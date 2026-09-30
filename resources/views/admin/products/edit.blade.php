@extends('admin.layouts.AdminMasterLayout')

@section('title', 'Edit Product')

@section('page-title', 'Edit Product')

@section('content')

    <div class="container-fluid">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <form
                    action="{{ route('admin.products.update', $product) }}"
                    method="POST"
                >

                    @csrf

                    @method('PUT')


                    <div class="row">


                        {{-- Name --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Product Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $product->name) }}"
                                class="form-control @error('name') is-invalid @enderror"
                            >

                            @error('name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>


                        {{-- Product Code --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Product Code
                            </label>

                            <input
                                type="text"
                                name="product_code"
                                value="{{ old('product_code', $product->product_code) }}"
                                class="form-control @error('product_code') is-invalid @enderror"
                            >

                            @error('product_code')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>


                        {{-- Category --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Category
                            </label>

                            <select
                                name="category_id"
                                class="form-select @error('category_id') is-invalid @enderror"
                            >

                                <option value="">
                                    -- Select Category --
                                </option>

                                @foreach($categories as $category)

                                    <option
                                        value="{{ $category->id }}"
                                        @selected(
                                            old(
                                                'category_id',
                                                $product->category_id
                                            ) == $category->id
                                        )
                                    >
                                        {{ $category->name }}
                                    </option>

                                @endforeach

                            </select>

                            @error('category_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>


                        {{-- Brand --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Brand
                            </label>

                            <select
                                name="brand_id"
                                class="form-select @error('brand_id') is-invalid @enderror"
                            >

                                <option value="">
                                    -- Select Brand --
                                </option>

                                @foreach($brands as $brand)

                                    <option
                                        value="{{ $brand->id }}"
                                        @selected(
                                            old(
                                                'brand_id',
                                                $product->brand_id
                                            ) == $brand->id
                                        )
                                    >
                                        {{ $brand->name }}
                                    </option>

                                @endforeach

                            </select>

                            @error('brand_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>


                        {{-- Slug --}}

                        <div class="col-md-12 mb-3">

                            <label class="form-label">
                                Slug
                            </label>

                            <input
                                type="text"
                                name="slug"
                                value="{{ old('slug', $product->slug) }}"
                                class="form-control @error('slug') is-invalid @enderror"
                            >

                            @error('slug')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>


                        {{-- Price --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Price
                            </label>

                            <input
                                type="number"
                                name="price"
                                value="{{ old('price', $product->price) }}"
                                min="0"
                                step="1"
                                class="form-control @error('price') is-invalid @enderror"
                            >

                            @error('price')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>


                        {{-- Discount --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Discount (%)
                            </label>

                            <input
                                type="number"
                                name="discount"
                                value="{{ old('discount', $product->discount) }}"
                                min="0"
                                max="100"
                                step="0.01"
                                class="form-control @error('discount') is-invalid @enderror"
                            >

                            @error('discount')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>


                        {{-- Description --}}

                        <div class="col-md-12 mb-3">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea
                                name="description"
                                rows="6"
                                class="form-control @error('description') is-invalid @enderror"
                            >{{ old('description', $product->description) }}</textarea>

                            @error('description')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>


                        {{-- Status --}}

                        <div class="col-md-12 mb-4">

                            <label class="form-label">
                                Status
                            </label>

                            <select
                                name="status"
                                class="form-select"
                            >

                                <option
                                    value="1"
                                    @selected(
                                        old(
                                            'status',
                                            $product->status
                                        ) == 1
                                    )
                                >
                                    Active
                                </option>

                                <option
                                    value="0"
                                    @selected(
                                        old(
                                            'status',
                                            $product->status
                                        ) == 0
                                    )
                                >
                                    Inactive
                                </option>

                            </select>

                        </div>

                    </div>


                    <div class="d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Update Product
                        </button>

                        <a
                            href="{{ route('admin.products.index') }}"
                            class="btn btn-secondary"
                        >
                            Cancel
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection
