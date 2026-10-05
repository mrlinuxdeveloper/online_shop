<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Cart;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\Payment as PaymentModel;
use App\Models\InventoryTransaction;
use App\Models\Coupon;
use App\Models\CouponUsage;

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
        if ((int) $order->user_id !== (int) auth()->id()) {
            abort(403);
        }

        /*
         * اگر سفارش قبلاً پرداخت شده
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
         * مبلغ سفارش
         */
        $amount = (int) $order->total_price;

        if ($amount <= 0) {
            return redirect()
                ->route('cart.index')
                ->with(
                    'error',
                    'مبلغ سفارش معتبر نیست.'
                );
        }

        /*
         * پیدا کردن Payment قبلی
         */
        $paymentRecord = PaymentModel::where(
            'order_id',
            $order->id
        )
            ->whereIn('status', ['pending', 'failed'])
            ->latest()
            ->first();

        /*
         * اگر Payment وجود نداشت، ایجاد می‌کنیم
         */
        if (!$paymentRecord) {

            $paymentRecord = PaymentModel::create([
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'amount' => $amount,
                'gateway' => 'zarinpal',
                'status' => 'pending',
                'description' =>
                    'Payment for order ' .
                    $order->order_number,
            ]);

        } else {

            /*
             * تلاش مجدد برای پرداخت
             */
            $paymentRecord->update([
                'amount' => $amount,
                'status' => 'pending',
                'description' =>
                    'Payment for order ' .
                    $order->order_number,
            ]);
        }

        /*
         * Invoice مربوط به Multipay
         */
        $invoice = (new Invoice())
            ->amount($amount);

        $invoice->detail([
            'description' =>
                'Payment for order ' .
                $order->order_number,

            'order_id' =>
                $order->id,

            'order_number' =>
                $order->order_number,
        ]);

        /*
         * اطلاعات لازم برای Callback
         */
        session()->put(
            'payment_order_id',
            $order->id
        );

        session()->put(
            'payment_id',
            $paymentRecord->id
        );

        /*
         * Payment
         */
        $payment = new Payment(
            config('payment')
        );

        /*
         * ارسال به درگاه
         */
        return $payment
            ->callbackUrl(
                route('payment.verify')
            )
            ->purchase(
                $invoice,

                function (
                    $driver,
                    $transactionId
                ) use ($paymentRecord) {

                    /*
                     * ذخیره Authority
                     */
                    $paymentRecord->update([
                        'authority' =>
                            $transactionId,
                    ]);

                    /*
                     * ذخیره Transaction ID
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
         * اطلاعات پرداخت از Session
         */
        $orderId = session(
            'payment_order_id'
        );

        $paymentId = session(
            'payment_id'
        );

        $transactionId = session(
            'payment_transaction_id'
        );

        /*
         * اگر Transaction ID در Session نبود،
         * از Payment می‌خوانیم.
         */
        if (!$transactionId && $paymentId) {

            $paymentRecord = PaymentModel::find(
                $paymentId
            );

            if ($paymentRecord) {
                $transactionId =
                    $paymentRecord->authority;
            }
        }

        /*
         * پیدا کردن سفارش
         */
        $order = Order::find(
            $orderId
        );

        if (!$order) {

            return redirect()
                ->route('cart.index')
                ->with(
                    'error',
                    'سفارش پیدا نشد.'
                );
        }

        /*
         * پیدا کردن Payment
         */
        $paymentRecord = PaymentModel::find(
            $paymentId
        );

        if (!$paymentRecord) {

            $paymentRecord = PaymentModel::where(
                'order_id',
                $order->id
            )
                ->latest()
                ->first();
        }

        if (!$paymentRecord) {

            return redirect()
                ->route('cart.index')
                ->with(
                    'error',
                    'رکورد پرداخت پیدا نشد.'
                );
        }

        /*
         * اگر قبلاً پرداخت شده
         */
        if ($order->payment_status === 'paid') {

            return redirect()
                ->route(
                    'checkout.success',
                    $order
                )
                ->with(
                    'success',
                    'این سفارش قبلاً پرداخت شده است.'
                );
        }

        /*
         * Transaction ID باید وجود داشته باشد
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
            $referenceId =
                $receipt->getReferenceId();

            /*
             * ---------------------------------------------------------
             * ثبت تمام اطلاعات پرداخت
             * داخل یک Transaction دیتابیس
             * ---------------------------------------------------------
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
                $lockedOrder = Order::where(
                    'id',
                    $order->id
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                /*
                 * اگر Callback تکراری باشد،
                 * هیچ عملیات دیگری انجام نمی‌دهیم.
                 */
                if (
                    $lockedOrder->payment_status === 'paid'
                ) {
                    return;
                }

                /*
                 * -----------------------------------------------------
                 * Payment
                 * -----------------------------------------------------
                 */
                $paymentRecord->update([
                    'status' => 'paid',

                    'transaction_id' =>
                        $referenceId,

                    'authority' =>
                        $transactionId,

                    'reference_id' =>
                        $referenceId,

                    'paid_at' =>
                        now(),
                ]);

                /*
                 * -----------------------------------------------------
                 * Order
                 * -----------------------------------------------------
                 */
                $lockedOrder->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                ]);

                /*
                 * -----------------------------------------------------
                 * Invoice
                 * -----------------------------------------------------
                 */
                if ($lockedOrder->invoice) {

                    $lockedOrder->invoice->update([
                        'status' => 'issued',
                    ]);
                }

                /*
                 * -----------------------------------------------------
                 * Coupon
                 * -----------------------------------------------------
                 *
                 * Coupon فقط بعد از پرداخت موفق مصرف می‌شود.
                 */
                $paymentCoupon = session(
                    'payment_coupon'
                );

                if (
                    $paymentCoupon &&
                    (int) (
                        $paymentCoupon['order_id']
                        ?? 0
                    ) ===
                    (int) $lockedOrder->id
                ) {

                    $couponId =
                        $paymentCoupon['coupon_id']
                        ?? null;

                    if ($couponId) {

                        /*
                         * قفل کردن Coupon
                         */
                        $coupon = Coupon::where(
                            'id',
                            $couponId
                        )
                            ->lockForUpdate()
                            ->first();

                        if ($coupon) {

                            /*
                             * بررسی اینکه قبلاً
                             * برای این سفارش CouponUsage
                             * ثبت نشده باشد.
                             */
                            $existingUsage =
                                CouponUsage::where(
                                    'order_id',
                                    $lockedOrder->id
                                )->first();

                            if (!$existingUsage) {

                                /*
                                 * ثبت مصرف Coupon
                                 */
                                CouponUsage::create([
                                    'coupon_id' =>
                                        $coupon->id,

                                    'user_id' =>
                                        $lockedOrder->user_id,

                                    'order_id' =>
                                        $lockedOrder->id,

                                    'discount_amount' =>
                                        $lockedOrder->discount,
                                ]);

                                /*
                                 * افزایش تعداد استفاده
                                 */
                                $coupon->increment(
                                    'used_count'
                                );
                            }
                        }
                    }
                }

                /*
                 * -----------------------------------------------------
                 * کاهش موجودی محصولات
                 * -----------------------------------------------------
                 */
                $lockedOrder->load([
                    'items.variant',
                ]);

                foreach (
                    $lockedOrder->items
                    as $item
                ) {

                    /*
                     * اگر Variant وجود نداشت
                     */
                    if (!$item->variant) {
                        continue;
                    }

                    /*
                     * قفل Variant
                     */
                    $variant =
                        ProductVariant::where(
                            'id',
                            $item->product_variant_id
                        )
                            ->lockForUpdate()
                            ->firstOrFail();

                    /*
                     * موجودی قبل از فروش
                     */
                    $stockBefore =
                        (int) $variant->stock;

                    /*
                     * تعداد فروش
                     */
                    $quantity =
                        (int) $item->quantity;

                    /*
                     * بررسی موجودی
                     */
                    if (
                        $stockBefore <
                        $quantity
                    ) {

                        throw new \RuntimeException(
                            'موجودی محصول "' .
                            $item->product_name .
                            '" کافی نیست.'
                        );
                    }

                    /*
                     * موجودی بعد از فروش
                     */
                    $stockAfter =
                        $stockBefore -
                        $quantity;

                    /*
                     * بروزرسانی موجودی
                     */
                    $variant->update([
                        'stock' =>
                            $stockAfter,
                    ]);

                    /*
                     * ثبت Inventory Transaction
                     */
                    InventoryTransaction::create([
                        'product_id' =>
                            $item->product_id,

                        'product_variant_id' =>
                            $variant->id,

                        'type' =>
                            'sale',

                        /*
                         * فروش به صورت منفی
                         */
                        'quantity' =>
                            -$quantity,

                        'stock_before' =>
                            $stockBefore,

                        'stock_after' =>
                            $stockAfter,

                        'description' =>
                            'فروش بابت سفارش ' .
                            $lockedOrder
                                ->order_number,
                    ]);
                }

                /*
                 * -----------------------------------------------------
                 * خالی کردن Cart
                 * -----------------------------------------------------
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
             * پاک کردن Sessionهای پرداخت
             */
            session()->forget([
                'payment_order_id',
                'payment_id',
                'payment_transaction_id',
                'payment_coupon',
            ]);

            /*
             * انتقال به صفحه موفقیت
             */
            return redirect()
                ->route(
                    'checkout.success',
                    $order
                )
                ->with(
                    'success',
                    'پرداخت با موفقیت انجام شد. شماره پیگیری: ' .
                    $referenceId
                );

        } catch (
        InvalidPaymentException $exception
        ) {

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

        } catch (
        \Throwable $exception
        ) {

            /*
             * خطای سیستمی
             */
            report($exception);

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
                    'خطایی هنگام تأیید پرداخت رخ داد.'
                );
        }
    }

/**
 * صفحه پرداخت ناموفق
 */
public function failed(Order $order)
{
    /*
     * بررسی مالکیت سفارش
     */
    if (
        (int) $order->user_id !==
        (int) auth()->id()
    ) {
        abort(403);
    }

    /*
     * سفارش پرداخت‌شده نباید
     * وارد صفحه پرداخت ناموفق شود.
     */
    if ($order->payment_status === 'paid') {
        return redirect()
            ->route(
                'checkout.success',
                $order
            );
    }

    /*
     * اطلاعات مورد نیاز صفحه
     */
    $order->load([
        'items',
        'address',
        'invoice',
    ]);

    return view(
        'payment.failed',
        compact('order')
    );
}

/**
 * تلاش مجدد برای پرداخت سفارش
 */
public function retry(Order $order)
{
    /*
     * بررسی مالکیت سفارش
     */
    if (
        (int) $order->user_id !==
        (int) auth()->id()
    ) {
        abort(403);
    }

    /*
     * اگر سفارش قبلاً پرداخت شده،
     * اجازه پرداخت مجدد نده.
     */
    if ($order->payment_status === 'paid') {
        return redirect()
            ->route(
                'checkout.success',
                $order
            );
    }

    /*
     * مبلغ سفارش باید معتبر باشد.
     */
    if ((int) $order->total_price <= 0) {
        return redirect()
            ->route(
                'orders.show',
                $order
            )
            ->with(
                'error',
                'مبلغ سفارش برای پرداخت معتبر نیست.'
            );
    }

    /*
     * آخرین Payment سفارش
     */
    $payment = PaymentModel::where(
        'order_id',
        $order->id
    )
        ->latest()
        ->first();

    /*
     * اگر Payment وجود نداشت،
     * یک Payment جدید ایجاد کن.
     */
    if (!$payment) {

        $payment = PaymentModel::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'amount' => $order->total_price,
            'gateway' => 'zarinpal',
            'status' => 'pending',
        ]);

    } else {

        /*
         * Payment قبلی را برای تلاش مجدد
         * دوباره pending می‌کنیم.
         */
        $payment->update([
            'amount' => $order->total_price,
            'status' => 'pending',
            'transaction_id' => null,
            'authority' => null,
            'reference_id' => null,
            'paid_at' => null,
        ]);
    }

    /*
     * وضعیت سفارش
     */
    $order->update([
        'status' => 'pending',
        'payment_status' => 'pending',
    ]);

    /*
     * اطلاعات پرداخت در Session
     */
    session([
        'payment_order_id' => $order->id,
        'payment_id' => $payment->id,
    ]);

    /*
     * payment_coupon را دست نمی‌زنیم.
     *
     * اگر سفارش Coupon داشته باشد،
     * Session قبلی همچنان برای verify()
     * قابل استفاده است.
     */

    try {

        $invoice = (new Invoice)
            ->amount($order->total_price);

        $paymentUrl = Payment::callbackUrl(
            route('payment.verify')
        )->purchase(
            $invoice,
            function ($driver, $transactionId) use (
                $payment
            ) {

                $payment->update([
                    'transaction_id' => $transactionId,
                ]);

            }
        );

        /*
         * شناسه پرداخت را در Session نگه می‌داریم.
         */
        session([
            'payment_transaction_id' =>
                $payment->transaction_id,
        ]);

        return redirect()->away(
            $paymentUrl
        );

    } catch (\Throwable $e) {

        /*
         * در صورت خطا، Payment ناموفق شود.
         */
        $payment->update([
            'status' => 'failed',
            'description' => $e->getMessage(),
        ]);

        $order->update([
            'status' => 'pending',
            'payment_status' => 'failed',
        ]);

        return redirect()
            ->route(
                'payment.failed',
                $order
            )
            ->with(
                'error',
                'شروع مجدد پرداخت انجام نشد. لطفاً دوباره تلاش کنید.'
            );
    }
}



}
