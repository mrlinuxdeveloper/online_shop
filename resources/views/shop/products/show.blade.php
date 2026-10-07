@extends('shop.layouts.app')

@section('title', $product->name . ' | ' . config('app.name', 'Companymod'))

@section('content')

    <style>
        .product-detail-page {
            padding: 45px 0 70px;
        }

        .product-detail-card {
            background: #fff;
            border: 1px solid #e8e3da;
            border-radius: 20px;
            padding: 25px;
        }

        .product-gallery {
            position: relative;
            background: #f4f1eb;
            border-radius: 16px;
            overflow: hidden;
        }

        .product-main-image {
            width: 100%;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            display: block;
        }

        .product-placeholder {
            aspect-ratio: 1 / 1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 80px;
            color: #aaa;
        }

        .product-thumbnails {
            display: flex;
            gap: 10px;
            margin-top: 12px;
            overflow-x: auto;
        }

        .product-thumbnail {
            width: 75px;
            height: 75px;
            flex: 0 0 75px;
            border: 2px solid transparent;
            border-radius: 9px;
            overflow: hidden;
            padding: 0;
            background: #f4f1eb;
            cursor: pointer;
        }

        .product-thumbnail.active {
            border-color: #2E5E4E;
        }

        .product-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-info {
            padding: 10px 15px;
        }

        .product-brand {
            color: #777;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .product-title {
            font-size: 32px;
            line-height: 1.4;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .product-code {
            color: #999;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .product-price-box {
            padding: 18px;
            background: #faf7f1;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .product-price {
            color: #2E5E4E;
            font-size: 28px;
            font-weight: 800;
        }

        .product-old-price {
            color: #999;
            text-decoration: line-through;
            margin-right: 10px;
            font-size: 14px;
        }

        .discount-badge {
            display: inline-block;
            background: #B24C3C;
            color: #fff;
            padding: 5px 9px;
            border-radius: 7px;
            font-size: 12px;
            margin-right: 8px;
        }

        .option-group {
            margin-bottom: 23px;
        }

        .option-label {
            display: block;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .variant-options {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .variant-option {
            position: relative;
        }

        .variant-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .variant-option label {
            display: block;
            min-width: 48px;
            padding: 9px 15px;
            border: 1px solid #ddd7cd;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            background: #fff;
            transition: .2s;
        }

        .variant-option input:checked + label {
            border-color: #2E5E4E;
            background: #2E5E4E;
            color: #fff;
        }

        .variant-option input:disabled + label {
            opacity: .4;
            cursor: not-allowed;
            text-decoration: line-through;
        }

        .color-option label {
            min-width: 45px;
            padding: 6px;
        }

        .color-circle {
            width: 27px;
            height: 27px;
            border-radius: 50%;
            display: block;
            margin: auto;
            border: 1px solid #ddd;
        }

        .stock-info {
            font-size: 14px;
            margin-top: 8px;
        }

        .stock-available {
            color: #2E5E4E;
        }

        .stock-unavailable {
            color: #B24C3C;
        }

        .quantity-box {
            display: flex;
            align-items: center;
            width: 130px;
            border: 1px solid #ddd7cd;
            border-radius: 9px;
            overflow: hidden;
        }

        .quantity-box button {
            width: 35px;
            height: 42px;
            border: 0;
            background: #f4f1eb;
            font-size: 18px;
        }

        .quantity-box input {
            width: 60px;
            height: 42px;
            border: 0;
            text-align: center;
            outline: none;
        }

        .add-cart-button {
            width: 100%;
            border: 0;
            border-radius: 10px;
            padding: 13px;
            background: #2E5E4E;
            color: #fff;
            font-weight: 800;
            margin-top: 20px;
        }

        .add-cart-button:hover {
            background: #23483d;
        }

        .add-cart-button:disabled {
            background: #aaa;
            cursor: not-allowed;
        }

        .wishlist-button {
            width: 100%;
            border: 1px solid #ddd7cd;
            border-radius: 10px;
            padding: 12px;
            background: #fff;
            color: #B24C3C;
            font-weight: 700;
            margin-top: 10px;
        }

        .wishlist-button:hover {
            border-color: #B24C3C;
            background: #fff8f6;
        }

        .product-description {
            margin-top: 35px;
            padding-top: 30px;
            border-top: 1px solid #e8e3da;
            line-height: 2;
            color: #555;
        }

        .reviews-section {
            margin-top: 45px;
            padding-top: 35px;
            border-top: 1px solid #e8e3da;
        }

        .review-card {
            background: #faf7f1;
            border-radius: 12px;
            padding: 17px;
            margin-bottom: 12px;
        }

        .review-stars {
            color: #d69a2d;
            letter-spacing: 2px;
        }

        @media (max-width: 767px) {
            .product-detail-page {
                padding-top: 25px;
            }

            .product-info {
                padding: 25px 0 0;
            }

            .product-title {
                font-size: 25px;
            }
        }
    </style>

    <div class="product-detail-page">
        <div class="container">

            <div class="product-detail-card">
                <div class="row g-4">

                    {{-- Product Gallery --}}
                    <div class="col-lg-6">
                        <div class="product-gallery">

                            @if($product->images->first())
                                <img
                                    id="mainProductImage"
                                    class="product-main-image"
                                    src="{{ asset('images/products/' . $product->images->first()->image) }}"
                                    alt="{{ $product->name }}"
                                >
                            @else
                                <div class="product-placeholder">
                                    👕
                                </div>
                            @endif

                        </div>

                        @if($product->images->count() > 1)
                            <div class="product-thumbnails">
                                @foreach($product->images as $image)
                                    <button
                                        type="button"
                                        class="product-thumbnail {{ $loop->first ? 'active' : '' }}"
                                        onclick="changeProductImage(this, '{{ asset('images/products/' . $image->image) }}')"
                                    >
                                        <img
                                            src="{{ asset('images/products/' . $image->image) }}"
                                            alt="{{ $product->name }}"
                                        >
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Product Information --}}
                    <div class="col-lg-6">
                        <div class="product-info">

                            @if($product->brand)
                                <div class="product-brand">
                                    برند:
                                    {{ $product->brand->name }}
                                </div>
                            @endif

                            <h1 class="product-title">
                                {{ $product->name }}
                            </h1>

                            @if($product->product_code)
                                <div class="product-code">
                                    کد محصول:
                                    {{ $product->product_code }}
                                </div>
                            @endif

                            {{-- Price --}}
                            <div class="product-price-box">
                                @if($product->discount > 0)
                                    <span class="product-price">
                                    {{ number_format($product->price - ($product->price * $product->discount / 100)) }}
                                    تومان
                                </span>

                                    <span class="product-old-price">
                                    {{ number_format($product->price) }}
                                    تومان
                                </span>

                                    <span class="discount-badge">
                                    {{ number_format($product->discount) }}٪ تخفیف
                                </span>
                                @else
                                    <span class="product-price">
                                    {{ number_format($product->price) }}
                                    تومان
                                </span>
                                @endif
                            </div>

                            @if($variants->count())

                                <form
                                    action="{{ route('cart.store', $product) }}"
                                    method="POST"
                                    id="addToCartForm"
                                >
                                    @csrf

                                    {{-- Variant --}}
                                    <input
                                        type="hidden"
                                        name="product_variant_id"
                                        id="selectedVariant"
                                        value=""
                                    >

                                    {{-- Size --}}
                                    @php
                                        $sizes = $variants
                                            ->filter(fn($variant) => $variant->size)
                                            ->unique('size_id')
                                            ->values();
                                    @endphp

                                    @if($sizes->count())
                                        <div class="option-group">
                                            <label class="option-label">
                                                سایز
                                            </label>

                                            <div class="variant-options">
                                                @foreach($sizes as $size)
                                                    <div class="variant-option">
                                                        <input
                                                            type="radio"
                                                            name="size_option"
                                                            id="size_{{ $size->size_id }}"
                                                            value="{{ $size->size_id }}"
                                                        >

                                                        <label for="size_{{ $size->size_id }}">
                                                            {{ $size->size->name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Color --}}
                                    @php
                                        $colors = $variants
                                            ->filter(fn($variant) => $variant->color)
                                            ->unique('color_id')
                                            ->values();
                                    @endphp

                                    @if($colors->count())
                                        <div class="option-group">
                                            <label class="option-label">
                                                رنگ
                                            </label>

                                            <div class="variant-options">
                                                @foreach($colors as $color)
                                                    <div class="variant-option color-option">
                                                        <input
                                                            type="radio"
                                                            name="color_option"
                                                            id="color_{{ $color->color_id }}"
                                                            value="{{ $color->color_id }}"
                                                        >

                                                        <label for="color_{{ $color->color_id }}">
                                                            @if($color->color->hex)
                                                                <span
                                                                    class="color-circle"
                                                                    style="background: {{ $color->color->hex }}"
                                                                ></span>
                                                            @else
                                                                {{ $color->color->name }}
                                                            @endif
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <div
                                        id="variantMessage"
                                        class="stock-info"
                                    >
                                        لطفاً سایز و رنگ مورد نظر خود را انتخاب کنید.
                                    </div>

                                    {{-- Quantity --}}
                                    <div class="option-group mt-4">
                                        <label class="option-label">
                                            تعداد
                                        </label>

                                        <div class="quantity-box">
                                            <button type="button" onclick="changeQuantity(-1)">
                                                −
                                            </button>

                                            <input
                                                type="number"
                                                name="quantity"
                                                id="quantity"
                                                value="1"
                                                min="1"
                                                max="1"
                                            >

                                            <button type="button" onclick="changeQuantity(1)">
                                                +
                                            </button>
                                        </div>
                                    </div>

                                    <button
                                        type="submit"
                                        class="add-cart-button"
                                        id="addCartButton"
                                        disabled
                                    >
                                        افزودن به سبد خرید
                                    </button>

                                </form>

                            @else

                                <div class="alert alert-warning">
                                    این محصول در حال حاضر موجود نیست.
                                </div>

                            @endif

                            {{-- Wishlist --}}
                            @auth
                                <form
                                    action="{{ route('wishlist.store', $product) }}"
                                    method="POST"
                                >
                                    @csrf

                                    <button
                                        type="submit"
                                        class="wishlist-button"
                                    >
                                        ♡ افزودن به علاقه‌مندی‌ها
                                    </button>
                                </form>
                            @endauth

                            {{-- Description --}}
                            @if($product->description)
                                <div class="product-description">
                                    <h5 class="fw-bold mb-3">
                                        توضیحات محصول
                                    </h5>

                                    {!! nl2br(e($product->description)) !!}
                                </div>
                            @endif

                        </div>
                    </div>

                </div>

                {{-- Reviews --}}
                <div class="reviews-section">

                    <h3 class="fw-bold mb-4">
                        نظرات کاربران
                    </h3>

                    @php
                        $reviews = $product->reviews
                            ->where('status', true)
                            ->sortByDesc('created_at');
                    @endphp

                    @forelse($reviews as $review)
                        <div class="review-card">

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>
                                    {{ $review->user->name ?? 'کاربر' }}
                                </strong>

                                <span class="review-stars">
                                {{ str_repeat('★', $review->rating) }}
                                    {{ str_repeat('☆', 5 - $review->rating) }}
                            </span>
                            </div>

                            @if($review->comment)
                                <div>
                                    {{ $review->comment }}
                                </div>
                            @endif

                        </div>
                    @empty
                        <div class="text-muted">
                            هنوز نظری برای این محصول ثبت نشده است.
                        </div>
                    @endforelse

                    @auth
                        <div class="mt-4">

                            <h5 class="fw-bold mb-3">
                                ثبت نظر
                            </h5>

                            <form
                                action="{{ route('reviews.store', $product) }}"
                                method="POST"
                            >
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">
                                        امتیاز
                                    </label>

                                    <select
                                        name="rating"
                                        class="form-select"
                                        required
                                    >
                                        <option value="">انتخاب امتیاز</option>
                                        <option value="5">★★★★★ عالی</option>
                                        <option value="4">★★★★ خوب</option>
                                        <option value="3">★★★ متوسط</option>
                                        <option value="2">★★ ضعیف</option>
                                        <option value="1">★ خیلی ضعیف</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        نظر شما
                                    </label>

                                    <textarea
                                        name="comment"
                                        class="form-control"
                                        rows="4"
                                        maxlength="2000"
                                        placeholder="نظر خود را درباره این محصول بنویسید..."
                                    ></textarea>
                                </div>

                                <button
                                    type="submit"
                                    class="btn btn-success"
                                >
                                    ثبت نظر
                                </button>
                            </form>

                        </div>
                    @else
                        <div class="alert alert-light mt-4">
                            برای ثبت نظر ابتدا وارد حساب کاربری خود شوید.
                        </div>
                    @endauth

                </div>

            </div>

        </div>
    </div>

    <script>
        const productVariants = @json(
        $variants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'size_id' => $variant->size_id,
                'color_id' => $variant->color_id,
                'stock' => $variant->stock,
                'price' => (float) $variant->price,
                'status' => (bool) $variant->status,
            ];
        })->values()
    );

        const sizeInputs = document.querySelectorAll('input[name="size_option"]');
        const colorInputs = document.querySelectorAll('input[name="color_option"]');
        const selectedVariantInput = document.getElementById('selectedVariant');
        const quantityInput = document.getElementById('quantity');
        const addCartButton = document.getElementById('addCartButton');
        const variantMessage = document.getElementById('variantMessage');

        function findSelectedVariant() {
            const selectedSize = document.querySelector('input[name="size_option"]:checked');
            const selectedColor = document.querySelector('input[name="color_option"]:checked');

            const sizeId = selectedSize ? selectedSize.value : null;
            const colorId = selectedColor ? selectedColor.value : null;

            return productVariants.find(variant => {
                const sizeMatch = variant.size_id == null || variant.size_id == sizeId;
                const colorMatch = variant.color_id == null || variant.color_id == colorId;

                return sizeMatch && colorMatch;
            });
        }

        function updateVariant() {
            const selectedVariant = findSelectedVariant();

            selectedVariantInput.value = '';
            addCartButton.disabled = true;

            if (!selectedVariant) {
                variantMessage.textContent = 'این ترکیب سایز و رنگ موجود نیست.';
                variantMessage.className = 'stock-info stock-unavailable';
                quantityInput.max = 1;
                return;
            }

            if (selectedVariant.stock <= 0) {
                variantMessage.textContent = 'این محصول در این ترکیب ناموجود است.';
                variantMessage.className = 'stock-info stock-unavailable';
                quantityInput.max = 1;
                return;
            }

            selectedVariantInput.value = selectedVariant.id;
            quantityInput.max = selectedVariant.stock;

            if (parseInt(quantityInput.value) > selectedVariant.stock) {
                quantityInput.value = selectedVariant.stock;
            }

            variantMessage.textContent =
                'موجودی: ' + selectedVariant.stock + ' عدد';

            variantMessage.className = 'stock-info stock-available';

            addCartButton.disabled = false;
        }

        function changeQuantity(amount) {
            const current = parseInt(quantityInput.value) || 1;
            const max = parseInt(quantityInput.max) || 1;

            let next = current + amount;

            if (next < 1) {
                next = 1;
            }

            if (next > max) {
                next = max;
            }

            quantityInput.value = next;
        }

        sizeInputs.forEach(input => {
            input.addEventListener('change', updateVariant);
        });

        colorInputs.forEach(input => {
            input.addEventListener('change', updateVariant);
        });

        quantityInput.addEventListener('change', function () {
            let value = parseInt(this.value) || 1;
            const max = parseInt(this.max) || 1;

            if (value < 1) value = 1;
            if (value > max) value = max;

            this.value = value;
        });

        function changeProductImage(button, imageUrl) {
            const mainImage = document.getElementById('mainProductImage');

            if (!mainImage) {
                return;
            }

            mainImage.src = imageUrl;

            document
                .querySelectorAll('.product-thumbnail')
                .forEach(item => item.classList.remove('active'));

            button.classList.add('active');
        }
    </script>

@endsection
