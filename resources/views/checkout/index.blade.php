@extends('layouts.app')

@section('content')

    <div class="container py-5">

        <div class="mb-4">
            <h2 class="fw-bold">Checkout</h2>
            <p class="text-muted mb-0">
                Review your order and select your shipping address.
            </p>
        </div>

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

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ route('checkout.store') }}"
            method="POST"
        >
            @csrf

            <div class="row g-4">

                {{-- Left side --}}
                <div class="col-lg-8">

                    {{-- Addresses --}}
                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">
                                Shipping Address
                            </h5>
                        </div>

                        <div class="card-body">

                            @if($addresses->isEmpty())

                                <div class="alert alert-warning mb-0">
                                    <div class="d-flex justify-content-between align-items-center">

                                    <span>
                                        You don't have any saved addresses.
                                    </span>

                                        <a
                                            href="{{ route('dashboard') }}"
                                            class="btn btn-sm btn-primary"
                                        >
                                            Add Address
                                        </a>

                                    </div>
                                </div>

                            @else

                                <div class="row g-3">

                                    @foreach($addresses as $address)

                                        <div class="col-md-6">

                                            <label
                                                class="address-card w-100"
                                                for="address-{{ $address->id }}"
                                            >

                                                <input
                                                    type="radio"
                                                    name="address_id"
                                                    value="{{ $address->id }}"
                                                    id="address-{{ $address->id }}"
                                                    class="address-radio"
                                                    @checked(
                                                        old(
                                                            'address_id',
                                                            $addresses->firstWhere('is_default', true)?->id
                                                            ?? $addresses->first()?->id
                                                        ) == $address->id
                                                    )
                                                >

                                                <div class="address-content">

                                                    <div class="d-flex justify-content-between align-items-center mb-2">

                                                        <strong>
                                                            {{ $address->title }}
                                                        </strong>

                                                        @if($address->is_default)
                                                            <span class="badge bg-success">
                                                            Default
                                                        </span>
                                                        @endif

                                                    </div>

                                                    <div class="small text-muted">

                                                        <div class="mb-1">
                                                            <strong>
                                                                {{ $address->receiver_name }}
                                                            </strong>
                                                        </div>

                                                        <div class="mb-1">
                                                            {{ $address->receiver_phone }}
                                                        </div>

                                                        <div class="mb-1">
                                                            {{ $address->province }}
                                                            -
                                                            {{ $address->city }}
                                                        </div>

                                                        <div class="mb-1">
                                                            {{ $address->address }}
                                                        </div>

                                                        <div>
                                                            Postal Code:
                                                            {{ $address->postal_code }}
                                                        </div>

                                                    </div>

                                                </div>

                                            </label>

                                        </div>

                                    @endforeach

                                </div>

                            @endif

                        </div>
                    </div>


                    {{-- Products --}}
                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">
                                Order Items
                            </h5>
                        </div>

                        <div class="card-body p-0">

                            @foreach($cart->items as $item)

                                <div class="checkout-item p-3">

                                    <div class="row align-items-center g-3">

                                        {{-- Image --}}
                                        <div class="col-auto">

                                            @php
                                                $primaryImage = $item->product->images
                                                    ->firstWhere('is_primary', true);

                                                $image = $primaryImage
                                                    ?? $item->product->images->first();
                                            @endphp

                                            @if($image)

                                                <img
                                                    src="{{ asset('storage/' . $image->image) }}"
                                                    alt="{{ $item->product->name }}"
                                                    class="product-image"
                                                >

                                            @else

                                                <div class="product-image-placeholder">
                                                    No Image
                                                </div>

                                            @endif

                                        </div>


                                        {{-- Product information --}}
                                        <div class="col">

                                            <h6 class="fw-bold mb-2">
                                                {{ $item->product->name }}
                                            </h6>

                                            <div class="small text-muted">

                                                <div>
                                                    Product Code:
                                                    {{ $item->product->product_code }}
                                                </div>

                                                @if($item->variant?->size)
                                                    <div>
                                                        Size:
                                                        {{ $item->variant->size->name }}
                                                    </div>
                                                @endif

                                                @if($item->variant?->color)
                                                    <div>
                                                        Color:
                                                        {{ $item->variant->color->name }}
                                                    </div>
                                                @endif

                                                @if($item->variant?->sku)
                                                    <div>
                                                        SKU:
                                                        {{ $item->variant->sku }}
                                                    </div>
                                                @endif

                                            </div>

                                        </div>


                                        {{-- Quantity --}}
                                        <div class="col-auto text-center">

                                            <div class="small text-muted mb-1">
                                                Quantity
                                            </div>

                                            <strong>
                                                {{ $item->quantity }}
                                            </strong>

                                        </div>


                                        {{-- Price --}}
                                        <div class="col-auto text-end">

                                            <div class="small text-muted mb-1">
                                                Price
                                            </div>

                                            <strong>
                                                {{ number_format($item->price) }}
                                            </strong>

                                        </div>


                                        {{-- Total --}}
                                        <div class="col-auto text-end">

                                            <div class="small text-muted mb-1">
                                                Total
                                            </div>

                                            <strong>
                                                {{ number_format($item->price * $item->quantity) }}
                                            </strong>

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>
                    </div>


                    {{-- Note --}}
                    <div class="card border-0 shadow-sm">

                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">
                                Order Note
                            </h5>
                        </div>

                        <div class="card-body">

                        <textarea
                            name="note"
                            class="form-control"
                            rows="4"
                            placeholder="Add a note for your order (optional)"
                        >{{ old('note') }}</textarea>

                        </div>

                    </div>

                </div>


                {{-- Right side --}}
                <div class="col-lg-4">

                    <div class="card border-0 shadow-sm checkout-summary">

                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">
                                Order Summary
                            </h5>
                        </div>

                        <div class="card-body">

                            {{-- Subtotal --}}
                            <div class="summary-row">
                            <span>
                                Subtotal
                            </span>

                                <strong>
                                    {{ number_format($subtotal) }}
                                </strong>
                            </div>


                            {{-- Discount --}}
                            <div class="summary-row">

                            <span>
                                Discount
                            </span>

                                <strong class="text-success">
                                    {{ number_format($discount) }}
                                </strong>

                            </div>


                            {{-- Shipping --}}
                            <div class="summary-row">

                            <span>
                                Shipping
                            </span>

                                <strong>
                                    {{ number_format($shippingCost) }}
                                </strong>

                            </div>


                            {{-- Tax --}}
                            <div class="summary-row">

                            <span>
                                Tax
                            </span>

                                <strong>
                                    {{ number_format($tax) }}
                                </strong>

                            </div>


                            <hr>


                            {{-- Total --}}
                            <div class="summary-total">

                            <span>
                                Total
                            </span>

                                <strong>
                                    {{ number_format($total) }}
                                </strong>

                            </div>


                            <div class="text-muted small mt-2">
                                Currency: Toman
                            </div>


                            {{-- Submit --}}
                            <button
                                type="submit"
                                class="btn btn-dark btn-lg w-100 mt-4"
                                @disabled($addresses->isEmpty())
                            >
                                Place Order
                            </button>


                            <a
                                href="{{ route('cart.index') }}"
                                class="btn btn-outline-secondary w-100 mt-2"
                            >
                                Back to Cart
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>


    <style>

        .address-card {
            cursor: pointer;
            display: block;
            margin: 0;
        }

        .address-radio {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .address-content {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px;
            height: 100%;
            transition: all 0.2s ease;
            background: #fff;
        }

        .address-card:hover .address-content {
            border-color: #999;
        }

        .address-radio:checked + .address-content {
            border-color: #212529;
            background: #f8f9fa;
        }

        .checkout-item {
            border-bottom: 1px solid #eee;
        }

        .checkout-item:last-child {
            border-bottom: 0;
        }

        .product-image {
            width: 90px;
            height: 110px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #eee;
        }

        .product-image-placeholder {
            width: 90px;
            height: 110px;
            border-radius: 8px;
            background: #f1f1f1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #777;
            font-size: 12px;
            text-align: center;
        }

        .checkout-summary {
            position: sticky;
            top: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 0;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 20px;
        }

        @media (max-width: 767px) {

            .checkout-item .row {
                align-items: flex-start;
            }

            .checkout-item .col-auto.text-end {
                text-align: left !important;
            }

            .product-image,
            .product-image-placeholder {
                width: 70px;
                height: 90px;
            }

        }

    </style>

@endsection
