<?php


namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment as PaymentModel;
use App\Models\Invoice as OrderInvoice;
use Illuminate\Support\Facades\DB;
use Shetabit\Multipay\Invoice as GatewayInvoice;
use Shetabit\Payment\Facade\Payment;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;

class PaymentController extends Controller
{
    /**
     * شروع پرداخت سفارش
     */
    public function payment(Order $order)
    {
        // فقط صاحب سفارش اجازه پرداخت دارد
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // اگر قبلاً پرداخت شده
        if ($order->payment_status === 'paid') {
            return redirect()
                ->route('checkout.success', $order)
                ->with('success', 'This order has already been paid.');
        }

        $amount = (int) $order->total_price;

        if ($amount <= 0) {
            return redirect()
                ->route('checkout.success', $order)
                ->with('error', 'Invalid payment amount.');
        }

        $paymentRecord = null;

        try {

            /*
             * Invoice مربوط به Shetabit
             */
            $gatewayInvoice = (new GatewayInvoice())
                ->amount($amount);

            $gatewayInvoice->detail([
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
             * ارسال درخواست پرداخت به زرین‌پال
             */
            $payment = Payment::callbackUrl(
                route('payment.verify')
            )->purchase($gatewayInvoice);

            /*
             * در MultiPay، Authority داخل Invoice قرار می‌گیرد.
             */
            $authority = $gatewayInvoice->getTransactionId();

            if (!$authority) {
                throw new \RuntimeException(
                    'ZarinPal authority was not received.'
                );
            }

            /*
             * ذخیره Authority
             */
            $paymentRecord->update([
                'authority' => $authority,
            ]);

            /*
             * انتقال کاربر به درگاه
             */
            return $payment->pay()->render();

        } catch (\Throwable $e) {

            report($e);

            if ($paymentRecord) {
                $paymentRecord->update([
                    'status' => 'failed',
                    'description' => 'Payment initialization failed.',
                ]);
            }

            return redirect()
                ->route('checkout.success', $order)
                ->with(
                    'error',
                    'Unable to start payment.'
                );
        }
    }


    /**
     * callback زرین‌پال
     */
    public function verify()
    {
        $authority = request()->query('Authority');
        $status = request()->query('Status');

        /*
         * کاربر پرداخت را لغو کرده
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
        $order = Order::find($paymentRecord->order_id);

        if (!$order) {
            return redirect()
                ->route('dashboard')
                ->with(
                    'error',
                    'Order not found.'
                );
        }

        /*
         * اگر قبلاً پرداخت و ثبت نهایی شده
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
             * قبل از verify، موجودی سفارش را بررسی می‌کنیم.
             *
             * اینجا Cart را بررسی نمی‌کنیم.
             * موجودی باید از OrderItem بررسی شود.
             */
            $order->load([
                'items.variant',
            ]);

            foreach ($order->items as $orderItem) {

                if (!$orderItem->variant) {
                    throw new \RuntimeException(
                        'Product variant not found.'
                    );
                }

                if (
                    $orderItem->variant->stock <
                    $orderItem->quantity
                ) {
                    throw new \RuntimeException(
                        'Insufficient stock.'
                    );
                }
            }

            /*
             * ساخت Payment مربوط به Gateway
             */
            $gatewayPayment = Payment::callbackUrl(
                route('payment.verify')
            );

            $gatewayPayment->amount(
                (int) $paymentRecord->amount
            );

            $gatewayPayment->transactionId(
                $authority
            );

            /*
             * Verify زرین‌پال
             */
            $receipt = $gatewayPayment->verify();

            /*
             * Reference ID
             */
            $referenceId = $receipt->getReferenceId();

            /*
             * نهایی‌کردن سفارش در یک Transaction
             */
            DB::transaction(function () use (
                $order,
                $paymentRecord,
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
                 * اگر همزمان callback دیگری رسید،
                 * موجودی دوباره کم نشود.
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
                 * دریافت OrderItems
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

                    /*
                     * قفل Variant
                     */
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

                    /*
                     * بررسی موجودی در لحظه نهایی‌سازی
                     */
                    if (
                        $variant->stock <
                        $orderItem->quantity
                    ) {
                        throw new \RuntimeException(
                            'Insufficient stock.'
                        );
                    }

                    /*
                     * کم کردن موجودی
                     */
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
                 * تغییر وضعیت Invoice
                 */
                if ($lockedOrder->invoice) {
                    $lockedOrder->invoice->update([
                        'status' => 'paid',
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

            report($e);

            $paymentRecord->update([
                'status' => 'failed',
                'description' => 'Payment verification failed.',
            ]);

            return redirect()
                ->route('checkout.success', $order)
                ->with(
                    'error',
                    'Payment verification failed.'
                );

        } catch (\Throwable $e) {

            report($e);

            /*
             * متن واقعی exception را به کاربر نشان نمی‌دهیم.
             */
            return redirect()
                ->route('checkout.success', $order)
                ->with(
                    'error',
                    'An error occurred while verifying payment.'
                );
        }
    }
}





//namespace App\Http\Controllers;
//
//use App\Models\Cart;
//use App\Models\Order;
//use App\Models\Payment as PaymentModel;
//use Illuminate\Support\Facades\DB;
//use Shetabit\Multipay\Invoice;
//use Shetabit\Payment\Facade\Payment;
//use Shetabit\Multipay\Exceptions\InvalidPaymentException;
//
//class PaymentController extends Controller
//{
//    public function payment(Order $order)
//    { /* * فقط صاحب سفارش می‌تواند پرداخت را شروع کند. */
//        if ($order->user_id !== auth()->id()) {
//            abort(403);
//        } /* * اگر قبلاً پرداخت شده، دوباره پرداخت نکن. */
//        if ($order->payment_status === 'paid') {
//            return redirect()->route('checkout.success', $order)->with('success', 'This order has already been paid.');
//        }
//        $amount = (int)$order->total_price;
//        if ($amount <= 0) {
//            return redirect()->route('checkout.success', $order)->with('error', 'Invalid payment amount.');
//        }
//        try { /* * ساخت Invoice مربوط به Shetabit */
//            $invoice = (new Invoice)->amount($amount);
//            $invoice->detail(['description' => 'Payment for order ' . $order->order_number, 'order_id' => $order->id, 'order_number' => $order->order_number,]); /* * ثبت Payment در دیتابیس خودمان */
//            $paymentRecord = PaymentModel::create(['order_id' => $order->id, 'user_id' => $order->user_id, 'amount' => $amount, 'gateway' => 'zarinpal', 'status' => 'pending', 'description' => 'Payment for order ' . $order->order_number,]); /* * ارسال درخواست به زرین پال */
//            $payment = Payment::callbackUrl(route('payment.verify'))->purchase($invoice); /* * purchase() مقدار Authority زرین پال * را داخل transactionId قرار می‌دهد. * * در این نسخه از پکیج، getInvoice() * روی Payment عمومی نیست. * * بنابراین Authority را از طریق transactionId * خود Payment می‌گیریم. */
//            $authority = null; /* * چون Payment::transactionId() * فقط setter است، برای گرفتن Authority * باید آن را از Invoice اولیه بخوانیم. * * بنابراین این بخش را با همان Invoice * که به purchase داده‌ایم انجام می‌دهیم. */
//            $authority = $invoice->getTransactionId();
//            if (!$authority) {
//                throw new \RuntimeException('ZarinPal authority was not received.');
//            } /* * ذخیره Authority */
//            $paymentRecord->update(['authority' => $authority,]); /* * انتقال کاربر به زرین پال */
//            return $payment->pay()->render();
//        } catch (\Throwable $e) {
//            report($e);
//            if (isset($paymentRecord)) {
//                $paymentRecord->update(['status' => 'failed', 'description' => $e->getMessage(),]);
//            }
//            return redirect()->route('checkout.success', $order)->with('error', 'Unable to start payment.');
//        }
//    }
//
//    public function verify()
//    { /* * اطلاعات callback زرین پال */
//        $authority = request()->query('Authority');
//        $status = request()->query('Status'); /* * پرداخت توسط کاربر لغو شده */
//        if (!$authority || strtoupper((string)$status) !== 'OK') {
//            return redirect()->route('dashboard')->with('error', 'Payment was cancelled.');
//        } /* * پیدا کردن Payment خودمان */
//        $paymentRecord = PaymentModel::where('authority', $authority)->first();
//        if (!$paymentRecord) {
//            return redirect()->route('dashboard')->with('error', 'Payment record not found.');
//        } /* * پیدا کردن سفارش */
//        $order = Order::findOrFail($paymentRecord->order_id); /* * اگر قبلاً پرداخت شده */
//        if ($paymentRecord->status === 'paid') {
//            return redirect()->route('checkout.success', $order)->with('success', 'Payment has already been verified.');
//        }
//        try { /* * Payment خودش یک Invoice داخلی دارد. * * invoice() protected است، بنابراین * نباید از ->invoice($invoice) استفاده کنیم. * * amount() و transactionId() روی Payment * Invoice داخلی را تنظیم می‌کنند. */
//            $payment = Payment::callbackUrl(route('payment.verify'));
//            $payment->amount((int)$paymentRecord->amount);
//            $payment->transactionId($authority); /* * Verify */
//            $receipt = $payment->verify(); /* * ref_id زرین پال */
//            $referenceId = $receipt->getReferenceId(); /* * ثبت نهایی پرداخت و کاهش موجودی */
//            DB::transaction(function () use ($order, $paymentRecord, $referenceId) { /* * قفل سفارش */
//                $lockedOrder = Order::where('id', $order->id)->lockForUpdate()->firstOrFail(); /* * اگر سفارش قبلاً پرداخت شده */
//                if ($lockedOrder->payment_status === 'paid') {
//                    return;
//                } /* * قفل Payment */
//                $lockedPayment = PaymentModel::where('id', $paymentRecord->id)->lockForUpdate()->firstOrFail(); /* * ثبت پرداخت موفق */
//                $lockedPayment->update(['status' => 'paid', 'transaction_id' => $paymentRecord->authority, 'reference_id' => $referenceId, 'paid_at' => now(),]); /* * تغییر وضعیت سفارش */
//                $lockedOrder->update(['payment_status' => 'paid', 'status' => 'processing',]); /* * سفارش را همراه OrderItem و Variant می‌گیریم. * * موجودی باید بر اساس سفارش کم شود، * نه Cart فعلی کاربر. */
//                $lockedOrder->load(['items.variant']); /* * کاهش موجودی */
//                foreach ($lockedOrder->items as $orderItem) {
//                    if (!$orderItem->variant) {
//                        continue;
//                    } /* * قفل Variant */
//                    $variant = $orderItem->variant->newQuery()->where('id', $orderItem->variant->id)->lockForUpdate()->first();
//                    if (!$variant) {
//                        throw new \RuntimeException('Product variant not found.');
//                    } /* * بررسی موجودی */
//                    if ($variant->stock < $orderItem->quantity) {
//                        throw new \RuntimeException('Insufficient stock for product variant.');
//                    } /* * کاهش موجودی */
//                    $variant->decrement('stock', $orderItem->quantity);
//                } /* * بعد از پرداخت موفق، * Cart کاربر خالی می‌شود. */
//                $cart = Cart::where('user_id', $lockedOrder->user_id)->first();
//                if ($cart) {
//                    $cart->items()->delete();
//                }
//            }); /* * پرداخت موفق */
//            return redirect()->route('checkout.success', $order)->with('success', 'Payment completed successfully.');
//        } catch (InvalidPaymentException $e) {
//            report($e);
//            $paymentRecord->update(['status' => 'failed', 'description' => $e->getMessage(),]);
//            return redirect()->route('checkout.success', $order)->with('error', 'Payment verification failed.');
//        } catch (\Throwable $e) {
//            report($e);
//            return redirect()->route('checkout.success', $order)->with('error', 'An error occurred while verifying payment.');
//        }
//    }
//}
