@extends('shop.layouts.app')

@section('content')
    <style>
        .failed-page {
            min-height: 75vh;
            background: #f8f6f1;
            padding: 55px 0 75px;
        }
        .failed-card {
            max-width: 680px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #e8e3da;
            border-radius: 20px;
            box-shadow: 0 10px 35px rgba(0,0,0,.05);
            overflow: hidden;
        }
        .failed-body {
            padding: 45px 35px;
            text-align: center;
        }
        .failed-icon {
            width: 82px;
            height: 82px;
            margin: 0 auto 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #f9e4e2;
            color: #a94442;
            font-size: 42px;
            font-weight: 300;
            border: 7px solid #fdf2f1;
        }
        .failed-title {
            color: #25352d;
            font-size: 26px;
            font-weight: 900;
            margin-bottom: 12px;
        }
        .failed-description {
            max-width: 520px;
            margin: 0 auto 28px;
            color: #777;
            font-size: 14px;
            line-height: 2;
        }
        .order-info {
            background: #f8f6f1;
            border: 1px solid #e8e3da;
            border-radius: 13px;
            padding: 18px 20px;
            margin-bottom: 20px;
            text-align: right;
        }
        .order-info-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 8px 0;
            font-size: 13px;
        }
        .order-info-row + .order-info-row {
            border-top: 1px solid #e8e3da;
        }
        .order-info-label {
            color: #888;
        }
        .order-info-value {
            color: #25352d;
            font-weight: 800;
        }
        .order-price {
            color: #6f5138;
            font-size: 15px;
            font-weight: 900;
        }
        .error-message {
            border: 1px solid #f0caca;
            background: #fff5f5;
            color: #9b3d3d;
            border-radius: 11px;
            padding: 12px 15px;
            margin-bottom: 22px;
            font-size: 13px;
        }
        .failed-actions {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .failed-actions .btn {
            min-width: 175px;
            border-radius: 9px;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 800;
        }
        .retry-btn {
            background: #25352d;
            border-color: #25352d;
            color: #fff;
        }
        .retry-btn:hover {
            background: #6f5138;
            border-color: #6f5138;
            color: #fff;
        }
        @media (max-width: 576px) {
            .failed-page {
                padding: 35px 15px 55px;
            }
            .failed-body {
                padding: 35px 20px;
            }
            .failed-title {
                font-size: 22px;
            }
            .order-info-row {
                align-items: flex-start;
            }
            .failed-actions {
                flex-direction: column;
            }
            .failed-actions .btn {
                width: 100%;
            }
        }
    </style>

    <div class="failed-page">
        <div class="container">
            <div class="failed-card">
                <div class="failed-body">

                    {{-- Icon --}}
                    <div class="failed-icon">
                        ×
                    </div>

                    {{-- Title --}}
                    <h2 class="failed-title">
                        پرداخت ناموفق بود
                    </h2>

                    <p class="failed-description">
                        متأسفانه پرداخت سفارش شما با موفقیت انجام نشد.
                        می‌توانید دوباره برای پرداخت سفارش تلاش کنید.
                    </p>

                    {{-- Order Information --}}
                    <div class="order-info">
                        <div class="order-info-row">
                        <span class="order-info-label">
                            شماره سفارش
                        </span>

                            <strong class="order-info-value">
                                {{ $order->order_number }}
                            </strong>
                        </div>

                        <div class="order-info-row">
                        <span class="order-info-label">
                            مبلغ سفارش
                        </span>

                            <strong class="order-price">
                                {{ number_format($order->total_price) }}
                                تومان
                            </strong>
                        </div>
                    </div>

                    {{-- Error Message --}}
                    @if(session('error'))
                        <div class="error-message">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="failed-actions">
                        <a href="{{ route('payment.retry', $order) }}"
                           class="btn retry-btn">
                            تلاش مجدد برای پرداخت
                        </a>

                        <a href="{{ route('orders.show', $order) }}"
                           class="btn btn-outline-secondary">
                            مشاهده سفارش
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
