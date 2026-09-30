<div class="col-6 col-md-4 col-lg-3">

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
                        src="{{ asset('storage/products/' . $primaryImage->image) }}"
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

                @endif


                <div class="product-name">
                    {{ $product->name }}
                </div>


                <div>

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

        </div>

    </a>

</div>

