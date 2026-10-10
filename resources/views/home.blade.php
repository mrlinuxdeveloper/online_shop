@extends('layouts.app')

@section('content')

    <style>
        .hero {
            min-height: 420px;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #f8f5f0, #e9eee9);
            border-radius: 20px;
            overflow: hidden;
        }

        .hero-content {
            padding: 60px;
        }

        .hero-title {
            font-size: 48px;
            font-weight: 800;
            line-height: 1.3;
        }

        .hero-text {
            color: #666;
            font-size: 18px;
            margin: 20px 0;
        }

        .category-card {
            height: 180px;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            background: #f4f4f4;
            transition: 0.3s;
        }

        .category-card:hover {
            transform: translateY(-5px);
        }

        .category-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .category-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: end;
            padding: 20px;
            background: linear-gradient(
                transparent,
                rgba(0, 0, 0, 0.65)
            );
        }

        .category-name {
            color: white;
            font-size: 20px;
            font-weight: 700;
        }

        .product-card {
            border: 0;
            border-radius: 16px;
            overflow: hidden;
            background: white;
            transition: 0.3s;
            height: 100%;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.06);
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            height: 280px;
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
            background: #b24c3c;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 13px;
        }

        .product-info {
            padding: 18px;
        }

        .product-category {
            font-size: 13px;
            color: #888;
        }

        .product-name {
            font-size: 17px;
            font-weight: 700;
            margin: 7px 0;
        }

        .product-price {
            font-weight: 700;
            color: #2e5e4e;
            font-size: 18px;
        }

        .old-price {
            color: #999;
            text-decoration: line-through;
            font-size: 14px;
            margin-left: 8px;
        }

        .section-title {
            font-size: 30px;
            font-weight: 800;
        }

        .section-description {
            color: #777;
        }
    </style>


    <div class="container py-4">

        {{-- Hero --}}
        <section class="hero mb-5">

            <div class="row w-100 align-items-center">

                <div class="col-lg-6">

                    <div class="hero-content">

                    <span class="text-muted">
                        فروشگاه آنلاین لباس
                    </span>

                        <h1 class="hero-title">
                            استایل خودت را پیدا کن
                        </h1>

                        <p class="hero-text">
                            جدیدترین محصولات پوشاک را با کیفیت بالا
                            و قیمت مناسب مشاهده کنید.
                        </p>

                        <a href="{{ url('/products') }}"
                           class="btn btn-dark px-4 py-2">
                            مشاهده محصولات
                        </a>

                    </div>

                </div>

                <div class="col-lg-6 d-none d-lg-block text-center">

                    <div style="font-size: 180px;">
                        👕
                    </div>

                </div>

            </div>

        </section>


        {{-- دسته‌بندی‌ها --}}
        <section class="mb-5">

            <div class="text-center mb-4">

                <h2 class="section-title">
                    دسته‌بندی‌ها
                </h2>

                <p class="section-description">
                    محصولات مورد نظر خود را بر اساس دسته‌بندی پیدا کنید.
                </p>

            </div>

            <div class="row g-4">

                @forelse($categories as $category)

                    <div class="col-6 col-md-4 col-lg-3">

                        <a href="#"
                           class="text-decoration-none">

                            <div class="category-card">

                                @if($category->image)

                                    <img
                                        src="{{ asset('storage/categories/' . $category->image) }}"
                                        alt="{{ $category->name }}"
                                    >

                                @else

                                    <div class="d-flex align-items-center justify-content-center h-100">
                                        <span class="fs-1">👗</span>
                                    </div>

                                @endif

                                <div class="category-overlay">

                                    <div class="category-name">
                                        {{ $category->name }}
                                    </div>

                                </div>

                            </div>

                        </a>

                    </div>

                @empty

                    <div class="col-12">

                        <div class="alert alert-light text-center">
                            هنوز دسته‌بندی‌ای ثبت نشده است.
                        </div>

                    </div>

                @endforelse

            </div>

        </section>


        {{-- محصولات جدید --}}
        <section class="mb-5">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h2 class="section-title mb-1">
                        جدیدترین محصولات
                    </h2>

                    <p class="section-description mb-0">
                        تازه‌ترین محصولات فروشگاه
                    </p>

                </div>

                <a href="{{ url('/products') }}"
                   class="btn btn-outline-dark">
                    مشاهده همه
                </a>

            </div>


            <div class="row g-4">

                @forelse($newProducts as $product)

                    @include('partials.product-card', [
                        'product' => $product
                    ])

                @empty

                    <div class="col-12">

                        <div class="alert alert-light text-center">
                            هنوز محصولی ثبت نشده است.
                        </div>

                    </div>

                @endforelse

            </div>

        </section>


        {{-- تخفیف محصولات --}}
        <section class="mb-5">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h2 class="section-title mb-1">
                        تخفیف‌های ویژه
                    </h2>

                    <p class="section-description mb-0">
                        محصولات دارای تخفیف را از دست ندهید.
                    </p>

                </div>

            </div>


            <div class="row g-4">

                @forelse($discountProducts as $product)

                    @include('partials.product-card', [
                        'product' => $product
                    ])

                @empty

                    <div class="col-12">

                        <div class="alert alert-light text-center">
                            در حال حاضر محصول تخفیف‌داری وجود ندارد.
                        </div>

                    </div>

                @endforelse

            </div>

        </section>


        {{-- محصولات ویژه --}}
        <section class="mb-5">

            <div class="text-center mb-4">

                <h2 class="section-title">
                    محصولات ویژه
                </h2>

                <p class="section-description">
                    محصولات منتخب فروشگاه
                </p>

            </div>


            <div class="row g-4">



                @forelse($featuredProducts as $product)

                    @include('partials.product-card', [
                        'product' => $product
                    ])

                @empty

                    <div class="col-12">

                        <div class="alert alert-light text-center">
                            هنوز محصول ویژه‌ای ثبت نشده است.
                        </div>

                    </div>

                @endforelse

            </div>

        </section>

    </div>

@endsection

