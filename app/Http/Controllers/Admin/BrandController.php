<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::latest()
            ->paginate(10);

        return view(
            'admin.brands.index',
            compact('brands')
        );
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:brands,slug',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'status' => [
                'required',
                'boolean',
            ],

            ]);
        } catch (ValidationException $exception) {
            Log::warning('Brand creation validation failed.', [
                'errors' => $exception->errors(),
            ]);

            return back()
                ->withInput()
                ->withErrors($exception->errors())
                ->with('error', 'برند ذخیره نشد: ' . collect($exception->errors())->flatten()->first());
        }

        /*
        |--------------------------------------------------------------------------
        | Generate slug
        |--------------------------------------------------------------------------
        */

        if (empty($validated['slug'])) {

            $validated['slug'] = Str::slug(
                $validated['name']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Upload image
        |--------------------------------------------------------------------------
        */

        $logoPath = null;
        if ($request->hasFile('image')) {
            $logoPath = $request
                ->file('image')
                ->store('brands', 'public');

            $validated['logo'] = $logoPath;
        }
        unset($validated['image']);

        /*
        |--------------------------------------------------------------------------
        | Create brand
        |--------------------------------------------------------------------------
        */

        try {
            Brand::create($validated);
        } catch (Throwable $exception) {
            if ($logoPath) {
                Storage::disk('public')->delete($logoPath);
            }

            Log::error('Brand creation failed.', [
                'brand_name' => $validated['name'] ?? null,
                'exception' => $exception,
            ]);

            return back()
                ->withInput()
                ->with('error', 'خطای ذخیره برند: ' . $exception::class . ' — ' . $exception->getMessage());
        }

        return redirect()
            ->route('admin.brands.index')
            ->with(
                'success',
                'برند با موفقیت ایجاد شد.'
            );
    }

    public function show(Brand $brand)
    {
        return redirect()
            ->route('admin.brands.index');
    }

    public function edit(Brand $brand)
    {
        return view(
            'admin.brands.edit',
            compact('brand')
        );
    }

    public function update(
        Request $request,
        Brand $brand
    ) {

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',

                Rule::unique('brands', 'slug')
                    ->ignore($brand->id),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'status' => [
                'required',
                'boolean',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Generate slug
        |--------------------------------------------------------------------------
        */

        if (empty($validated['slug'])) {

            $validated['slug'] = Str::slug(
                $validated['name']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Replace image
        |--------------------------------------------------------------------------
        */

        $previousLogo = $brand->logo;
        if ($request->hasFile('image')) {
            $validated['logo'] = $request
                ->file('image')
                ->store('brands', 'public');
        }
        unset($validated['image']);

        /*
        |--------------------------------------------------------------------------
        | Update brand
        |--------------------------------------------------------------------------
        */

        $brand->update($validated);

        if ($request->hasFile('image') && $previousLogo) {
            Storage::disk('public')->delete($previousLogo);
        }

        return redirect()
            ->route('admin.brands.index')
            ->with(
                'success',
                'برند با موفقیت به‌روزرسانی شد.'
            );
    }

    public function destroy(Brand $brand)
    {
        /*
        |--------------------------------------------------------------------------
        | Delete image
        |--------------------------------------------------------------------------
        */

        if ($brand->logo) {

            Storage::disk('public')
                ->delete($brand->logo);
        }

        /*
        |--------------------------------------------------------------------------
        | Delete brand
        |--------------------------------------------------------------------------
        */

        $brand->delete();

        return redirect()
            ->route('admin.brands.index')
            ->with(
                'success',
                'برند با موفقیت حذف شد.'
            );
    }
}
