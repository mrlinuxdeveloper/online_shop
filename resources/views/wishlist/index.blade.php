@extends('layouts.app')

@section('content')

    <style>
        .wishlist-page {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .wishlist-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .wishlist-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .wishlist-count {
            color: #777;
            font-size: 14px;
        }

        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
        }

        .wishlist-card {
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
            transition: 0.2s;
        }

        .wishlist-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        .wishlist-image {
            width: 100%;
            height: 260px;
            object-fit: cover;
            display: block;
        }

        .wishlist-content {
            padding: 16px;
        }

        .wishlist-product-name {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .wishlist-product-category {
            color: #777;
            font-size: 13px;
            margin-bottom: 12px;
        }

        .wishlist-price {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .wishlist-actions {
            display: flex;
            gap: 8px;
        }

        .wishlist-view {
            flex: 1;
            text-decoration: none;
            text-align: center;
            padding: 9px 12px;
            border: 1px solid #222;
            color: #222;
            border-radius: 7px;
        }

        .wishlist-delete {
            padding: 9px 12px;
            border: 1px solid #c0392b;
            background: #fff;
            color: #c0392b;
            border-radius: 7px;
            cursor: pointer;
        }

        .wishlist-empty {
            text-align: center;
            padding: 80px 20px;
            border: 1px dashed #ccc;
            border-radius: 12px;
        }

        .wishlist-empty h2 {
            margin-bottom: 10px;
        }

        .wishlist-empty p {
            color: #777;
            margin-bottom: 20px;
        }

        .wishlist-shop {
            display: inline-block;
            padding: 10px 20px;
            background: #222;
            color: #fff;
            text-decoration: none;
            border-radius: 7px;
        }

        @media (max-width: 992px) {
            .wishlist-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 700px) {
            .wishlist-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .wishlist-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>


    <div class="wishlist-page">

        <div class="wishlist-header">

            <div>
                <h1>❤️ علاقه‌مندی‌ها</h1>

                <div class="wishlist-count">
                    {{ $wishlist->items->count() }} محصول
                </div>
            </div>

        </div>


        @if($wishlist->items->isEmpty())

            <div class="wishlist-empty">

                <h2>
                    لیست علاقه‌مندی‌ها خالی است
                </h2>

                <p>
                    محصولاتی که دوست داری را به این لیست اضافه کن.
                </p>

                <a
                    href="{{ url('/') }}"
                    class="wishlist-shop"
                >
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

                        @if($image)

                            <img
                                src="{{ asset('images/products/' . $image->image) }}"
                                alt="{{ $product->name }}"
                                class="wishlist-image"
                            >

                        @else

                            <div
                                class="wishlist-image"
                                style="
                                display:flex;
                                align-items:center;
                                justify-content:center;
                                background:#f5f5f5;
                                color:#777;
                            "
                            >
                                بدون تصویر
                            </div>

                        @endif


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

@endsection
