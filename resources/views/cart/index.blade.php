@extends('shop.layouts.app')

@section('content')
    <style>
        .cart-page {
            padding: 50px 0 80px;
            background: #f8f6f1;
            min-height: 70vh;
        }
        .cart-title {
            font-size: 30px;
            font-weight: 800;
            color: #25352d;
        }
        .cart-subtitle {
            color: #777;
            font-size: 14px;
        }
        .cart-card,
        .summary-card {
            background: #fff;
            border: 1px solid #e8e3da;
            border-radius: 18px;
            box-shadow: 0 8px 30px rgba(0,0,0,.04);
        }
        .cart-item {
            padding: 22px;
            border-bottom: 1px solid #eee9e1;
        }
        .cart-item:last-child {
            border-bottom: 0;
        }
        .product-image {
            width: 105px;
            height: 125px;
            object-fit: cover;
            border-radius: 14px;
            background: #f3f0eb;
        }
        .product-placeholder {
            width: 105px;
            height: 125px;
            border-radius: 14px;
            background: #f3f0eb;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 13px;
        }
        .product-name {
            color: #25352d;
            font-size: 17px;
            font-weight: 700;
            text-decoration: none;
        }
        .product-name:hover {
            color: #6f5138;
        }
        .variant-info {
            color: #777;
            font-size: 13px;
            line-height: 1.9;
        }
        .color-dot {
            display: inline-block;
            width: 13px;
            height: 13px;
            border-radius: 50%;
            border: 1px solid #ccc;
            vertical-align: middle;
            margin-right: 4px;
        }
        .price-label {
            display: block;
            color: #999;
            font-size: 12px;
            margin-bottom: 4px;
        }
        .price-value {
            color: #25352d;
            font-weight: 700;
            font-size: 15px;
        }
        .quantity-form {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .quantity-input {
            width: 65px;
            height: 40px;
            border: 1px solid #ddd6cc;
            border-radius: 9px;
            text-align: center;
        }
        .update-btn {
            height: 40px;
            border-radius: 9px;
        }
        .item-total {
            color: #25352d;
            font-size: 16px;
            font-weight: 800;
        }
        .remove-btn {
            border: 0;
            background: transparent;
            color: #b24c3a;
            font-size: 13px;
            padding: 0;
        }
        .remove-btn:hover {
            color: #8e3324;
        }
        .summary-card {
            position: sticky;
            top: 25px;
            padding: 25px;
        }
        .summary-title {
            font-size: 21px;
            font-weight: 800;
            color: #25352d;
            margin-bottom: 25px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            color: #666;
        }
        .summary-row strong {
            color: #25352d;
        }
        .summary-total {
            padding-top: 18px;
            border-top: 1px solid #eee9e1;
            margin-top: 20px;
        }
        .summary-total strong:last-child {
            font-size: 23px;
        }
        .checkout-btn {
            display: block;
            width: 100%;
            background: #25352d;
            color: #fff;
            border: 0;
            border-radius: 11px;
            padding: 13px 20px;
            text-align: center;
            text-decoration: none;
            font-weight: 700;
            margin-top: 22px;
            transition: .2s;
        }
        .checkout-btn:hover {
            background: #6f5138;
            color: #fff;
        }
        .continue-btn {
            border-radius: 10px;
        }
        .empty-cart {
            background: #fff;
            border: 1px solid #e8e3da;
            border-radius: 18px;
            padding: 70px 20px;
            text-align: center;
        }
        .empty-icon {
            font-size: 65px;
            margin-bottom: 20px;
        }
        .empty-cart h3 {
            color: #25352d;
            font-weight: 800;
        }
        @media (max-width: 991px) {
            .summary-card {
                position: static;
            }
        }
        @media (max-width: 767px) {
            .cart-title {
                font-size: 24px;
            }
            .cart-item {
                padding: 16px;
            }
            .product-image,
            .product-placeholder {
                width: 85px;
                height: 105px;
            }
        }
    </style>

    <div class="cart-page">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div>
                    <h1 class="cart-title mb-1">Shopping Cart</h1>
                    <div class="cart-subtitle">
                        {{ $cart->items->count() }} item(s) in your cart
                    </div>
                </div>

                <a href="{{ route('shop.products.index') }}"
                   class="btn btn-outline-dark continue-btn">
                    Continue Shopping
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success rounded-3">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger rounded-3">
                    {{ session('error') }}
                </div>
            @endif

            @if($cart->items->count())
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="cart-card overflow-hidden">
                            @foreach($cart->items as $item)
                                @php
                                    $product = $item->product;
                                    $variant = $item->variant;
                                    $itemTotal = $item->price * $item->quantity;

                                    $primaryImage = $product?->images
                                        ->firstWhere('is_primary', true)
                                        ?? $product?->images?->first();
                                @endphp

                                <div class="cart-item">
                                    <div class="row align-items-center g-3">
                                        <div class="col-4 col-sm-3 col-md-2">
                                            @if($primaryImage)
                                                <img
                                                    src="{{ asset('storage/' . $primaryImage->image) }}"
                                                    alt="{{ $product?->name }}"
                                                    class="product-image"
                                                >
                                            @else
                                                <div class="product-placeholder">
                                                    No Image
                                                </div>
                                            @endif
                                        </div>

                                        <div class="col-8 col-sm-9 col-md-4">
                                            @if($product)
                                                <a href="{{ route('shop.products.show', $product) }}"
                                                   class="product-name">
                                                    {{ $product->name }}
                                                </a>
                                            @else
                                                <div class="text-danger fw-bold">
                                                    Product unavailable
                                                </div>
                                            @endif

                                            @if($variant)
                                                <div class="variant-info mt-2">
                                                    @if($variant->size)
                                                        <div>
                                                            <strong>Size:</strong>
                                                            {{ $variant->size->name }}
                                                        </div>
                                                    @endif

                                                    @if($variant->color)
                                                        <div>
                                                            <strong>Color:</strong>
                                                            {{ $variant->color->name }}

                                                            <span
                                                                class="color-dot"
                                                                style="background: {{ $variant->color->hex }};"
                                                            ></span>
                                                        </div>
                                                    @endif

                                                    <div>
                                                        <strong>SKU:</strong>
                                                        {{ $variant->sku ?? '-' }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="col-6 col-md-2">
                                            <span class="price-label">Unit Price</span>
                                            <span class="price-value">
                                            {{ number_format($item->price) }}
                                        </span>
                                        </div>

                                        <div class="col-6 col-md-2">
                                            <span class="price-label">Quantity</span>

                                            <form action="{{ route('cart.update', $item) }}"
                                                  method="POST"
                                                  class="quantity-form">
                                                @csrf
                                                @method('PATCH')

                                                <input
                                                    type="number"
                                                    name="quantity"
                                                    value="{{ $item->quantity }}"
                                                    min="1"
                                                    @if($variant)
                                                        max="{{ $variant->stock }}"
                                                    @endif
                                                    class="quantity-input"
                                                >

                                                <button type="submit"
                                                        class="btn btn-outline-dark btn-sm update-btn"
                                                        title="Update quantity">
                                                    ✓
                                                </button>
                                            </form>
                                        </div>

                                        <div class="col-12 col-md-2 text-md-end">
                                            <span class="price-label">Total</span>

                                            <div class="item-total">
                                                {{ number_format($itemTotal) }}
                                            </div>

                                            <form action="{{ route('cart.destroy', $item) }}"
                                                  method="POST"
                                                  class="mt-2">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="remove-btn">
                                                    Remove
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="summary-card">
                            <h2 class="summary-title">Order Summary</h2>

                            <div class="summary-row">
                                <span>Subtotal</span>
                                <strong>{{ number_format($total) }}</strong>
                            </div>

                            <div class="summary-row">
                                <span>Shipping</span>
                                <strong>Free</strong>
                            </div>

                            <div class="summary-row summary-total">
                                <strong>Total</strong>
                                <strong>{{ number_format($total) }}</strong>
                            </div>

                            <a href="{{ route('checkout.index') }}"
                               class="checkout-btn">
                                Proceed to Checkout
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="empty-cart">
                    <div class="empty-icon">🛒</div>

                    <h3>Your cart is empty</h3>

                    <p class="text-muted mb-4">
                        You haven't added any products yet.
                    </p>

                    <a href="{{ route('shop.products.index') }}"
                       class="btn btn-dark px-4 py-2 rounded-3">
                        Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
