@extends('layouts.app')

@section('content')
    <style>
        .wishlist-page {
            min-height: 75vh;
            background: #f8f6f1;
            padding: 45px 0 70px;
        }
        .wishlist-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
        }
        .wishlist-title {
            color: #25352d;
            font-size: 27px;
            font-weight: 900;
            margin: 0 0 7px;
        }
        .wishlist-count {
            color: #888;
            font-size: 13px;
        }
        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 22px;
        }
        .wishlist-card {
            background: #fff;
            border: 1px solid #e8e3da;
            border-radius: 15px;
            overflow: hidden;
            transition: .2s ease;
        }
        .wishlist-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(0,0,0,.07);
        }
        .wishlist-image-wrapper {
            position: relative;
            height: 280px;
            background: #f1eee8;
            overflow: hidden;
        }
        .wishlist-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform .3s ease;
        }
        .wishlist-card:hover .wishlist-image {
            transform: scale(1.03);
        }
        .wishlist-no-image {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 13px;
        }
        .wishlist-heart {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,.92);
            border-radius: 50%;
            color: #b24b3c;
            font-size: 17px;
            box-shadow: 0 3px 12px rgba(0,0,0,.08);
        }
        .wishlist-content {
            padding: 17px;
        }
        .wishlist-product-name {
            color: #25352d;
            font-size: 16px;
            font-weight: 800;
            margin-bottom: 7px;
            line-height: 1.7;
        }
        .wishlist-product-category {
            color: #999;
            font-size: 12px;
            margin-bottom: 12px;
        }
        .wishlist-price {
            color: #6f5138;
            font-size: 16px;
            font-weight: 900;
            margin-bottom: 16px;
        }
        .wishlist-actions {
            display: flex;
            gap: 8px;
        }
        .wishlist-view {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            border: 1px solid #25352d;
            border-radius: 8px;
            color: #25352d;
            text-decoration: none;
            font-size: 12px;
            font-weight: 800;
            transition: .2s;
        }
        .wishlist-view:hover {
            background: #25352d;
            color: #fff;
        }
        .wishlist-delete {
            min-width: 70px;
            border: 1px solid #c56a5e;
            background: #fff;
            color: #b24b3c;
            border-radius: 8px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 800;
            transition: .2s;
        }
        .wishlist-delete:hover {
            background: #b24b3c;
            border-color: #b24b3c;
            color: #fff;
        }
        .wishlist-empty {
            background: #fff;
            border: 1px dashed #d8d1c6;
            border-radius: 17px;
            padding: 80px 20px;
            text-align: center;
        }
        .wishlist-empty-icon {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            background: #f3eee8;
            border-radius: 50%;
            color: #9a7a5d;
            font-size: 30px;
        }
        .wishlist-empty h2 {
            color: #25352d;
            font-size: 22px;
            font-weight: 900;
            margin-bottom: 10px;
        }
        .wishlist-empty p {
            color: #888;
            font-size: 13px;
            margin-bottom: 22px;
        }
        .wishlist-shop {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 11px 22px;
            background: #25352d;
            color: #fff;
            text-decoration: none;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 800;
            transition: .2s;
        }
        .wishlist-shop:hover {
            background: #6f5138;
            color: #fff;
        }
        @media (max-width: 1100px) {
            .wishlist-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        @media (max-width: 768px) {
            .wishlist-page {
                padding: 30px 15px 55px;
            }
            .wishlist-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
            .wishlist-image-wrapper {
                height: 230px;
            }
            .wishlist-content {
                padding: 13px;
            }
            .wishlist-actions {
                flex-direction: column;
            }
            .wishlist-delete {
                min-height: 38px;
            }
        }
        @media (max-width: 480px) {
            .wishlist-header {
                margin-bottom: 20px;
            }
            .wishlist-title {
                font-size: 22px;
            }
            .wishlist-grid {
                grid-template-columns: 1fr;
            }
            .wishlist-image-wrapper {
                height: 300px;
            }
            .wishlist-actions {
                flex-direction: row;
            }
            .wishlist-delete {
                min-height: 40px;
            }
        }
    </style>

    <div class="wishlist-page">
        <div class="container">

            <div class="wishlist-header">
                <div>
                    <h1 class="wishlist-title">
                        ❤️ علاقه‌مندی‌ها
                    </h1>

                    <div class="wishlist-count">
                        {{ $wishlist->items->count() }} محصول
                    </div>
                </div>
            </div>

            @if($wishlist->items->isEmpty())

                <div class="wishlist-empty">
                    <div class="wishlist-empty-icon">
                        ♡
                    </div>

                    <h2>
                        لیست علاقه‌مندی‌ها خالی است
                    </h2>

                    <p>
                        محصولاتی که دوست داری را به این لیست اضافه کن.
                    </p>

                    <a href="{{ url('/') }}" class="wishlist-shop">
                        مشاهده محصولات
                    </a>
                </div>

            @else

                <div class="wishlist-grid">
                    @foreach($wishlist->items as $item)

                        @php
                            $product = $item->product;
                            $image = $product->images
                                ->firstWhere('is_primary', true)
                                ?? $product->images->first();
                        @endphp

                        <div class="wishlist-card">

                            <div class="wishlist-image-wrapper">
                                @if($image)
                                    <img
                                        src="{{ asset('storage/' . $image->image) }}"
                                        alt="{{ $product->name }}"
                                        class="wishlist-image"
                                    >
                                @else
                                    <div class="wishlist-no-image">
                                        بدون تصویر
                                    </div>
                                @endif

                                <div class="wishlist-heart">
                                    ♥
                                </div>
                            </div>

                            <div class="wishlist-content">

                                <div class="wishlist-product-name">
                                    {{ $product->name }}
                                </div>

                                @if($product->category)
                                    <div class="wishlist-product-category">
                                        {{ $product->category->name }}
                                    </div>
                                @endif

                                <div class="wishlist-price">
                                    {{ number_format($product->price) }}
                                    تومان
                                </div>

                                <div class="wishlist-actions">

                                    <a
                                        href="{{ route('shop.products.show', $product->slug) }}"
                                        class="wishlist-view"
                                    >
                                        مشاهده محصول
                                    </a>

                                    <form
                                        action="{{ route('wishlist.destroy', $product->id) }}"
                                        method="POST"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="wishlist-delete"
                                        >
                                            حذف
                                        </button>
                                    </form>

                                </div>

                            </div>
                        </div>

                    @endforeach
                </div>

            @endif

        </div>
    </div>
@endsection
