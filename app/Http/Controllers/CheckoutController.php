<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Invoice;
use App\Models\Coupon;

class CheckoutController extends Controller
{
    /**
     * نمایش صفحه Checkout
     */
    public function index()
    {
        $cart = Cart::where(
            'user_id',
            auth()->id()
        )
            ->with([
                'items.product.images',
                'items.variant.size',
                'items.variant.color',
            ])
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with(
                    'error',
                    'Your cart is empty.'
                );
        }

        $addresses = Address::where(
            'user_id',
            auth()->id()
        )
            ->orderByDesc('is_default')
            ->latest()
            ->get();

        /*
         * محاسبه Subtotal
         */
        $subtotal = $cart->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        /*
         * Coupon ذخیره‌شده در Session
         */
        $checkoutCoupon = session(
            'checkout_coupon'
        );

        /*
         * مقدار اولیه تخفیف
         */
        $discount = 0;

        /*
         * بررسی مجدد Coupon
         */
        if ($checkoutCoupon) {

            $coupon = Coupon::find(
                $checkoutCoupon['id'] ?? null
            );

            if (
                $coupon &&
                $coupon->status &&
                (
                    !$coupon->starts_at ||
                    now()->gte($coupon->starts_at)
                ) &&
                (
                    !$coupon->expires_at ||
                    now()->lte($coupon->expires_at)
                ) &&
                (
                    $coupon->usage_limit === null ||
                    $coupon->used_count < $coupon->usage_limit
                ) &&
                (
                    $coupon->min_order_amount === null ||
                    $subtotal >= $coupon->min_order_amount
                )
            ) {

                /*
                 * محاسبه دوباره Discount
                 */
                if ($coupon->type === 'percent') {

                    $discount =
                        ($subtotal * $coupon->value) / 100;

                } elseif ($coupon->type === 'fixed') {

                    $discount =
                        $coupon->value;
                }

                /*
                 * تخفیف بیشتر از Subtotal نباشد
                 */
                $discount = min(
                    $discount,
                    $subtotal
                );

            } else {

                /*
                 * Coupon دیگر معتبر نیست
                 */
                session()->forget(
                    'checkout_coupon'
                );

                $checkoutCoupon = null;
            }
        }

        /*
         * هزینه ارسال
         */
        $shippingCost = 0;

        /*
         * مالیات
         */
        $tax = 0;

        /*
         * مبلغ نهایی
         */
        $total =
            $subtotal
            - $discount
            + $shippingCost
            + $tax;

        /*
         * جلوگیری از مبلغ منفی
         */
        $total = max(
            0,
            $total
        );

        return view(
            'checkout.index',
            compact(
                'cart',
                'addresses',
                'subtotal',
                'discount',
                'shippingCost',
                'tax',
                'total'
            )
        );
    }


    /**
     * اعمال Coupon
     */
    public function applyCoupon(Request $request)
    {
        $validated = $request->validate([
            'coupon_code' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        /*
         * دریافت Cart
         */
        $cart = Cart::where(
            'user_id',
            auth()->id()
        )
            ->with('items')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return back()
                ->with(
                    'error',
                    'Your cart is empty.'
                );
        }

        /*
         * محاسبه Subtotal
         */
        $subtotal = $cart->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        /*
         * جستجوی Coupon
         */
        $coupon = Coupon::where(
            'code',
            $validated['coupon_code']
        )->first();

        if (!$coupon) {
            return back()
                ->withInput()
                ->withErrors([
                    'coupon_code' =>
                        'کد تخفیف معتبر نیست.'
                ]);
        }

        /*
         * بررسی وضعیت
         */
        if (!$coupon->status) {
            return back()
                ->withInput()
                ->withErrors([
                    'coupon_code' =>
                        'این کد تخفیف فعال نیست.'
                ]);
        }

        /*
         * بررسی تاریخ شروع
         */
        if (
            $coupon->starts_at &&
            now()->lt($coupon->starts_at)
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'coupon_code' =>
                        'این کد تخفیف هنوز فعال نشده است.'
                ]);
        }

        /*
         * بررسی تاریخ پایان
         */
        if (
            $coupon->expires_at &&
            now()->gt($coupon->expires_at)
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'coupon_code' =>
                        'تاریخ اعتبار این کد تخفیف تمام شده است.'
                ]);
        }

        /*
         * بررسی محدودیت استفاده
         */
        if (
            $coupon->usage_limit !== null &&
            $coupon->used_count >= $coupon->usage_limit
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'coupon_code' =>
                        'ظرفیت استفاده از این کد تخفیف تکمیل شده است.'
                ]);
        }

        /*
         * بررسی حداقل مبلغ سفارش
         */
        if (
            $coupon->min_order_amount !== null &&
            $subtotal < $coupon->min_order_amount
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'coupon_code' =>
                        'حداقل مبلغ سفارش برای این کد تخفیف ' .
                        number_format(
                            $coupon->min_order_amount
                        ) .
                        ' تومان است.'
                ]);
        }

        /*
         * محاسبه تخفیف
         */
        if ($coupon->type === 'percent') {

            $discount =
                ($subtotal * $coupon->value) / 100;

        } elseif ($coupon->type === 'fixed') {

            $discount =
                $coupon->value;

        } else {

            return back()
                ->withInput()
                ->withErrors([
                    'coupon_code' =>
                        'نوع کد تخفیف معتبر نیست.'
                ]);
        }

        /*
         * تخفیف بیشتر از Subtotal نباشد
         */
        $discount = min(
            $discount,
            $subtotal
        );

        /*
         * ذخیره Coupon در Session
         *
         * این اطلاعات تا زمان ایجاد Order
         * در Session باقی می‌ماند.
         */
        session([
            'checkout_coupon' => [
                'id' =>
                    $coupon->id,

                'code' =>
                    $coupon->code,

                'discount' =>
                    $discount,
            ],
        ]);

        return back()
            ->with(
                'success',
                'کد تخفیف با موفقیت اعمال شد.'
            );
    }


    /**
     * ایجاد Order
     */
    public function store(Request $request)
    {
        /*
         * coupon_code از این فرم دریافت نمی‌شود.
         *
         * Coupon قبلاً توسط applyCoupon
         * در Session ذخیره شده است.
         */
        $validated = $request->validate([
            'address_id' => [
                'required',
                'exists:addresses,id',
            ],

            'note' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        /*
         * بررسی مالکیت Address
         */
        $address = Address::where(
            'id',
            $validated['address_id']
        )
            ->where(
                'user_id',
                auth()->id()
            )
            ->firstOrFail();

        /*
         * دریافت Cart
         */
        $cart = Cart::where(
            'user_id',
            auth()->id()
        )
            ->with([
                'items.product.images',
                'items.variant.size',
                'items.variant.color',
            ])
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with(
                    'error',
                    'Your cart is empty.'
                );
        }

        DB::beginTransaction();

        try {

            /*
             * بررسی دوباره موجودی و Variant
             */
            foreach ($cart->items as $cartItem) {

                $variant =
                    $cartItem->variant;

                $product =
                    $cartItem->product;

                if (!$product) {
                    throw new \RuntimeException(
                        'A product in your cart no longer exists.'
                    );
                }

                if (!$variant) {
                    throw new \RuntimeException(
                        'A product variant in your cart no longer exists.'
                    );
                }

                if (!$variant->status) {
                    throw new \RuntimeException(
                        "The product variant {$variant->sku} is no longer available."
                    );
                }

                if (
                    $variant->stock <
                    $cartItem->quantity
                ) {
                    throw new \RuntimeException(
                        "Not enough stock available for {$product->name}."
                    );
                }
            }

            /*
             * محاسبه Subtotal
             */
            $subtotal = $cart->items->sum(function ($item) {
                return $item->price * $item->quantity;
            });

            /*
             * مقادیر اولیه
             */
            $discount = 0;

            $shippingCost = 0;

            $tax = 0;

            $coupon = null;

            /*
             * دریافت Coupon از Session
             */
            $checkoutCoupon = session(
                'checkout_coupon'
            );

            /*
             * بررسی Coupon
             */
            if ($checkoutCoupon) {

                /*
                 * دریافت Coupon با Lock
                 */
                $coupon = Coupon::where(
                    'id',
                    $checkoutCoupon['id'] ?? null
                )
                    ->lockForUpdate()
                    ->first();

                if (!$coupon) {
                    throw new \RuntimeException(
                        'کد تخفیف دیگر معتبر نیست.'
                    );
                }

                /*
                 * بررسی فعال بودن
                 */
                if (!$coupon->status) {
                    throw new \RuntimeException(
                        'این کد تخفیف دیگر فعال نیست.'
                    );
                }

                /*
                 * بررسی تاریخ شروع
                 */
                if (
                    $coupon->starts_at &&
                    now()->lt($coupon->starts_at)
                ) {
                    throw new \RuntimeException(
                        'این کد تخفیف هنوز فعال نشده است.'
                    );
                }

                /*
                 * بررسی تاریخ پایان
                 */
                if (
                    $coupon->expires_at &&
                    now()->gt($coupon->expires_at)
                ) {
                    throw new \RuntimeException(
                        'تاریخ اعتبار این کد تخفیف تمام شده است.'
                    );
                }

                /*
                 * بررسی تعداد استفاده
                 */
                if (
                    $coupon->usage_limit !== null &&
                    $coupon->used_count >= $coupon->usage_limit
                ) {
                    throw new \RuntimeException(
                        'ظرفیت استفاده از این کد تخفیف تکمیل شده است.'
                    );
                }

                /*
                 * بررسی حداقل مبلغ
                 */
                if (
                    $coupon->min_order_amount !== null &&
                    $subtotal < $coupon->min_order_amount
                ) {
                    throw new \RuntimeException(
                        'حداقل مبلغ سفارش برای این کد تخفیف ' .
                        number_format(
                            $coupon->min_order_amount
                        ) .
                        ' تومان است.'
                    );
                }

                /*
                 * محاسبه دوباره Discount
                 *
                 * به مبلغ Session اعتماد نمی‌کنیم.
                 */
                if ($coupon->type === 'percent') {

                    $discount =
                        ($subtotal * $coupon->value) / 100;

                } elseif ($coupon->type === 'fixed') {

                    $discount =
                        $coupon->value;

                } else {

                    throw new \RuntimeException(
                        'نوع کد تخفیف معتبر نیست.'
                    );
                }

                /*
                 * تخفیف بیشتر از Subtotal نباشد
                 */
                $discount = min(
                    $discount,
                    $subtotal
                );
            }

            /*
             * محاسبه مبلغ نهایی
             */
            $total =
                $subtotal
                - $discount
                + $shippingCost
                + $tax;

            /*
             * جلوگیری از مبلغ منفی
             */
            $total = max(
                0,
                $total
            );

            /*
             * ایجاد Order
             */
            $order = Order::create([
                'user_id' =>
                    auth()->id(),

                'address_id' =>
                    $address->id,

                'order_number' =>
                    'ORD-' . strtoupper(
                        Str::random(10)
                    ),

                'subtotal' =>
                    $subtotal,

                'discount' =>
                    $discount,

                'shipping_cost' =>
                    $shippingCost,

                'tax' =>
                    $tax,

                'total_price' =>
                    $total,

                'status' =>
                    'pending',

                'payment_status' =>
                    'pending',

                'note' =>
                    $validated['note'] ?? null,
            ]);

            /*
             * ایجاد Order Items
             */
            foreach ($cart->items as $cartItem) {

                $variant =
                    $cartItem->variant;

                $product =
                    $cartItem->product;

                /*
                 * تصویر محصول
                 */
                $productImage =
                    $product->images
                        ->firstWhere(
                            'is_primary',
                            true
                        );

                if (!$productImage) {
                    $productImage =
                        $product->images->first();
                }

                OrderItem::create([
                    'order_id' =>
                        $order->id,

                    'product_id' =>
                        $product->id,

                    'product_variant_id' =>
                        $variant->id,

                    'product_name' =>
                        $product->name,

                    'product_code' =>
                        $product->product_code,

                    'product_image' =>
                        $productImage?->image,

                    'size' =>
                        $variant->size?->name,

                    'color' =>
                        $variant->color?->name,

                    'price' =>
                        $cartItem->price,

                    'discount' =>
                        0,

                    'quantity' =>
                        $cartItem->quantity,

                    'total' =>
                        $cartItem->price *
                        $cartItem->quantity,
                ]);
            }

            /*
             * ایجاد Invoice
             */
            Invoice::create([
                'order_id' =>
                    $order->id,

                'invoice_number' =>
                    'INV-' . strtoupper(
                        Str::random(10)
                    ),

                'subtotal' =>
                    $subtotal,

                'discount' =>
                    $discount,

                'shipping_cost' =>
                    $shippingCost,

                'tax' =>
                    $tax,

                'total_price' =>
                    $total,

                'status' =>
                    'issued',

                'issued_at' =>
                    now(),
            ]);

            /*
             * Commit
             */
            DB::commit();

            /*
             * اگر Coupon وجود داشته باشد،
             * اطلاعات آن را برای مرحله پرداخت
             * نگه می‌داریم.
             *
             * PaymentController بعد از Verify موفق
             * از این اطلاعات برای ثبت CouponUsage
             * استفاده می‌کند.
             */
            if ($checkoutCoupon) {

                session([
                    'payment_coupon' => [
                        'order_id' =>
                            $order->id,

                        'coupon_id' =>
                            $checkoutCoupon['id'],
                    ],
                ]);
            }

            /*
             * Coupon مربوط به Checkout را پاک می‌کنیم.
             *
             * توجه:
             * Coupon هنوز مصرف نشده است.
             *
             * used_count و CouponUsage
             * فقط بعد از پرداخت موفق
             * در PaymentController ثبت می‌شوند.
             */
            session()->forget(
                'checkout_coupon'
            );

            /*
             * انتقال به پرداخت
             */
            return redirect()
                ->route(
                    'payment.start',
                    $order
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }


    /**
     * صفحه موفقیت سفارش
     */
    public function success(Order $order)
    {
        /*
         * بررسی مالکیت سفارش
         *
         * کاربر فقط می‌تواند صفحه موفقیت
         * سفارش خودش را مشاهده کند.
         */
        if (
            (int)$order->user_id !==
            (int)auth()->id()
        ) {
            abort(403);
        }

        /*
         * فقط سفارش پرداخت‌شده باید
         * صفحه موفقیت واقعی داشته باشد.
         */
        if ($order->payment_status !== 'paid') {

            return redirect()
                ->route(
                    'orders.show',
                    $order
                )
                ->with(
                    'error',
                    'این سفارش هنوز پرداخت نشده است.'
                );
        }

        /*
         * اطلاعات مورد نیاز صفحه موفقیت
         */
        $order->load([
            'items.product.images',
            'items.variant.size',
            'items.variant.color',
            'address',
            'invoice',
            'payments',
        ]);

        /*
         * آخرین پرداخت موفق
         */
        $payment = $order->payments()
            ->where(
                'status',
                'paid'
            )
            ->latest()
            ->first();

        return view(
            'checkout.success',
            compact(
                'order',
                'payment'
            )
        );
    }


}
