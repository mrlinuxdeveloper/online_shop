@extends('layouts.app')

@section('title', request('search') ? 'جستجوی «' . request('search') . '» | ' . config('app.name', 'فروشگاه آنلاین') : 'خرید آنلاین پوشاک و محصولات | ' . config('app.name', 'فروشگاه آنلاین'))
@section('meta_description', request('search') ? 'نتایج جستجوی ' . request('search') . ' در فروشگاه ' . config('app.name', 'فروشگاه آنلاین') . '. محصولات را بررسی و آنلاین خرید کنید.' : 'مشاهده و خرید آنلاین جدیدترین محصولات و پوشاک با قیمت مناسب و ارسال به سراسر ایران از ' . config('app.name', 'فروشگاه آنلاین'))
@section('canonical_url', route('shop.products.index'))
@if(request()->hasAny(['search', 'category', 'brand', 'sort']))@section('robots', 'noindex, follow')@endif

@section('content')

    <style>
        .shop-page {
            background: #fff;
            min-height: 100vh;
        }

        .shop-intro {
            background: #fff;
            border-bottom: 1px solid #e9e9e9;
            padding: 24px 0;
            margin-bottom: 24px;
        }

        .shop-title {
            font-size: 34px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .shop-description {
            color: #777;
            margin: 0;
        }

        .filter-box {
            background: white;
            border: 1px solid #e9e9e9;
            border-radius: 3px;
            padding: 22px;
            position: sticky;
            top: 15px;
        }

        .filter-title {
            font-size: 19px;
            font-weight: 700;
            margin-bottom: 18px;
        }

        .filter-label {
            font-weight: 600;
            margin-bottom: 7px;
        }

        .product-card {
            border: 1px solid #eee;
            border-radius: 2px;
            overflow: hidden;
            background: white;
            transition: 0.25s;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-3px);
            border-color: #bbb;
        }

        .product-image {
            aspect-ratio: 3 / 4;
            background: #f5f5f5;
            overflow: hidden;
            position: relative;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.3s;
        }

        .product-card:hover .product-image img {
            transform: scale(1.04);
        }

        .discount-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: var(--shop-accent, #d7193f);
            color: white;
            padding: 5px 10px;
            border-radius: 2px;
            font-size: 13px;
        }

        .product-info {
            padding: 18px;
            min-height: 158px;
            display: flex;
            flex-direction: column;
        }

        .product-category {
            font-size: 13px;
            color: #888;
            height: 19px;
            overflow: hidden;
        }

        .product-name {
            font-size: 17px;
            font-weight: 700;
            line-height: 1.6;
            height: 54px;
            margin: 4px 0;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            overflow: hidden;
        }

        .product-price {
            font-weight: 700;
            color: var(--shop-primary, #75172c);
            font-size: 18px;
            margin-top: auto;
        }

        .old-price {
            color: #999;
            text-decoration: line-through;
            font-size: 14px;
            margin-right: 8px;
            display: inline-block;
        }

        .empty-products {
            background: white;
            border-radius: 16px;
            padding: 60px 20px;
            text-align: center;
            color: #777;
        }

        @media (max-width: 575px) {
            .product-info { min-height: 145px; padding: 12px; }
            .product-name { height: 46px; font-size: 14px; }
            .product-price { font-size: 15px; }
            .old-price { font-size: 11px; }
        }
    </style>


    <div class="shop-page">

        <div class="container py-4">

            {{-- Header --}}
            <div class="shop-intro">

                <h1 class="shop-title">
                    فروشگاه
                </h1>

                <p class="shop-description">
                    محصولات مورد نظر خود را پیدا کنید.
                </p>

            </div>


            <div class="row g-4">

                {{-- فیلترها --}}
                <div class="col-lg-3">

                    <div class="filter-box">

                        <div class="filter-title">
                            فیلتر محصولات
                        </div>

                        <form
                            action="{{ route('shop.products.index') }}"
                            method="GET"
                        >

                            {{-- جستجو --}}
                            <div class="mb-4">

                                <label class="filter-label">
                                    جستجو
                                </label>

                                <input
                                    type="text"
                                    name="search"
                                    class="form-control"
                                    value="{{ request('search') }}"
                                    placeholder="نام یا کد محصول..."
                                >

                            </div>


                            {{-- دسته‌بندی --}}
                            <div class="mb-4">

                                <label class="filter-label">
                                    دسته‌بندی
                                </label>

                                <select
                                    name="category"
                                    class="form-select"
                                >

                                    <option value="">
                                        همه دسته‌بندی‌ها
                                    </option>

                                    @foreach($categories as $category)

                                        <option
                                            value="{{ $category->id }}"
                                            @selected(request('category') == $category->id)
                                        >
                                            {{ $category->name }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- برند --}}
                            <div class="mb-4">

                                <label class="filter-label">
                                    برند
                                </label>

                                <select
                                    name="brand"
                                    class="form-select"
                                >

                                    <option value="">
                                        همه برندها
                                    </option>

                                    @foreach($brands as $brand)

                                        <option
                                            value="{{ $brand->id }}"
                                            @selected(request('brand') == $brand->id)
                                        >
                                            {{ $brand->name }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Sort --}}
                            <div class="mb-4">

                                <label class="filter-label">
                                    مرتب‌سازی
                                </label>

                                <select
                                    name="sort"
                                    class="form-select"
                                >

                                    <option value="">
                                        جدیدترین
                                    </option>

                                    <option
                                        value="price_low"
                                        @selected(request('sort') === 'price_low')
                                    >
                                        ارزان‌ترین
                                    </option>

                                    <option
                                        value="price_high"
                                        @selected(request('sort') === 'price_high')
                                    >
                                        گران‌ترین
                                    </option>

                                    <option
                                        value="oldest"
                                        @selected(request('sort') === 'oldest')
                                    >
                                        قدیمی‌ترین
                                    </option>

                                </select>

                            </div>


                            <div class="d-grid gap-2">

                                <button
                                    type="submit"
                                    class="btn btn-dark"
                                >
                                    اعمال فیلتر
                                </button>

                                <a
                                    href="{{ route('shop.products.index') }}"
                                    class="btn btn-outline-secondary"
                                >
                                    حذف فیلترها
                                </a>

                            </div>

                        </form>

                    </div>

                </div>


                {{-- محصولات --}}
                <div class="col-lg-9">

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div class="text-muted">

                            {{ $products->total() }}
                            محصول

                        </div>

                    </div>


                    @if($products->count())

                        <div class="row g-4">

                            @foreach($products as $product)

                                <div class="col-6 col-md-6 col-xl-4">

                                    <a
                                        href="{{ route('shop.products.show', $product) }}"
                                        class="text-decoration-none text-dark"
                                    >

                                        <div class="product-card">

                                            <div class="product-image">

                                                @php
                                                    $primaryImage = $product->images
                                                        ->where('is_primary', true)
                                                        ->first()
                                                        ?? $product->images->first();
                                                @endphp


                                                @if($primaryImage)

                                                    <img
                                                        src="{{ asset('storage/' . $primaryImage->image) }}"
                                                        alt="{{ $product->name }}"
                                                    >

                                                @else

                                                    <div class="d-flex align-items-center justify-content-center h-100">

                                                    <span class="text-muted">
                                                        بدون تصویر
                                                    </span>

                                                    </div>

                                                @endif


                                                @if($product->discount > 0)

                                                    <span class="discount-badge">

                                                    {{ number_format($product->discount, 0) }}%

                                                </span>

                                                @endif

                                            </div>


                                            <div class="product-info">

                                                @if($product->category)

                                                    <div class="product-category">
                                                        {{ $product->category->name }}
                                                    </div>

                                                @else

                                                    <div class="product-category" aria-hidden="true"></div>

                                                @endif


                                                <div class="product-name">
                                                    {{ $product->name }}
                                                </div>


                                                @if($product->discount > 0)

                                                    @php
                                                        $finalPrice = $product->price -
                                                            ($product->price * $product->discount / 100);
                                                    @endphp

                                                    <span class="product-price">
                                                    {{ number_format($finalPrice) }}
                                                    تومان
                                                </span>

                                                    <span class="old-price">
                                                    {{ number_format($product->price) }}
                                                    تومان
                                                </span>

                                                @else

                                                    <span class="product-price">
                                                    {{ number_format($product->price) }}
                                                    تومان
                                                </span>

                                                @endif

                                            </div>

                                        </div>

                                    </a>

                                </div>

                            @endforeach

                        </div>


                        {{-- Pagination --}}
                        <div class="mt-5 d-flex justify-content-center">

                            {{ $products->links() }}

                        </div>

                    @else

                        <div class="empty-products">

                            <div class="fs-1 mb-3">
                                🛍️
                            </div>

                            <h4>
                                محصولی پیدا نشد
                            </h4>

                            <p class="mb-4">
                                با تغییر فیلترها یا عبارت جستجو دوباره امتحان کنید.
                            </p>

                            <a
                                href="{{ route('shop.products.index') }}"
                                class="btn btn-dark"
                            >
                                مشاهده همه محصولات
                            </a>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

@endsection
