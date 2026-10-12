<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;

class SitemapController extends Controller
{
    public function __invoke()
    {
        $products = Product::query()
            ->where('status', true)
            ->select(['id', 'updated_at'])
            ->orderBy('id')
            ->get();

        return response()
            ->view('seo.sitemap', compact('products'))
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
