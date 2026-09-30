@extends('layouts.app')

@section('content')

    <div class="container py-5">

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

                    <div class="d-flex gap-2 mt-3">

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
                                document.getElementById('mainProductImage').src =
                                this.src;
                            "
                            >

                        @endforeach

                    </div>

                @endif

            </div>


            {{-- Product Info --}}
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

                <div class="mb-4">

                    <h3 id="productPrice">
                        {{ number_format($product->price) }}
                    </h3>

                </div>


                {{-- Add To Cart --}}
                <form
                    action="{{ route('cart.store', $product) }}"
                    method="POST"
                    id="addToCartForm"
                >

                    @csrf

                    {{-- Variant ID --}}

                    <input
                        type="hidden"
                        name="product_variant_id"
                        id="variantId"
                    >


                    {{-- Size --}}

                    @if($variants->whereNotNull('size_id')->count())

                        <div class="mb-4">

                            <label class="form-label fw-bold">
                                Size
                            </label>

                            <div
                                class="d-flex flex-wrap gap-2"
                                id="sizeOptions"
                            >

                                @foreach(
                                    $variants
                                        ->whereNotNull('size_id')
                                        ->unique('size_id')
                                        ->sortBy('size.name')
                                    as $variant
                                )

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

                    @if($variants->whereNotNull('color_id')->count())

                        <div class="mb-4">

                            <label class="form-label fw-bold">
                                Color
                            </label>

                            <div
                                class="d-flex flex-wrap gap-2"
                                id="colorOptions"
                            >

                                @foreach(
                                    $variants
                                        ->whereNotNull('color_id')
                                        ->unique('color_id')
                                        ->sortBy('color.name')
                                    as $variant
                                )

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

                        <label class="form-label fw-bold">
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
                            class="text-muted"
                        ></small>

                    </div>


                    {{-- Add Button --}}

                    <button
                        type="submit"
                        id="addToCartButton"
                        class="btn btn-dark btn-lg"
                        disabled
                    >
                        Select Size and Color
                    </button>

                </form>


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

            </div>

        </div>

    </div>

@endsection


@push('scripts')

    <script>

        const variants = @json(
        $variants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'size_id' => $variant->size_id,
                'color_id' => $variant->color_id,
                'price' => $variant->price,
                'stock' => $variant->stock,
            ];
        })->values()
    );

        let selectedSize = null;
        let selectedColor = null;

        const variantId = document.getElementById('variantId');
        const quantity = document.getElementById('quantity');
        const productPrice = document.getElementById('productPrice');
        const stockInfo = document.getElementById('stockInfo');
        const addToCartButton =
            document.getElementById('addToCartButton');


        function findVariant()
        {
            return variants.find(function (variant) {

                const sizeMatches =
                    selectedSize === null ||
                    variant.size_id == selectedSize;

                const colorMatches =
                    selectedColor === null ||
                    variant.color_id == selectedColor;

                return sizeMatches && colorMatches;

            });
        }


        function updateVariant()
        {
            const variant = findVariant();

            if (!variant) {

                variantId.value = '';

                addToCartButton.disabled = true;

                addToCartButton.innerText =
                    'This combination is unavailable';

                stockInfo.innerText = '';

                productPrice.innerText =
                    '{{ number_format($product->price) }}';

                return;
            }


            /*
             * اگر محصول Size و Color دارد،
             * باید هر دو انتخاب شده باشند.
             */

            const hasSizes =
                variants.some(v => v.size_id !== null);

            const hasColors =
                variants.some(v => v.color_id !== null);


            if (
                (hasSizes && selectedSize === null) ||
                (hasColors && selectedColor === null)
            ) {

                variantId.value = '';

                addToCartButton.disabled = true;

                addToCartButton.innerText =
                    'Select Size and Color';

                stockInfo.innerText = '';

                return;
            }


            variantId.value = variant.id;

            productPrice.innerText =
                Number(
                    variant.price ?? {{ $product->price }}
                ).toLocaleString();


            stockInfo.innerText =
                'Available stock: ' + variant.stock;


            quantity.max = variant.stock;


            if (variant.stock <= 0) {

                addToCartButton.disabled = true;

                addToCartButton.innerText =
                    'Out of stock';

            } else {

                addToCartButton.disabled = false;

                addToCartButton.innerText =
                    'Add to Cart';
            }
        }


        document
            .querySelectorAll('.size-option')
            .forEach(function (button) {

                button.addEventListener('click', function () {

                    document
                        .querySelectorAll('.size-option')
                        .forEach(btn =>
                            btn.classList.remove('active')
                        );

                    this.classList.add('active');

                    selectedSize =
                        this.dataset.sizeId;

                    updateVariant();

                });

            });


        document
            .querySelectorAll('.color-option')
            .forEach(function (button) {

                button.addEventListener('click', function () {

                    document
                        .querySelectorAll('.color-option')
                        .forEach(btn =>
                            btn.classList.remove('active')
                        );

                    this.classList.add('active');

                    selectedColor =
                        this.dataset.colorId;

                    updateVariant();

                });

            });


        quantity.addEventListener('change', function () {

            const variant = findVariant();

            if (!variant) {
                return;
            }

            if (Number(this.value) > variant.stock) {

                this.value = variant.stock;

            }

            if (Number(this.value) < 1) {

                this.value = 1;

            }

        });

    </script>

@endpush
