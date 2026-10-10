<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\WishlistItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * نمایش لیست علاقه‌مندی‌ها
     */
    public function index()
    {
        $wishlist = Wishlist::firstOrCreate([
            'user_id' => Auth::id(),
        ]);

        $wishlist->load([
            'items.product.images',
            'items.product.category',
            'items.product.brand',
        ]);

        return view('wishlist.index', compact('wishlist'));
    }


    /**
     * اضافه کردن محصول به Wishlist
     */
    public function store(Product $product)
    {
        abort_unless($product->status, 404);

        $wishlist = Wishlist::firstOrCreate([
            'user_id' => Auth::id(),
        ]);

        $exists = WishlistItem::where('wishlist_id', $wishlist->id)
            ->where('product_id', $product->id)
            ->exists();

        if ($exists) {
            return back()->with(
                'info',
                'این محصول قبلاً به علاقه‌مندی‌ها اضافه شده است.'
            );
        }

        WishlistItem::create([
            'wishlist_id' => $wishlist->id,
            'product_id' => $product->id,
        ]);

        return back()->with(
            'success',
            'محصول به علاقه‌مندی‌ها اضافه شد.'
        );
    }


    /**
     * حذف محصول از Wishlist
     */
    public function destroy(Product $product)
    {
        $wishlist = Wishlist::where(
            'user_id',
            Auth::id()
        )->first();

        if (!$wishlist) {
            return back();
        }

        WishlistItem::where('wishlist_id', $wishlist->id)
            ->where('product_id', $product->id)
            ->delete();

        return back()->with(
            'success',
            'محصول از علاقه‌مندی‌ها حذف شد.'
        );
    }
}
