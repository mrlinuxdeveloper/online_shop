<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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

        if ($request->hasFile('image')) {

            $validated['image'] = $request
                ->file('image')
                ->store('brands', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Create brand
        |--------------------------------------------------------------------------
        */

        Brand::create($validated);

        return redirect()
            ->route('admin.brands.index')
            ->with(
                'success',
                'Brand created successfully.'
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

        if ($request->hasFile('image')) {

            if ($brand->image) {

                Storage::disk('public')
                    ->delete($brand->image);
            }

            $validated['image'] = $request
                ->file('image')
                ->store('brands', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Update brand
        |--------------------------------------------------------------------------
        */

        $brand->update($validated);

        return redirect()
            ->route('admin.brands.index')
            ->with(
                'success',
                'Brand updated successfully.'
            );
    }

    public function destroy(Brand $brand)
    {
        /*
        |--------------------------------------------------------------------------
        | Delete image
        |--------------------------------------------------------------------------
        */

        if ($brand->image) {

            Storage::disk('public')
                ->delete($brand->image);
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
                'Brand deleted successfully.'
            );
    }
}
