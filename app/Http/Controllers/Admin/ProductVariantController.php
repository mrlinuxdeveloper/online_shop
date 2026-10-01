<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        $product->load([
            'variants.size',
            'variants.color',
        ]);

        return view(
            'admin.products.variants.index',
            compact('product')
        );
    }

    public function create(Product $product)
    {
        $sizes = Size::orderBy('name')->get();
        $colors = Color::orderBy('name')->get();

        return view(
            'admin.products.variants.create',
            compact('product', 'sizes', 'colors')
        );
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'size_id' => [
                'nullable',
                'exists:sizes,id',
            ],

            'color_id' => [
                'nullable',
                'exists:colors,id',
            ],

            'sku' => [
                'required',
                'string',
                'max:255',
                'unique:product_variants,sku',
            ],

            'price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'weight' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);

        $validated['product_id'] = $product->id;
        $validated['status'] = $request->boolean('status');

        ProductVariant::create($validated);

        return redirect()
            ->route('admin.products.variants.index', $product)
            ->with('success', 'Product variant created successfully.');
    }

    public function edit(Product $product, ProductVariant $variant)
    {
        $this->checkVariant($product, $variant);

        $sizes = Size::orderBy('name')->get();
        $colors = Color::orderBy('name')->get();

        return view(
            'admin.products.variants.edit',
            compact(
                'product',
                'variant',
                'sizes',
                'colors'
            )
        );
    }

    public function update(
        Request $request,
        Product $product,
        ProductVariant $variant
    ) {
        $this->checkVariant($product, $variant);

        $validated = $request->validate([
            'size_id' => [
                'nullable',
                'exists:sizes,id',
            ],

            'color_id' => [
                'nullable',
                'exists:colors,id',
            ],

            'sku' => [
                'required',
                'string',
                'max:255',
                Rule::unique('product_variants', 'sku')
                    ->ignore($variant->id),
            ],

            'price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'weight' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);

        $validated['status'] = $request->boolean('status');

        $variant->update($validated);

        return redirect()
            ->route('admin.products.variants.index', $product)
            ->with('success', 'Product variant updated successfully.');
    }

    public function destroy(
        Product $product,
        ProductVariant $variant
    ) {
        $this->checkVariant($product, $variant);

        $variant->delete();

        return redirect()
            ->route('admin.products.variants.index', $product)
            ->with('success', 'Product variant deleted successfully.');
    }

    private function checkVariant(
        Product $product,
        ProductVariant $variant
    ) {
        if ($variant->product_id !== $product->id) {
            abort(404);
        }
    }
}
