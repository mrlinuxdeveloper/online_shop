<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->where('status', true)
            ->with([
                'category',
                'brand',
                'images',
                'variants.size',
                'variants.color',
            ]);

        // Search
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('product_code', 'like', "%{$search}%");

            });
        }

        // Category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Brand
        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        // Sorting
        switch ($request->get('sort')) {

            case 'price_low':
                $query->orderBy('price', 'asc');
                break;

            case 'price_high':
                $query->orderBy('price', 'desc');
                break;

            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;

            default:
                $query->latest();
                break;
        }

        $products = $query
            ->paginate(12)
            ->withQueryString();

        $categories = Category::query()
            ->where('status', true)
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        $brands = Brand::query()
            ->where('status', true)
            ->orderBy('name')
            ->get();

        return view(
            'shop.products.index',
            compact(
                'products',
                'categories',
                'brands'
            )
        );
    }

    public function show(Product $product)
    {
        $product->load([
            'category',
            'brand',
            'images',
            'variants.size',
            'variants.color',
        ]);

        $variants = $product->variants
            ->where('status', true)
            ->values();

        return view(
            'shop.products.show',
            compact('product', 'variants')
        );
    }
}
