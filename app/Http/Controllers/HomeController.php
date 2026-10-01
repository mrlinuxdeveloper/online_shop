<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // دسته‌بندی‌های فعال و اصلی
        $categories = Category::query()
            ->where('status', true)
            ->whereNull('parent_id')
            ->with('children')
            ->latest()
            ->get();

        // جدیدترین محصولات
        $newProducts = Product::query()
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

        // محصولات دارای تخفیف
        $discountProducts = Product::query()
            ->where('status', true)
            ->where('discount', '>', 0)
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

        // محصولات ویژه
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

        return view('home', compact(
            'categories',
            'newProducts',
            'discountProducts',
            'featuredProducts'
        ));
    }
}

