@extends('layouts.app')

@section('content')
    <style>
        .success-page {
            padding: 60px 0 80px;
            background: #f8f6f1;
            min-height: 75vh;
        }
        .success-card {
            background: #fff;
            border: 1px solid #e8e3da;
            border-radius: 20px;
            box-shadow: 0 10px 35px rgba(0,0,0,.05);
            overflow: hidden;
        }
        .success-header {
            padding: 40px 30px 30px;
            text-align: center;
            border-bottom: 1px solid #eee9e1;
        }
        .success-icon {
            width: 78px;
            height: 78px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: #25352d;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 42px;
            font-weight: 800;
            box-shadow: 0 8px 25px rgba(37,53,45,.2);
        }
        .success-title {
            color: #25352d;
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 8px;
        }
        .success-text {
            color: #777;
            margin-bottom: 0;
        }
        .section {
            padding: 25px 30px;
            border-bottom: 1px solid #eee9e1;
        }
        .section:last-child {
            border-bottom: 0;
        }
        .section-title {
            color: #25352d;
            font-size: 17px;
            font-weight: 800;
            margin-bottom: 16px;
        }
        .order-info {
            border: 1px solid #e8e3da;
            border-radius: 14px;
            overflow: hidden;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            padding: 14px 16px;
            border-bottom: 1px solid #eee9e1;
        }
        .info-row:last-child {
            border-bottom: 0;
        }
        .info-label {
            color: #777;
            font-size: 13px;
        }
        .info-value {
            color: #25352d;
            font-weight: 700;
            text-align: right;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 11px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }
        .status-paid {
            background: #e5f4e9;
            color: #28733d;
        }
        .status-pending {
            background: #fff3d8;
            color: #8a6518;
        }
        .status-failed {
            background: #fbe6e3;
            color: #9b382b;
        }
        .status-default {
            background: #eee;
            color: #666;
        }
        .address-box {
            background: #f8f6f1;
            border: 1px solid #e8e3da;
            border-radius: 14px;
            padding: 18px;
        }
        .address-title {
            color: #25352d;
            font-weight: 800;
            margin-bottom: 8px;
        }
        .address-info {
            color: #777;
            font-size: 13px;
            line-height: 1.9;
        }
        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            padding: 15px 0;
            border-bottom: 1px solid #eee9e1;
        }
        .item-row:last-child {
            border-bottom: 0;
        }
        .item-name {
            color: #25352d;
            font-weight: 700;
        }
        .item-meta {
            color: #888;
            font-size: 12px;
            margin-top: 4px;
        }
        .item-price {
            color: #25352d;
            font-weight: 800;
            white-space: nowrap;
        }
        .success-actions {
            padding: 25px 30px 30px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        .success-actions .btn {
            border-radius: 10px;
            padding: 11px 22px;
            font-weight: 700;
        }
        .btn-shop-dark {
            background: #25352d;
            color: #fff;
            border: 1px solid #25352d;
        }
        .btn-shop-dark:hover {
            background: #6f5138;
            border-color: #6f5138;
            color: #fff;
        }
        .btn-pay {
            background: #6f5138;
            color: #fff;
            border: 1px solid #6f5138;
        }
        .btn-pay:hover {
            background: #57402d;
            border-color: #57402d;
            color: #fff;
        }
        @media (max-width: 576px) {
            .success-page {
                padding: 35px 0 50px;
            }
            .success-header {
                padding: 30px 20px 25px;
            }
            .section {
                padding: 20px;
            }
            .success-actions {
                padding: 20px;
            }
            .success-title {
                font-size: 23px;
            }
            .info-row {
                align-items: flex-start;
                flex-direction: column;
                gap: 5px;
            }
            .info-value {
                text-align: left;
            }
            .item-row {
                align-items: flex-start;
                flex-direction: column;
                gap: 7px;
            }
        }
    </style>

    <div class="success-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="success-card">
                        <div class="success-header">
                            <div class="success-icon">
                                ✓
                            </div>

                            <h1 class="success-title">
                                سفارش با موفقیت ثبت شد
                            </h1>

                            <p class="success-text">
                                سفارش شما با موفقیت ثبت شد.
                            </p>
                        </div>

                        <div class="section">
                            <h2 class="section-title">
                                اطلاعات سفارش
                            </h2>

                            <div class="order-info">
                                <div class="info-row">
                                <span class="info-label">
                                    شمارهٔ سفارش
                                </span>

                                    <strong class="info-value">
                                        {{ $order->order_number }}
                                    </strong>
                                </div>

                                <div class="info-row">
                                <span class="info-label">
                                    شمارهٔ فاکتور
                                </span>

                                    <strong class="info-value">
                                        {{ $order->invoice?->invoice_number ?? '-' }}
                                    </strong>
                                </div>

                                <div class="info-row">
                                <span class="info-label">
                                    وضعیت سفارش
                                </span>

                                    <span class="status-badge status-default">
                                    {{ ucfirst($order->status) }}
                                </span>
                                </div>

                                <div class="info-row">
                                <span class="info-label">
                                    وضعیت پرداخت
                                </span>

                                    @if($order->payment_status === 'paid')
                                        <span class="status-badge status-paid">
                                        پرداخت‌شده
                                    </span>
                                    @elseif($order->payment_status === 'pending')
                                        <span class="status-badge status-pending">
                                        در انتظار
                                    </span>
                                    @elseif($order->payment_status === 'failed')
                                        <span class="status-badge status-failed">
                                        ناموفق
                                    </span>
                                    @else
                                        <span class="status-badge status-default">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                    @endif
                                </div>

                                <div class="info-row">
                                <span class="info-label">
                                    مبلغ نهایی قیمت
                                </span>

                                    <strong class="info-value">
                                        {{ number_format($order->total_price) }} تومان
                                    </strong>
                                </div>
                            </div>
                        </div>

                        @if($order->address)
                            <div class="section">
                                <h2 class="section-title">
                                    نشانی ارسال
                                </h2>

                                <div class="address-box">
                                    <div class="address-title">
                                        {{ $order->address->title }}
                                    </div>

                                    <div class="address-info">
                                        <div>
                                            {{ $order->address->receiver_name }}
                                        </div>

                                        <div>
                                            {{ $order->address->receiver_phone }}
                                        </div>

                                        <div>
                                            {{ $order->address->province }}
                                            -
                                            {{ $order->address->city }}
                                        </div>

                                        <div>
                                            {{ $order->address->address }}
                                        </div>

                                        <div>
                                            کد پستی:
                                            {{ $order->address->postal_code }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="section">
                            <h2 class="section-title">
                                اقلام سفارش
                            </h2>

                            <div>
                                @foreach($order->items as $item)
                                    <div class="item-row">
                                        <div>
                                            <div class="item-name">
                                                {{ $item->product_name }}
                                            </div>

                                            <div class="item-meta">
                                                @if($item->size)
                                                    سایز: {{ $item->size }}
                                                @endif

                                                @if($item->color)
                                                    | رنگ: {{ $item->color }}
                                                @endif

                                                | تعداد: {{ $item->quantity }}
                                            </div>
                                        </div>

                                        <strong class="item-price">
                                            {{ number_format($item->total) }}
                                        </strong>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="success-actions">
                            <a href="{{ route('dashboard') }}"
                               class="btn btn-shop-dark">
                                رفتن به پیشخوان
                            </a>

                            <a href="{{ route('cart.index') }}"
                               class="btn btn-outline-secondary">
                                بازگشت به سبد خرید
                            </a>

                            @if($order->payment_status !== 'paid')
                                <a href="{{ route('payment.start', $order) }}"
                                   class="btn btn-pay">
                                    پرداخت
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
