<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment as PaymentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Shetabit\Multipay\Invoice;
use Shetabit\Multipay\Payment;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;

class PaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Start Payment
    |--------------------------------------------------------------------------
    */

    public function payment(Order $order)
    {
        /*
         * فقط صاحب سفارش اجازه پرداخت دارد
         */
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        /*
         * اگر سفارش قبلاً پرداخت شده
         */
        if ($order->payment_status === 'paid') {
            return redirect()
                ->route('checkout.success', $order)
                ->with('success', 'این سفارش قبلاً پرداخت شده است.');
        }

        /*
         * مبلغ سفارش از دیتابیس گرفته می‌شود
         */
        $amount = (int) $order->total_price;

        if ($amount <= 0) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'مبلغ سفارش معتبر نیست.');
        }

        /*
         * اگر قبلاً برای این سفارش Payment ساخته شده،
         * همان رکورد را استفاده می‌کنیم.
         */
        $paymentRecord = PaymentModel::where('order_id', $order->id)
            ->whereIn('status', ['pending', 'failed'])
            ->latest()
            ->first();

        if (!$paymentRecord) {
            $paymentRecord = PaymentModel::create([
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'amount' => $amount,
                'gateway' => 'zarinpal',
                'status' => 'pending',
                'description' => 'Payment for order ' . $order->order_number,
            ]);
        } else {
            /*
             * اگر پرداخت قبلی ناموفق بوده،
             * برای تلاش جدید وضعیت را pending می‌کنیم.
             */
            $paymentRecord->update([
                'amount' => $amount,
                'status' => 'pending',
                'description' => 'Payment for order ' . $order->order_number,
            ]);
        }

        /*
         * Invoice مربوط به خود پکیج پرداخت
         */
        $invoice = (new Invoice())
            ->amount($amount);

        $invoice->detail([
            'description' => 'Payment for order ' . $order->order_number,
            'order_id' => $order->id,
            'order_number' => $order->order_number,
        ]);

        /*
         * اطلاعات لازم برای callback
         *
         * چون verify ممکن است خارج از middleware auth اجرا شود،
         * اطلاعات سفارش را در session نگه می‌داریم.
         */
        session()->put('payment_order_id', $order->id);
        session()->put('payment_id', $paymentRecord->id);

        /*
         * ساخت Payment بر اساس نسخه فعلی Multipay
         */
        $payment = new Payment(
            config('payment')
        );

        /*
         * ارسال به درگاه
         */
        return $payment
            ->callbackUrl(route('payment.verify'))
            ->purchase(
                $invoice,

                function ($driver, $transactionId) use ($paymentRecord) {

                    /*
                     * transactionId دریافت شده از درگاه
                     */
                    $paymentRecord->update([
                        'authority' => $transactionId,
                    ]);

                    /*
                     * برای verify
                     */
                    session()->put(
                        'payment_transaction_id',
                        $transactionId
                    );
                }
            )
            ->pay()
            ->render();
    }


    /*
    |--------------------------------------------------------------------------
    | Verify Payment
    |--------------------------------------------------------------------------
    */

    public function verify(Request $request)
    {
        /*
         * اطلاعات پرداخت از session
         */
        $orderId = session('payment_order_id');
        $paymentId = session('payment_id');
        $transactionId = session('payment_transaction_id');

        /*
         * اگر transactionId در session نبود،
         * از رکورد Payment می‌خوانیم.
         */
        if (!$transactionId && $paymentId) {

            $paymentRecord = PaymentModel::find($paymentId);

            if ($paymentRecord) {
                $transactionId = $paymentRecord->authority;
            }
        }

        /*
         * سفارش
         */
        $order = Order::find($orderId);

        if (!$order) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'سفارش پیدا نشد.');
        }

        /*
         * رکورد پرداخت
         */
        $paymentRecord = PaymentModel::find($paymentId);

        if (!$paymentRecord) {

            $paymentRecord = PaymentModel::where('order_id', $order->id)
                ->latest()
                ->first();
        }

        if (!$paymentRecord) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'رکورد پرداخت پیدا نشد.');
        }

        /*
         * اگر قبلاً پرداخت شده
         */
        if ($order->payment_status === 'paid') {

            return redirect()
                ->route('checkout.success', $order)
                ->with(
                    'success',
                    'این سفارش قبلاً پرداخت شده است.'
                );
        }

        /*
         * transactionId باید وجود داشته باشد
         */
        if (!$transactionId) {

            $paymentRecord->update([
                'status' => 'failed',
            ]);

            return redirect()
                ->route('cart.index')
                ->with(
                    'error',
                    'شناسه تراکنش پرداخت پیدا نشد.'
                );
        }

        /*
         * مبلغ سفارش
         */
        $amount = (int) $order->total_price;

        try {

            /*
             * ساخت Payment
             */
            $payment = new Payment(
                config('payment')
            );

            /*
             * Verify
             */
            $receipt = $payment
                ->amount($amount)
                ->transactionId($transactionId)
                ->verify();

            /*
             * شماره مرجع پرداخت
             */
            $referenceId = $receipt->getReferenceId();

            /*
             * ثبت اطلاعات پرداخت
             */
            DB::transaction(function () use (
                $order,
                $paymentRecord,
                $transactionId,
                $referenceId
            ) {

                /*
                 * قفل کردن سفارش
                 */
                $lockedOrder = Order::where('id', $order->id)
                    ->lockForUpdate()
                    ->first();

                /*
                 * اگر همزمان قبلاً پرداخت شده،
                 * دوباره عملیات انجام نشود.
                 */
                if ($lockedOrder->payment_status === 'paid') {
                    return;
                }

                /*
                 * Payment
                 */
                $paymentRecord->update([
                    'status' => 'paid',
                    'transaction_id' => $referenceId,
                    'authority' => $transactionId,
                    'reference_id' => $referenceId,
                    'paid_at' => now(),
                ]);

                /*
                 * وضعیت سفارش
                 */
                $lockedOrder->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                ]);

                /*
                 * Invoice
                 *
                 * status جدول invoices در پروژه تو
                 * issued / cancelled است.
                 */
                if ($lockedOrder->invoice) {
                    $lockedOrder->invoice->update([
                        'status' => 'issued',
                    ]);
                }

                /*
                 * کاهش موجودی Variantها
                 */
                $lockedOrder->load([
                    'items.variant',
                ]);

                foreach ($lockedOrder->items as $item) {

                    if ($item->variant) {

                        $variant = $item->variant;

                        /*
                         * کنترل موجودی
                         */
                        if ($variant->stock < $item->quantity) {

                            throw new \Exception(
                                'موجودی محصول ' .
                                $item->product_name .
                                ' کافی نیست.'
                            );
                        }

                        $variant->decrement(
                            'stock',
                            $item->quantity
                        );
                    }
                }

                /*
                 * خالی کردن سبد خرید
                 */
                $cart = Cart::where(
                    'user_id',
                    $lockedOrder->user_id
                )->first();

                if ($cart) {
                    $cart->items()->delete();
                }
            });

            /*
             * پاک کردن اطلاعات موقت پرداخت
             */
            session()->forget([
                'payment_order_id',
                'payment_id',
                'payment_transaction_id',
            ]);

            /*
             * انتقال به صفحه موفقیت
             */
            return redirect()
                ->route('checkout.success', $order)
                ->with(
                    'success',
                    'پرداخت با موفقیت انجام شد. شماره پیگیری: ' .
                    $referenceId
                );

        } catch (InvalidPaymentException $exception) {

            /*
             * پرداخت ناموفق
             */
            $paymentRecord->update([
                'status' => 'failed',
            ]);

            $order->update([
                'payment_status' => 'failed',
            ]);

            return redirect()
                ->route('cart.index')
                ->with(
                    'error',
                    $exception->getMessage()
                );

        } catch (\Throwable $exception) {

            report($exception);

            $paymentRecord->update([
                'status' => 'failed',
            ]);

            return redirect()
                ->route('cart.index')
                ->with(
                    'error',
                    'خطایی هنگام تأیید پرداخت رخ داد.'
                );
        }
    }
}
