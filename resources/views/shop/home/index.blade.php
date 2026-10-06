@extends('layouts.app')

@section('title', config('app.name', 'Companymod'))

@section('content')

    <style>
        .home-hero {
            background: linear-gradient(135deg, #2E5E4E 0%, #23483d 100%);
            color: #fff;
            border-radius: 0 0 28px 28px;
            overflow: hidden;
        }

        .hero-content {
            padding: 80px 0;
        }

        .hero-title {
            font-size: 48px;
            line-height: 1.3;
            font-weight: 800;
            margin-bottom: 20px;
        }

        .hero-text {
            color: rgba(255,255,255,.8);
            font-size: 17px;
            line-height: 2;
            max-width: 520px;
        }

        .hero-button {
            display: inline-block;
            background: #fff;
            color: #2E5E4E;
            padding: 13px 28px;
            border-radius: 10px;
            font-weight: 700;
            margin-top: 20px;
        }

        .hero-button:hover {
            background: #FAF7F1;
            color: #B24C3C;
        }

        .hero-box {
            min-height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 120px;
            opacity: .9;
        }

        .section {
            padding: 65px 0 0;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .section-title {
            font-size: 28px;
            font-weight: 800;
            margin: 0;
        }

        .section-link {
            color: #2E5E4E;
            font-weight: 700;
        }

        .section-link:hover {
            color: #B24C3C;
        }

        .category-card {
            display: block;
            background: #fff;
            border: 1px solid #e8e3da;
            border-radius: 16px;
            padding: 28px 15px;
            text-align: center;
            transition: .2s;
            height: 100%;
        }

        .category-card:hover {
            transform: translateY(-5px);
            border-color: #2E5E4E;
            box-shadow: 0 10px 30px rgba(0,0,0,.07);
        }

        .category-icon {
            width: 65px;
            height: 65px;
            margin: 0 auto 15px;
            border-radius: 50%;
            background: #eef4f1;
            color: #2E5E4E;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 27px;
        }

        .category-name {
            font-weight: 700;
            margin-bottom: 6px;
        }

        .category-count {
            color: #888;
            font-size: 13px;
        }

        .product-card {
            background: #fff;
            border: 1px solid #e8e3da;
            border-radius: 16px;
            overflow: hidden;
            height: 100%;
            transition: .2s;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,.08);
        }

        .product-image {
            position: relative;
            background: #f4f1eb;
            aspect-ratio: 1 / 1;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: .3s;
        }

        .product-card:hover .product-image img {
            transform: scale(1.04);
        }

        .product-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #aaa;
            font-size: 45px;
        }

        .product-discount {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #B24C3C;
            color: #fff;
            border-radius: 7px;
            padding: 5px 9px;
            font-size: 12px;
            font-weight: 700;
        }

        .product-body {
            padding: 17px;
        }

        .product-category {
            color: #888;
            font-size: 12px;
            margin-bottom: 7px;
        }

        .product-name {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 12px;
            min-height: 42px;
        }

        .product-name a:hover {
            color: #2E5E4E;
        }

        .product-price {
            color: #2E5E4E;
            font-size: 17px;
            font-weight: 800;
        }

        .product-old-price {
            color: #999;
            text-decoration: line-through;
            font-size: 12px;
            margin-right: 7px;
        }

        .brand-card {
            display: block;
            background: #fff;
            border: 1px solid #e8e3da;
            border-radius: 14px;
            padding: 25px 15px;
            text-align: center;
            height: 100%;
            transition: .2s;
        }

        .brand-card:hover {
            border-color: #2E5E4E;
            transform: translateY(-4px);
        }

        .brand-logo {
            width: 70px;
            height: 70px;
            margin: 0 auto 12px;
            border-radius: 50%;
            background: #f4f1eb;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            color: #2E5E4E;
            font-weight: 800;
            font-size: 22px;
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .brand-name {
            font-weight: 700;
        }

        .brand-count {
            color: #888;
            font-size: 12px;
            margin-top: 5px;
        }

        @media (max-width: 767px) {
            .hero-content {
                padding: 50px 20px;
                text-align: center;
            }

            .hero-title {
                font-size: 34px;
            }

            .hero-box {
                min-height: 180px;
                font-size: 80px;
            }

            .section {
                padding-top: 45px;
            }

            .section-title {
                font-size: 23px;
            }
        }
    </style>

    {{-- Hero --}}
    <section class="home-hero">
        <div class="container">
            <div class="row align-items-center hero-content">
                <div class="col-lg-7">
                    <h1 class="hero-title">
                        استایل خودت را<br>
                        متفاوت انتخاب کن
                    </h1>

                    <p class="hero-text">
                        جدیدترین محصولات پوشاک را با کیفیت مناسب،
                        تنوع بالا و امکان خرید آنلاین مشاهده کنید.
                    </p>

                    <a href="{{ route('shop.products.index') }}" class="hero-button">
                        مشاهده فروشگاه
                    </a>
                </div>

                <div class="col-lg-5">
                    <div class="hero-box">
                        👕
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Categories --}}
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">دسته‌بندی‌ها</h2>

                <a href="{{ route('shop.products.index') }}" class="section-link">
                    مشاهده همه
                </a>
            </div>

            <div class="row g-3">
                @forelse($categories as $category)
                    <div class="col-6 col-md-4 col-lg-3">
                        <a
                            href="{{ route('shop.products.index', ['category' => $category->id]) }}"
                            class="category-card"
                        >
                            <div class="category-icon">
                                👕
                            </div>

                            <div class="category-name">
                                {{ $category->name }}
                            </div>

                            <div class="category-count">
                                {{ $category->products_count }} محصول
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light text-center">
                            هنوز دسته‌بندی فعالی ثبت نشده است.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Featured Products --}}
    @if($featuredProducts->isNotEmpty())
        <section class="section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">محصولات ویژه</h2>

                    <a href="{{ route('shop.products.index') }}" class="section-link">
                        مشاهده همه محصولات
                    </a>
                </div>

                <div class="row g-4">
                    @foreach($featuredProducts as $product)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="product-card">

                                <a
                                    href="{{ route('shop.products.show', $product) }}"
                                    class="product-image"
                                >
                                    @if($product->images->first())
                                        <img
                                            src="{{ asset('images/products/' . $product->images->first()->image) }}"
                                            alt="{{ $product->name }}"
                                        >
                                    @else
                                        <div class="product-placeholder">
                                            👕
                                        </div>
                                    @endif

                                    @if($product->discount > 0)
                                        <span class="product-discount">
                                    {{ number_format($product->discount) }}٪ تخفیف
                                </span>
                                    @endif
                                </a>

                                <div class="product-body">
                                    @if($product->category)
                                        <div class="product-category">
                                            {{ $product->category->name }}
                                        </div>
                                    @endif

                                    <div class="product-name">
                                        <a href="{{ route('shop.products.show', $product) }}">
                                            {{ $product->name }}
                                        </a>
                                    </div>

                                    <div>
                                <span class="product-price">
                                    {{ number_format($product->price) }}
                                    تومان
                                </span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Latest Products --}}
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">جدیدترین محصولات</h2>

                <a href="{{ route('shop.products.index') }}" class="section-link">
                    مشاهده همه
                </a>
            </div>

            <div class="row g-4">
                @forelse($latestProducts as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="product-card">

                            <a
                                href="{{ route('shop.products.show', $product) }}"
                                class="product-image"
                            >
                                @if($product->images->first())
                                    <img
                                        src="{{ asset('images/products/' . $product->images->first()->image) }}"
                                        alt="{{ $product->name }}"
                                    >
                                @else
                                    <div class="product-placeholder">
                                        👕
                                    </div>
                                @endif

                                @if($product->discount > 0)
                                    <span class="product-discount">
                                    {{ number_format($product->discount) }}٪ تخفیف
                                </span>
                                @endif
                            </a>

                            <div class="product-body">
                                @if($product->category)
                                    <div class="product-category">
                                        {{ $product->category->name }}
                                    </div>
                                @endif

                                <div class="product-name">
                                    <a href="{{ route('shop.products.show', $product) }}">
                                        {{ $product->name }}
                                    </a>
                                </div>

                                <div>
                                <span class="product-price">
                                    {{ number_format($product->price) }}
                                    تومان
                                </span>
                                </div>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light text-center">
                            هنوز محصولی ثبت نشده است.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Brands --}}
    @if($brands->isNotEmpty())
        <section class="section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">برندها</h2>

                    <a href="{{ route('shop.products.index') }}" class="section-link">
                        مشاهده فروشگاه
                    </a>
                </div>

                <div class="row g-3">
                    @foreach($brands as $brand)
                        <div class="col-6 col-md-3 col-lg-2">
                            <a href="{{ route('shop.products.index', ['brand' => $brand->id]) }}" class="brand-card">

                                <div class="brand-logo">
                                    @if($brand->logo)
                                        <img
                                            src="{{ asset('images/brands/' . $brand->logo) }}"
                                            alt="{{ $brand->name }}"
                                        >
                                    @else
                                        {{ mb_substr($brand->name, 0, 1) }}
                                    @endif
                                </div>

                                <div class="brand-name">
                                    {{ $brand->name }}
                                </div>

                                <div class="brand-count">
                                    {{ $brand->products_count }} محصول
                                </div>

                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
