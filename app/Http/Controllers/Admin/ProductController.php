<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with([
            'category',
            'brand',
        ])
            ->latest()
            ->paginate(10);

        return view(
            'admin.products.index',
            compact('products')
        );
    }

    public function create()
    {
        $categories = Category::where('status', true)
            ->orderBy('name')
            ->get();

        $brands = Brand::where('status', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.products.create',
            compact(
                'categories',
                'brands'
            )
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'category_id' => [
                'required',
                'exists:categories,id',
            ],

            'brand_id' => [
                'nullable',
                'exists:brands,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:products,slug',
            ],

            'product_code' => [
                'required',
                'string',
                'max:255',
                'unique:products,product_code',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'discount' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'status' => [
                'required',
                'boolean',
            ],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug(
                $validated['name']
            );
        }

        Product::create($validated);

        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'محصول با موفقیت ایجاد شد.'
            );
    }

    public function show(Product $product)
    {
        return redirect()
            ->route('admin.products.index');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('status', true)
            ->orderBy('name')
            ->get();

        $brands = Brand::where('status', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.products.edit',
            compact(
                'product',
                'categories',
                'brands'
            )
        );
    }

    public function update(
        Request $request,
        Product $product
    ) {
        $validated = $request->validate([

            'category_id' => [
                'required',
                'exists:categories,id',
            ],

            'brand_id' => [
                'nullable',
                'exists:brands,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('products', 'slug')
                    ->ignore($product->id),
            ],

            'product_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'product_code')
                    ->ignore($product->id),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'discount' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'status' => [
                'required',
                'boolean',
            ],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug(
                $validated['name']
            );
        }

        $product->update($validated);

        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'محصول با موفقیت به‌روزرسانی شد.'
            );
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'محصول با موفقیت حذف شد.'
            );
    }
    public function images(Product $product)
    {
        $product->load('images');

        return view(
            'admin.products.images',
            compact('product')
        );
    }
    public function storeImage(Request $request, Product $product)
    {
        $validated = $request->validate([
            'image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],

            'is_primary' => [
                'nullable',
                'boolean',
            ],
        ]);

        $path = $request
            ->file('image')
            ->store('products', 'public');

        $isPrimary = $request->boolean('is_primary');

        /*
        |--------------------------------------------------------------------------
        | اگر تصویر اصلی باشد، تصاویر قبلی اصلی نباشند
        |--------------------------------------------------------------------------
        */

        if ($isPrimary) {
            $product->images()
                ->update([
                    'is_primary' => false,
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | اگر اولین تصویر محصول باشد، خودکار primary شود
        |--------------------------------------------------------------------------
        */

        if ($product->images()->count() === 0) {
            $isPrimary = true;
        }

        ProductImage::create([
            'product_id' => $product->id,
            'image' => $path,
            'is_primary' => $isPrimary,
            'sort_order' => $product->images()->max('sort_order') + 1,
        ]);

        return redirect()
            ->route(
                'admin.products.images',
                $product
            )
            ->with(
                'success',
                'تصویر محصول با موفقیت بارگذاری شد.'
            );
    }
    public function destroyImage(
        Product $product,
        ProductImage $image
    ) {
        if ($image->product_id !== $product->id) {
            abort(404);
        }

        if ($image->image) {
            Storage::disk('public')
                ->delete($image->image);
        }

        $image->delete();

        return redirect()
            ->route(
                'admin.products.images',
                $product
            )
            ->with(
                'success',
                'تصویر محصول با موفقیت حذف شد.'
            );
    }
    public function setPrimaryImage(
        Product $product,
        ProductImage $image
    ) {
        if ($image->product_id !== $product->id) {
            abort(404);
        }

        $product->images()
            ->update([
                'is_primary' => false,
            ]);

        $image->update([
            'is_primary' => true,
        ]);

        return redirect()
            ->route(
                'admin.products.images',
                $product
            )
            ->with(
                'success',
                'تصویر اصلی با موفقیت به‌روزرسانی شد.'
            );
    }

}
