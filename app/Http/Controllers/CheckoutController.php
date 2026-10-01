<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * نمایش صفحه Checkout
     */
    public function index()
    {
        $cart = Cart::where('user_id', auth()->id())
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


        $subtotal = $cart->items->sum(function ($item) {

            return $item->price * $item->quantity;

        });


        $discount = 0;

        $shippingCost = 0;

        $tax = 0;


        $total =
            $subtotal
            - $discount
            + $shippingCost
            + $tax;


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
     * ایجاد Order
     */
    public function store(Request $request)
    {
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
             *
             * این بررسی مهم است چون ممکن است
             * بعد از اضافه شدن محصول به Cart،
             * موجودی تغییر کرده باشد.
             */
            foreach ($cart->items as $cartItem) {

                $variant = $cartItem->variant;

                $product = $cartItem->product;


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


                if ($variant->stock < $cartItem->quantity) {

                    throw new \RuntimeException(
                        "Not enough stock available for {$product->name}."
                    );
                }

            }


            /*
             * محاسبه قیمت در سمت سرور
             */
            $subtotal = $cart->items->sum(function ($item) {

                return $item->price * $item->quantity;

            });


            $discount = 0;

            $shippingCost = 0;

            $tax = 0;


            $total =
                $subtotal
                - $discount
                + $shippingCost
                + $tax;


            /*
             * ایجاد Order
             */
            $order = Order::create([
                'user_id' => auth()->id(),

                'address_id' => $address->id,

                'order_number' =>
                    'ORD-' . strtoupper(
                        Str::random(10)
                    ),

                'subtotal' => $subtotal,

                'discount' => $discount,

                'shipping_cost' => $shippingCost,

                'tax' => $tax,

                'total_price' => $total,

                'status' => 'pending',

                'payment_status' => 'pending',

                'note' =>
                    $validated['note'] ?? null,
            ]);


            /*
             * ایجاد Order Items
             */
            foreach ($cart->items as $cartItem) {

                $variant = $cartItem->variant;

                $product = $cartItem->product;


                $productImage = $product->images
                    ->firstWhere(
                        'is_primary',
                        true
                    );


                /*
                 * اگر Primary Image وجود نداشت،
                 * اولین تصویر را بردار
                 */
                if (!$productImage) {

                    $productImage =
                        $product->images->first();

                }


                OrderItem::create([
                    'order_id' => $order->id,

                    'product_id' => $product->id,

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

                    'discount' => 0,

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
                'order_id' => $order->id,
                'invoice_number' => 'INV-' . strtoupper(Str::random(10)),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'shipping_cost' => $shippingCost,
                'tax' => $tax,
                'total_price' => $total,
                'status' => 'issued',
                'issued_at' => now(),
            ]);

//            Invoice::create([
//                'order_id' => $order->id,
//
//                'invoice_number' =>
//                    'INV-' . strtoupper(
//                        Str::random(10)
//                    ),
//
//                'subtotal' =>
//                    $subtotal,
//
//                'discount' =>
//                    $discount,
//
//                'shipping_cost' =>
//                    $shippingCost,
//
//                'tax' =>
//                    $tax,
//
//                'total_price' =>
//                    $total,
//
//                'status' =>
//                    'pending',
//
//                'issued_at' =>
//                    now(),
//            ]);


            DB::commit();


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
         * فقط صاحب سفارش
         */
        if (
            $order->user_id !==
            auth()->id()
        ) {

            abort(403);

        }


        $order->load([
            'items',
            'address',
            'invoice',
        ]);


        return view(
            'checkout.success',
            compact('order')
        );
    }
}
