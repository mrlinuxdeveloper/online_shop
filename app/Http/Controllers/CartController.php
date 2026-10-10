<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * نمایش سبد خرید
     */
    public function index()
    {
        $cart = Cart::firstOrCreate([
            'user_id' => auth()->id(),
        ]);

        $cart->load([
            'items.product',
            'items.variant.size',
            'items.variant.color',
        ]);

        $total = $cart->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return view('cart.index', compact(
            'cart',
            'total'
        ));
    }


    /**
     * افزودن محصول به سبد
     */
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_variant_id' => [
                'required',
                'exists:product_variants,id',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);


        /*
         * پیدا کردن Variant
         */
        $variant = ProductVariant::with([
            'size',
            'color',
        ])->findOrFail(
            $validated['product_variant_id']
        );


        /*
         * اطمینان از تعلق Variant به Product
         */
        if ($variant->product_id !== $product->id) {

            abort(404);

        }


        /*
         * بررسی فعال بودن Variant
         */
        if (!$variant->status) {

            return back()->with(
                'error',
                'این تنوع محصول در دسترس نیست.'
            );

        }

        if (!$product->status) {
            return back()->with(
                'error',
                'این محصول در دسترس نیست.'
            );
        }


        /*
         * بررسی موجودی
         */
        if ($validated['quantity'] > $variant->stock) {

            return back()->with(
                'error',
                'موجودی کافی نیست.'
            );

        }


        /*
         * پیدا کردن یا ایجاد Cart
         */
        $cart = Cart::firstOrCreate([
            'user_id' => auth()->id(),
        ]);


        /*
         * بررسی اینکه این Variant
         * قبلاً در سبد وجود دارد یا نه
         */
        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_variant_id', $variant->id)
            ->first();


        if ($item) {

            /*
             * اگر قبلاً وجود دارد،
             * تعداد را افزایش بده
             */
            $newQuantity =
                $item->quantity +
                $validated['quantity'];


            /*
             * بررسی موجودی کل
             */
            if ($newQuantity > $variant->stock) {

                return back()->with(
                    'error',
                    'تعداد درخواستی از موجودی بیشتر است.'
                );

            }


            $item->update([
                'quantity' => $newQuantity,
                'price' => $product->salePrice($variant->price),
            ]);

        } else {

            /*
             * ایجاد آیتم جدید
             */
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'product_variant_id' => $variant->id,
                'quantity' => $validated['quantity'],
                'price' => $product->salePrice($variant->price),
            ]);

        }


        return redirect()
            ->route('cart.index')
            ->with(
                'success',
                'محصول با موفقیت به سبد خرید اضافه شد.'
            );
    }


    /**
     * تغییر تعداد محصول
     */
    public function update(
        Request $request,
        CartItem $cartItem
    ) {

        $validated = $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);


        /*
         * اطمینان از اینکه Cart
         * متعلق به کاربر فعلی است
         */
        $cart = Cart::where('id', $cartItem->cart_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();


        /*
         * پیدا کردن Variant
         */
        $variant = $cartItem->variant;


        if (!$variant) {

            return back()->with(
                'error',
                'تنوع محصول پیدا نشد.'
            );

        }

        if (!$variant->status || !$cartItem->product?->status) {
            return back()->with(
                'error',
                'این محصول دیگر در دسترس نیست.'
            );
        }


        /*
         * بررسی موجودی
         */
        if ($validated['quantity'] > $variant->stock) {

            return back()->with(
                'error',
                'تعداد درخواستی از موجودی بیشتر است.'
            );

        }


        /*
         * بروزرسانی
         */
        $cartItem->update([
            'quantity' => $validated['quantity'],
            'price' => $cartItem->product->salePrice($variant->price),
        ]);


        return back()->with(
            'success',
            'سبد خرید با موفقیت به‌روزرسانی شد.'
        );
    }


    /**
     * حذف آیتم از سبد
     */
    public function destroy(CartItem $cartItem)
    {
        /*
         * اطمینان از مالکیت آیتم
         */
        Cart::where('id', $cartItem->cart_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();


        $cartItem->delete();


        return back()->with(
            'success',
            'محصول از سبد خرید حذف شد.'
        );
    }
}
