<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment as PaymentModel;
use Illuminate\Support\Facades\DB;
use Shetabit\Multipay\Invoice;
use Shetabit\Multipay\Payment;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;

class PaymentController extends Controller
{
    /**
     * شروع پرداخت سفارش
     */
    public function payment(Order $order)
    {
        /*
         * فقط صاحب سفارش می‌تواند پرداخت را انجام دهد.
         */
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        /*
         * اگر سفارش قبلاً پرداخت شده است.
         */
        if ($order->payment_status === 'paid') {
            return redirect()
                ->route('checkout.success', $order)
                ->with(
                    'success',
                    'This order has already been paid.'
                );
        }

        /*
         * مبلغ سفارش
         */
        $amount = (int) $order->total_price;

        if ($amount <= 0) {
            return redirect()
                ->route('checkout.success', $order)
                ->with(
                    'error',
                    'Invalid payment amount.'
                );
        }

        $paymentRecord = null;

        try {

            /*
             * Invoice مربوط به Shetabit
             */
            $invoice = (new Invoice())
                ->amount($amount);

            /*
             * اطلاعات اضافی Invoice
             */
            $invoice->detail([
                'description' => 'Payment for order ' . $order->order_number,
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ]);

            /*
             * ثبت Payment در دیتابیس خودمان
             */
            $paymentRecord = PaymentModel::create([
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'amount' => $amount,
                'gateway' => 'zarinpal',
                'status' => 'pending',
                'description' => 'Payment for order ' . $order->order_number,
            ]);

            /*
             * طبق API نسخه 3.4:
             *
             * Payment باید با config پروژه ساخته شود.
             */
            $payment = new Payment(
                config('payment')
            );

            /*
             * ارسال درخواست خرید به زرین پال
             *
             * transactionId در callback رسمی
             * به ما تحویل داده می‌شود.
             */
            return $payment
                ->callbackUrl(
                    route('payment.verify')
                )
                ->purchase(
                    $invoice,
                    function ($driver, $transactionId) use (
                        $paymentRecord
                    ) {

                        /*
                         * ذخیره Authority / Transaction ID
                         */
                        $paymentRecord->update([
                            'authority' => $transactionId,
                        ]);
                    }
                )
                ->pay()
                ->render();

        } catch (\Throwable $e) {

            /*
             * ثبت خطا در Laravel log
             */
            report($e);

            if ($paymentRecord) {
                $paymentRecord->update([
                    'status' => 'failed',
                    'description' => $e->getMessage(),
                ]);
            }

            /*
             * فعلاً خطای واقعی را نمایش می‌دهیم
             * تا در مرحله تست مشخص شود مشکل کجاست.
             */
            return redirect()
                ->route('checkout.success', $order)
                ->with(
                    'error',
                    'Payment error: ' . $e->getMessage()
                );
        }
    }


    /**
     * Callback زرین پال
     */
    public function verify()
    {
        /*
         * Authority ارسال‌شده توسط زرین پال
         */
        $authority = request()->query('Authority');

        /*
         * وضعیت پرداخت
         */
        $status = request()->query('Status');

        /*
         * اگر کاربر پرداخت را لغو کرده باشد.
         */
        if (
            !$authority ||
            strtoupper((string) $status) !== 'OK'
        ) {
            return redirect()
                ->route('dashboard')
                ->with(
                    'error',
                    'Payment was cancelled.'
                );
        }

        /*
         * پیدا کردن Payment خودمان
         */
        $paymentRecord = PaymentModel::where(
            'authority',
            $authority
        )->first();

        if (!$paymentRecord) {
            return redirect()
                ->route('dashboard')
                ->with(
                    'error',
                    'Payment record not found.'
                );
        }

        /*
         * پیدا کردن سفارش
         */
        $order = Order::find(
            $paymentRecord->order_id
        );

        if (!$order) {
            return redirect()
                ->route('dashboard')
                ->with(
                    'error',
                    'Order not found.'
                );
        }

        /*
         * اگر قبلاً پرداخت شده است.
         */
        if (
            $paymentRecord->status === 'paid' ||
            $order->payment_status === 'paid'
        ) {
            return redirect()
                ->route('checkout.success', $order)
                ->with(
                    'success',
                    'Payment has already been verified.'
                );
        }

        try {

            /*
             * Payment جدید برای Verify
             */
            $payment = new Payment(
                config('payment')
            );

            /*
             * طبق مستندات رسمی:
             *
             * amount()
             * transactionId()
             * verify()
             */
            $receipt = $payment
                ->amount(
                    (int) $paymentRecord->amount
                )
                ->transactionId(
                    $authority
                )
                ->verify();

            /*
             * Reference ID زرین پال
             */
            $referenceId = $receipt->getReferenceId();

            /*
             * ثبت نهایی پرداخت
             */
            DB::transaction(function () use (
                $order,
                $paymentRecord,
                $authority,
                $referenceId
            ) {

                /*
                 * قفل سفارش
                 */
                $lockedOrder = Order::where(
                    'id',
                    $order->id
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                /*
                 * اگر قبلاً پرداخت شده است.
                 */
                if (
                    $lockedOrder->payment_status === 'paid'
                ) {
                    return;
                }

                /*
                 * قفل Payment
                 */
                $lockedPayment = PaymentModel::where(
                    'id',
                    $paymentRecord->id
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                /*
                 * بارگذاری Variant های سفارش
                 */
                $lockedOrder->load([
                    'items.variant',
                ]);

                /*
                 * بررسی و کاهش موجودی
                 */
                foreach ($lockedOrder->items as $orderItem) {

                    if (!$orderItem->variant) {
                        throw new \RuntimeException(
                            'Product variant not found.'
                        );
                    }

                    $variant = $orderItem->variant
                        ->newQuery()
                        ->where(
                            'id',
                            $orderItem->variant->id
                        )
                        ->lockForUpdate()
                        ->first();

                    if (!$variant) {
                        throw new \RuntimeException(
                            'Product variant not found.'
                        );
                    }

                    if (
                        $variant->stock <
                        $orderItem->quantity
                    ) {
                        throw new \RuntimeException(
                            'Insufficient stock.'
                        );
                    }

                    $variant->decrement(
                        'stock',
                        $orderItem->quantity
                    );
                }

                /*
                 * ثبت Payment موفق
                 */
                $lockedPayment->update([
                    'status' => 'paid',
                    'transaction_id' => $authority,
                    'reference_id' => $referenceId,
                    'paid_at' => now(),
                ]);

                /*
                 * تغییر وضعیت سفارش
                 */
                $lockedOrder->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                ]);

                /*
                 * Invoice ما:
                 *
                 * طبق ساختار فعلی دیتابیس شما
                 * status فقط issued/cancelled است.
                 */
                if ($lockedOrder->invoice) {
                    $lockedOrder->invoice->update([
                        'status' => 'issued',
                    ]);
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
             * پرداخت موفق
             */
            return redirect()
                ->route('checkout.success', $order)
                ->with(
                    'success',
                    'Payment completed successfully.'
                );

        } catch (InvalidPaymentException $e) {

            /*
             * پرداخت توسط درگاه تأیید نشده است.
             */
            report($e);

            $paymentRecord->update([
                'status' => 'failed',
                'description' => $e->getMessage(),
            ]);

            return redirect()
                ->route('checkout.success', $order)
                ->with(
                    'error',
                    'Payment verification failed: ' .
                    $e->getMessage()
                );

        } catch (\Throwable $e) {

            /*
             * خطای عمومی
             */
            report($e);

            return redirect()
                ->route('checkout.success', $order)
                ->with(
                    'error',
                    'An error occurred while verifying payment: ' .
                    $e->getMessage()
                );
        }
    }
}
