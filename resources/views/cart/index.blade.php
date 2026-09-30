@extends('layouts.app')

@section('content')

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h2 class="mb-1">
                    Shopping Cart
                </h2>

                <div class="text-muted">
                    {{ $cart->items->count() }} item(s)
                </div>
            </div>

            <a
                href="{{ route('shop.products.index') }}"
                class="btn btn-outline-dark"
            >
                Continue Shopping
            </a>

        </div>


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


        @if($cart->items->count())


            <div class="row g-4">

                {{-- Cart Items --}}
                <div class="col-lg-8">

                    <div class="card shadow-sm">

                        <div class="card-body p-0">

                            @foreach($cart->items as $item)

                                @php

                                    $product = $item->product;
                                    $variant = $item->variant;

                                    $itemTotal =
                                        $item->price *
                                        $item->quantity;

                                    $primaryImage =
                                        $product?->images
                                            ->firstWhere(
                                                'is_primary',
                                                true
                                            )
                                        ?? $product?->images?->first();

                                @endphp


                                <div
                                    class="
                                        p-4
                                        border-bottom
                                    "
                                >

                                    <div class="row align-items-center g-3">


                                        {{-- Product Image --}}
                                        <div class="col-4 col-md-2">

                                            @if($primaryImage)

                                                <img
                                                    src="{{ asset('storage/' . $primaryImage->image) }}"
                                                    alt="{{ $product?->name }}"
                                                    class="img-fluid rounded"
                                                    style="
                                                        width:100%;
                                                        height:120px;
                                                        object-fit:cover;
                                                    "
                                                >

                                            @else

                                                <div
                                                    class="
                                                        bg-light
                                                        rounded
                                                        d-flex
                                                        align-items-center
                                                        justify-content-center
                                                    "
                                                    style="
                                                        height:120px;
                                                    "
                                                >
                                                    No Image
                                                </div>

                                            @endif

                                        </div>


                                        {{-- Product Information --}}
                                        <div class="col-8 col-md-4">

                                            @if($product)

                                                <h5 class="mb-2">

                                                    <a
                                                        href="{{ route('shop.products.show', $product) }}"
                                                        class="text-decoration-none text-dark"
                                                    >
                                                        {{ $product->name }}
                                                    </a>

                                                </h5>

                                            @else

                                                <h5 class="text-danger">
                                                    Product unavailable
                                                </h5>

                                            @endif


                                            @if($variant)

                                                <div class="small text-muted">

                                                    @if($variant->size)

                                                        <div>
                                                            Size:
                                                            {{ $variant->size->name }}
                                                        </div>

                                                    @endif


                                                    @if($variant->color)

                                                        <div class="mt-1">

                                                            Color:
                                                            {{ $variant->color->name }}

                                                            <span
                                                                style="
                                                                    display:inline-block;
                                                                    width:15px;
                                                                    height:15px;
                                                                    border-radius:50%;
                                                                    background:{{ $variant->color->hex }};
                                                                    border:1px solid #ccc;
                                                                    vertical-align:middle;
                                                                    margin-left:4px;
                                                                "
                                                            ></span>

                                                        </div>

                                                    @endif


                                                    <div class="mt-1">

                                                        SKU:
                                                        {{ $variant->sku ?? '-' }}

                                                    </div>

                                                </div>

                                            @endif

                                        </div>


                                        {{-- Price --}}
                                        <div class="col-6 col-md-2">

                                            <div class="small text-muted">
                                                Unit Price
                                            </div>

                                            <strong>
                                                {{ number_format($item->price) }}
                                            </strong>

                                        </div>


                                        {{-- Quantity --}}
                                        <div class="col-6 col-md-2">

                                            <form
                                                action="{{ route('cart.update', $item) }}"
                                                method="POST"
                                            >

                                                @csrf
                                                @method('PATCH')


                                                <label
                                                    class="small text-muted"
                                                >
                                                    Quantity
                                                </label>


                                                <div class="d-flex gap-2">

                                                    <input
                                                        type="number"
                                                        name="quantity"
                                                        value="{{ $item->quantity }}"
                                                        min="1"
                                                        @if($variant)
                                                            max="{{ $variant->stock }}"
                                                        @endif
                                                        class="form-control"
                                                        style="width:80px;"
                                                    >

                                                    <button
                                                        type="submit"
                                                        class="btn btn-outline-primary"
                                                        title="Update quantity"
                                                    >
                                                        Update
                                                    </button>

                                                </div>

                                            </form>

                                        </div>


                                        {{-- Item Total + Delete --}}
                                        <div class="col-12 col-md-2 text-md-end">

                                            <div class="small text-muted">
                                                Total
                                            </div>

                                            <strong class="fs-6">
                                                {{ number_format($itemTotal) }}
                                            </strong>


                                            <div class="mt-2">

                                                <form
                                                    action="{{ route('cart.destroy', $item) }}"
                                                    method="POST"
                                                >

                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                    >
                                                        Remove
                                                    </button>

                                                </form>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    </div>

                </div>


                {{-- Cart Summary --}}
                <div class="col-lg-4">

                    <div
                        class="card shadow-sm"
                        style="position:sticky; top:20px;"
                    >

                        <div class="card-body">

                            <h4 class="mb-4">
                                Order Summary
                            </h4>


                            <div
                                class="
                                    d-flex
                                    justify-content-between
                                    mb-3
                                "
                            >

                                <span>
                                    Subtotal
                                </span>

                                <strong>
                                    {{ number_format($total) }}
                                </strong>

                            </div>


                            <hr>


                            <div
                                class="
                                    d-flex
                                    justify-content-between
                                    align-items-center
                                    mb-4
                                "
                            >

                                <strong>
                                    Total
                                </strong>

                                <strong class="fs-4">
                                    {{ number_format($total) }}
                                </strong>

                            </div>


                            <a
                                href="{{ route('checkout.index') }}"
                                class="btn btn-dark btn-lg w-100"
                            >
                                Proceed to Checkout
                            </a>

                        </div>

                    </div>

                </div>

            </div>


        @else


            {{-- Empty Cart --}}
            <div
                class="
                    card
                    shadow-sm
                    text-center
                    py-5
                "
            >

                <div class="card-body">

                    <div
                        class="mb-3"
                        style="font-size:60px;"
                    >
                        🛒
                    </div>

                    <h3>
                        Your cart is empty
                    </h3>

                    <p class="text-muted">
                        You haven't added any products yet.
                    </p>


                    <a
                        href="{{ route('shop.products.index') }}"
                        class="btn btn-dark"
                    >
                        Start Shopping
                    </a>

                </div>

            </div>


        @endif

    </div>

@endsection
