@extends('layouts.app')

@section('content')

    <style>
        .wishlist-button {
            border: 1px solid #222;
            background: #fff;
            color: #222;
            padding: 10px 18px;
            border-radius: 7px;
            cursor: pointer;
            transition: 0.2s;
        }

        .wishlist-button:hover {
            background: #222;
            color: #fff;
        }
    </style>
    <div class="container py-5">

        {{-- Messages --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="row g-5">

            {{-- Product Images --}}
            <div class="col-md-6">

                @php
                    $primaryImage = $product->images
                        ->firstWhere('is_primary', true)
                        ?? $product->images->first();
                @endphp

                @if($primaryImage)

                    <img
                        id="mainProductImage"
                        src="{{ asset('storage/' . $primaryImage->image) }}"
                        class="img-fluid rounded"
                        style="width:100%; max-height:550px; object-fit:contain;"
                        alt="{{ $product->name }}"
                    >

                @else

                    <div
                        class="bg-light rounded d-flex align-items-center justify-content-center"
                        style="height:450px;"
                    >
                        No Image
                    </div>

                @endif

                {{-- Thumbnails --}}
                @if($product->images->count() > 1)

                    <div class="d-flex flex-wrap gap-2 mt-3">

                        @foreach($product->images as $image)

                            <img
                                src="{{ asset('storage/' . $image->image) }}"
                                class="rounded border product-thumbnail"
                                style="
                                width:80px;
                                height:80px;
                                object-fit:cover;
                                cursor:pointer;
                            "
                                onclick="
                                document.getElementById('mainProductImage').src = this.src;
                            "
                                alt="{{ $product->name }}"
                            >

                        @endforeach

                    </div>

                @endif

            </div>


            {{-- Product Information --}}
            <div class="col-md-6">

                <h1 class="mb-3">
                    {{ $product->name }}
                </h1>

                @if($product->brand)

                    <div class="text-muted mb-2">
                        Brand:
                        {{ $product->brand->name }}
                    </div>

                @endif

                @if($product->category)

                    <div class="text-muted mb-3">
                        Category:
                        {{ $product->category->name }}
                    </div>

                @endif


                {{-- Price --}}
                <div class="mb-4">

                    <h3 id="productPrice">
                        {{ number_format($product->price) }}
                    </h3>

                    @if($product->discount > 0)

                        <div class="text-muted">

                            <del>
                                {{ number_format($product->price) }}
                            </del>

                            <span class="text-danger ms-2">
                            {{ $product->discount }}% OFF
                        </span>

                        </div>

                    @endif

                </div>


                {{-- Add To Cart Form --}}
                <form
                    action="{{ route('cart.store', $product) }}"
                    method="POST"
                    id="addToCartForm"
                >

                    @csrf

                    {{-- Selected Variant --}}
                    <input
                        type="hidden"
                        name="product_variant_id"
                        id="variantId"
                        value=""
                    >


                    {{-- Size --}}
                    @php
                        $sizeVariants = $variants
                            ->whereNotNull('size_id')
                            ->unique('size_id')
                            ->sortBy('size.name');
                    @endphp

                    @if($sizeVariants->count() > 0)

                        <div class="mb-4">

                            <label class="form-label fw-bold">
                                Size
                            </label>

                            <div
                                class="d-flex flex-wrap gap-2"
                                id="sizeOptions"
                            >

                                @foreach($sizeVariants as $variant)

                                    @if($variant->size)

                                        <button
                                            type="button"
                                            class="btn btn-outline-dark size-option"
                                            data-size-id="{{ $variant->size_id }}"
                                        >
                                            {{ $variant->size->name }}
                                        </button>

                                    @endif

                                @endforeach

                            </div>

                        </div>

                    @endif


                    {{-- Color --}}
                    @php
                        $colorVariants = $variants
                            ->whereNotNull('color_id')
                            ->unique('color_id')
                            ->sortBy('color.name');
                    @endphp

                    @if($colorVariants->count() > 0)

                        <div class="mb-4">

                            <label class="form-label fw-bold">
                                Color
                            </label>

                            <div
                                class="d-flex flex-wrap gap-2"
                                id="colorOptions"
                            >

                                @foreach($colorVariants as $variant)

                                    @if($variant->color)

                                        <button
                                            type="button"
                                            class="btn btn-outline-dark color-option"
                                            data-color-id="{{ $variant->color_id }}"
                                        >

                                        <span
                                            style="
                                                display:inline-block;
                                                width:18px;
                                                height:18px;
                                                border-radius:50%;
                                                background:{{ $variant->color->hex }};
                                                border:1px solid #ccc;
                                                vertical-align:middle;
                                            "
                                        ></span>

                                            {{ $variant->color->name }}

                                        </button>

                                    @endif

                                @endforeach

                            </div>

                        </div>

                    @endif


                    {{-- Quantity --}}
                    <div class="mb-4">

                        <label
                            for="quantity"
                            class="form-label fw-bold"
                        >
                            Quantity
                        </label>

                        <input
                            type="number"
                            name="quantity"
                            id="quantity"
                            value="1"
                            min="1"
                            class="form-control"
                            style="width:120px;"
                        >

                        <small
                            id="stockInfo"
                            class="text-muted d-block mt-2"
                        ></small>

                    </div>


                    {{-- Add To Cart --}}
                    <button
                        type="submit"
                        id="addToCartButton"
                        class="btn btn-dark btn-lg"
                        disabled
                    >
                        Select Options
                    </button>

                </form>
                @auth
                    <form
                        action="{{ route('wishlist.store', $product->id) }}"
                        method="POST"
                        style="display: inline;"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="wishlist-button"
                        >
                            ❤️ افزودن به علاقه‌مندی‌ها
                        </button>
                    </form>
                @endauth

                {{-- Description --}}
                @if($product->description)

                    <div class="mt-5">

                        <h5>
                            Description
                        </h5>

                        <div class="text-muted">
                            {!! nl2br(e($product->description)) !!}
                        </div>

                    </div>

                @endif
                {{-- Reviews --}}
                <div class="mt-5">

                    <h4 class="mb-4">
                        نظرات کاربران
                    </h4>

                    @php
                        $approvedReviews = $product->reviews
                            ->where('status', true)
                            ->sortByDesc('created_at');
                    @endphp

                    @if($approvedReviews->count())

                        @foreach($approvedReviews as $review)

                            <div class="border rounded p-3 mb-3">

                                <div class="d-flex justify-content-between">

                                    <strong>
                                        {{ $review->user->name }}
                                    </strong>

                                    <span>
                        @for($i = 1; $i <= 5; $i++)

                                            @if($i <= $review->rating)
                                                ⭐
                                            @else
                                                ☆
                                            @endif

                                        @endfor
                    </span>

                                </div>

                                @if($review->comment)

                                    <p class="mt-2 mb-0">
                                        {{ $review->comment }}
                                    </p>

                                @endif

                                <small class="text-muted">
                                    {{ $review->created_at->format('Y/m/d') }}
                                </small>

                            </div>

                        @endforeach

                    @else

                        <p class="text-muted">
                            هنوز نظری برای این محصول ثبت نشده است.
                        </p>

                    @endif

                </div>


                @auth

                    <div class="mt-5">

                        <h4 class="mb-4">
                            ثبت نظر
                        </h4>

                        <form
                            action="{{ route('reviews.store', $product) }}"
                            method="POST"
                        >
                            @csrf

                            <div class="mb-3">

                                <label class="form-label">
                                    امتیاز
                                </label>

                                <div>
                                    @for($i = 1; $i <= 5; $i++)

                                        <label
                                            style="
                                cursor: pointer;
                                font-size: 28px;
                                margin-left: 5px;
                            "
                                        >

                                            <input
                                                type="radio"
                                                name="rating"
                                                value="{{ $i }}"
                                                style="display: none;"
                                                {{ old('rating') == $i ? 'checked' : '' }}
                                            >

                                            ⭐

                                        </label>

                                    @endfor
                                </div>

                                @error('rating')
                                <div class="text-danger mt-1">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>

                            <div class="mb-3">

                                <label
                                    for="comment"
                                    class="form-label"
                                >
                                    نظر شما
                                </label>

                                <textarea
                                    name="comment"
                                    id="comment"
                                    rows="5"
                                    class="form-control"
                                    placeholder="نظر خود را درباره این محصول بنویسید..."
                                >{{ old('comment') }}</textarea>

                                @error('comment')
                                <div class="text-danger mt-1">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>

                            <button
                                type="submit"
                                class="btn btn-dark"
                            >
                                ثبت نظر
                            </button>

                        </form>

                    </div>

                @else

                    <div class="alert alert-info mt-5">

                        برای ثبت نظر ابتدا وارد حساب کاربری خود شوید.

                        <a href="{{ route('login') }}">
                            ورود
                        </a>

                    </div>

                @endauth

            </div>

        </div>

    </div>


@endsection

{{-- ========================================================= --}}
{{-- Variant JavaScript --}}
{{-- ========================================================= --}}

@php


    $variantData = $variants->map(function ($variant) {

        return [
            'id' => (int) $variant->id,
            'size_id' => $variant->size_id !== null
                ? (int) $variant->size_id
                : null,
            'color_id' => $variant->color_id !== null
                ? (int) $variant->color_id
                : null,
            'price' => (float) $variant->price,
            'stock' => (int) $variant->stock,
        ];

    })->values()->toArray();


@endphp

<script>
    document.addEventListener('DOMContentLoaded', function () {

        /*
         * Variant data from Laravel
         */
        const variants = @json($variantData);

        console.log('Product variants:', variants);


        /*
         * Elements
         */
        const variantId =
            document.getElementById('variantId');

        const quantity =
            document.getElementById('quantity');

        const productPrice =
            document.getElementById('productPrice');

        const stockInfo =
            document.getElementById('stockInfo');

        const addToCartButton =
            document.getElementById('addToCartButton');


        /*
         * Selected options
         */
        let selectedSize = null;
        let selectedColor = null;


        /*
         * Does product have size/color?
         */
        const hasSizes =
            variants.some(function (variant) {
                return variant.size_id !== null;
            });

        const hasColors =
            variants.some(function (variant) {
                return variant.color_id !== null;
            });


        console.log('Has sizes:', hasSizes);
        console.log('Has colors:', hasColors);


        /*
         * Find matching variant
         */
        function findVariant() {

            return variants.find(function (variant) {

                const sizeMatches =
                    !hasSizes ||
                    Number(variant.size_id) === Number(selectedSize);

                const colorMatches =
                    !hasColors ||
                    Number(variant.color_id) === Number(selectedColor);

                return sizeMatches && colorMatches;

            });

        }


        /*
         * Reset form
         */
        function resetVariant() {

            variantId.value = '';

            stockInfo.innerText = '';

            quantity.max = '';

            quantity.value = 1;

            addToCartButton.disabled = true;

            addToCartButton.innerText =
                'Select Options';

        }


        /*
         * Update variant
         */
        function updateVariant() {

            console.log(
                'Selected:',
                {
                    size: selectedSize,
                    color: selectedColor
                }
            );


            /*
             * Required options not selected
             */
            if (
                (hasSizes && selectedSize === null) ||
                (hasColors && selectedColor === null)
            ) {

                resetVariant();

                return;
            }


            /*
             * Find variant
             */
            const variant = findVariant();

            console.log(
                'Matched variant:',
                variant
            );


            /*
             * No matching variant
             */
            if (!variant) {

                variantId.value = '';

                stockInfo.innerText =
                    'This combination is unavailable.';

                quantity.max = '';

                addToCartButton.disabled = true;

                addToCartButton.innerText =
                    'Combination unavailable';

                return;
            }


            /*
             * Set variant ID
             */
            variantId.value = variant.id;


            /*
             * Set price
             */
            productPrice.innerText =
                Number(variant.price).toLocaleString();


            /*
             * Set stock
             */
            stockInfo.innerText =
                'Available stock: ' + variant.stock;

            quantity.max = variant.stock;


            /*
             * Out of stock
             */
            if (variant.stock <= 0) {

                quantity.value = 1;

                addToCartButton.disabled = true;

                addToCartButton.innerText =
                    'Out of stock';

                return;
            }


            /*
             * Fix quantity
             */
            if (
                Number(quantity.value) < 1 ||
                Number(quantity.value) > variant.stock
            ) {

                quantity.value = 1;

            }


            /*
             * Enable Add To Cart
             */
            addToCartButton.disabled = false;

            addToCartButton.innerText =
                'Add to Cart';

        }


        /*
         * SIZE BUTTONS
         */
        const sizeButtons =
            document.querySelectorAll('.size-option');

        sizeButtons.forEach(function (button) {

            button.addEventListener('click', function (event) {

                event.preventDefault();

                /*
                 * Remove active from other sizes
                 */
                sizeButtons.forEach(function (btn) {

                    btn.classList.remove('active');

                });


                /*
                 * Add active
                 */
                this.classList.add('active');


                /*
                 * Save selected size
                 */
                selectedSize =
                    Number(this.dataset.sizeId);


                console.log(
                    'Selected size:',
                    selectedSize
                );


                /*
                 * Update
                 */
                updateVariant();

            });

        });


        /*
         * COLOR BUTTONS
         */
        const colorButtons =
            document.querySelectorAll('.color-option');

        colorButtons.forEach(function (button) {

            button.addEventListener('click', function (event) {

                event.preventDefault();

                /*
                 * Remove active from other colors
                 */
                colorButtons.forEach(function (btn) {

                    btn.classList.remove('active');

                });


                /*
                 * Add active
                 */
                this.classList.add('active');


                /*
                 * Save selected color
                 */
                selectedColor =
                    Number(this.dataset.colorId);


                console.log(
                    'Selected color:',
                    selectedColor
                );


                /*
                 * Update
                 */
                updateVariant();

            });

        });


        /*
         * QUANTITY
         */
        quantity.addEventListener('input', function () {

            const variant = findVariant();

            if (!variant) {
                return;
            }


            let value =
                Number(this.value);


            if (
                !Number.isFinite(value) ||
                value < 1
            ) {

                value = 1;

            }


            if (
                value > variant.stock
            ) {

                value = variant.stock;

            }


            this.value = value;

        });


        /*
         * FORM SUBMIT VALIDATION
         */
        document
            .getElementById('addToCartForm')
            .addEventListener('submit', function (event) {

                const variant = findVariant();


                if (!variant) {

                    event.preventDefault();

                    alert(
                        'Please select the available size and color.'
                    );

                    return;

                }


                if (variant.stock <= 0) {

                    event.preventDefault();

                    alert(
                        'This product is out of stock.'
                    );

                    return;

                }


                if (
                    Number(quantity.value) < 1 ||
                    Number(quantity.value) > variant.stock
                ) {

                    event.preventDefault();

                    alert(
                        'Invalid quantity.'
                    );

                    return;

                }


                /*
                 * Make sure variant ID exists
                 */
                variantId.value =
                    variant.id;

            });


        /*
         * Initial state
         */
        resetVariant();

    });
</script>
