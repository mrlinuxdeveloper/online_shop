<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::query()
            ->where('status', true)
            ->whereNull('parent_id')
            ->withCount('products')
            ->orderBy('name')
            ->get();

        $featuredProducts = Product::query()
            ->where('status', true)
            ->where('featured', true)
            ->with([
                'category',
                'brand',
                'images',
                'variants.size',
                'variants.color',
            ])
            ->latest()
            ->take(8)
            ->get();

        $latestProducts = Product::query()
            ->where('status', true)
            ->with([
                'category',
                'brand',
                'images',
                'variants.size',
                'variants.color',
            ])
            ->latest()
            ->take(8)
            ->get();

        $brands = Brand::query()
            ->where('status', true)
            ->withCount('products')
            ->orderBy('name')
            ->take(8)
            ->get();

        return view('shop.home.index', compact(
            'categories',
            'featuredProducts',
            'latestProducts',
            'brands'
        ));
    }
}
