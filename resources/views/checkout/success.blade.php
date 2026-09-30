@extends('layouts.app')

@section('content')

    @if($order->payment_status !== 'paid')

        <a
            href="{{ route('payment.start', $order) }}"
            class="btn btn-success"
        >
            Pay Now
        </a>

    @endif


    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="card border-0 shadow-sm">

                    <div class="card-body p-5 text-center">

                        <div class="mb-4">
                            <div
                                class="success-icon mx-auto"
                            >
                                ✓
                            </div>
                        </div>

                        <h2 class="fw-bold mb-3">
                            Order Created Successfully
                        </h2>

                        <p class="text-muted mb-4">
                            Your order has been created successfully.
                        </p>

                        <div class="order-info">

                            <div class="info-row">
                                <span>Order Number</span>
                                <strong>
                                    {{ $order->order_number }}
                                </strong>
                            </div>

                            <div class="info-row">
                                <span>Invoice Number</span>
                                <strong>
                                    {{ $order->invoice?->invoice_number ?? '-' }}
                                </strong>
                            </div>

                            <div class="info-row">
                                <span>Order Status</span>
                                <span class="badge bg-warning text-dark">
                                {{ ucfirst($order->status) }}
                            </span>
                            </div>

                            <div class="info-row">
                                <span>Payment Status</span>
                                <span class="badge bg-secondary">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                            </div>

                            <div class="info-row">
                                <span>Total Price</span>
                                <strong>
                                    {{ number_format($order->total_price) }}
                                </strong>
                            </div>

                        </div>

                        <hr class="my-4">

                        <h5 class="fw-bold text-start mb-3">
                            Shipping Address
                        </h5>

                        @if($order->address)

                            <div class="address-box text-start">

                                <strong>
                                    {{ $order->address->title }}
                                </strong>

                                <div class="text-muted mt-2">

                                    {{ $order->address->receiver_name }}

                                    <br>

                                    {{ $order->address->receiver_phone }}

                                    <br>

                                    {{ $order->address->province }}
                                    -
                                    {{ $order->address->city }}

                                    <br>

                                    {{ $order->address->address }}

                                    <br>

                                    Postal Code:
                                    {{ $order->address->postal_code }}

                                </div>

                            </div>

                        @endif

                        <hr class="my-4">

                        <h5 class="fw-bold text-start mb-3">
                            Order Items
                        </h5>

                        <div class="text-start">

                            @foreach($order->items as $item)

                                <div class="item-row">

                                    <div>

                                        <strong>
                                            {{ $item->product_name }}
                                        </strong>

                                        <div class="small text-muted">

                                            @if($item->size)
                                                Size: {{ $item->size }}
                                            @endif

                                            @if($item->color)
                                                | Color: {{ $item->color }}
                                            @endif

                                            | Quantity:
                                            {{ $item->quantity }}

                                        </div>

                                    </div>

                                    <strong>
                                        {{ number_format($item->total) }}
                                    </strong>

                                </div>

                            @endforeach

                        </div>

                        <div class="mt-4">

                            <a
                                href="{{ route('dashboard') }}"
                                class="btn btn-dark"
                            >
                                Go to Dashboard
                            </a>

                            <a
                                href="{{ route('cart.index') }}"
                                class="btn btn-outline-secondary"
                            >
                                Back to Cart
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    <style>

        .success-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #198754;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 38px;
            font-weight: bold;
        }

        .order-info {
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 10px 18px;
            text-align: left;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }

        .info-row:last-child {
            border-bottom: 0;
        }

        .address-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 18px;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .item-row:last-child {
            border-bottom: 0;
        }

        @media (max-width: 576px) {

            .card-body {
                padding: 25px !important;
            }

            .info-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }

            .item-row {
                flex-direction: column;
                gap: 8px;
            }

        }

    </style>

@endsection
