@extends('shop.layouts.app')

@section('content')
    <style>
        .order-page {
            background: #f8f6f1;
            min-height: 75vh;
            padding: 50px 0 80px;
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
        }
        .page-title {
            color: #25352d;
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 7px;
        }
        .page-subtitle {
            color: #777;
            font-size: 13px;
            margin: 0;
        }
        .page-subtitle strong {
            color: #25352d;
        }
        .shop-card {
            background: #fff;
            border: 1px solid #e8e3da;
            border-radius: 17px;
            box-shadow: 0 7px 28px rgba(0,0,0,.04);
            overflow: hidden;
        }
        .card-header-shop {
            background: #fff;
            border-bottom: 1px solid #eee9e1;
            padding: 18px 22px;
        }
        .card-title {
            color: #25352d;
            font-size: 16px;
            font-weight: 800;
            margin: 0;
        }
        .card-body-shop {
            padding: 22px;
        }
        .order-summary {
            padding: 20px 22px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }
        .summary-item {
            background: #f8f6f1;
            border-radius: 11px;
            padding: 14px;
        }
        .summary-label {
            display: block;
            color: #888;
            font-size: 12px;
            margin-bottom: 6px;
        }
        .summary-value {
            color: #25352d;
            font-size: 14px;
            font-weight: 800;
        }
        .status-badge {
            display: inline-block;
            border-radius: 20px;
            padding: 5px 11px;
            font-size: 11px;
            font-weight: 700;
        }
        .status-pending {
            background: #fff3d8;
            color: #8a6518;
        }
        .status-processing {
            background: #e5eef7;
            color: #315d86;
        }
        .status-shipped {
            background: #eee7f8;
            color: #66498a;
        }
        .status-delivered {
            background: #e2f3eb;
            color: #28745b;
        }
        .status-cancelled {
            background: #fbe5e2;
            color: #9a3c31;
        }
        .status-paid {
            background: #e2f3e8;
            color: #28733d;
        }
        .status-failed {
            background: #fbe5e2;
            color: #9a3c31;
        }
        .status-refunded {
            background: #eee;
            color: #666;
        }
        .status-default {
            background: #eee;
            color: #666;
        }
        .products-table {
            margin: 0;
        }
        .products-table thead th {
            background: #f8f6f1;
            color: #777;
            border-bottom: 1px solid #e8e3da;
            font-size: 12px;
            font-weight: 700;
            padding: 14px 16px;
            white-space: nowrap;
        }
        .products-table tbody td {
            padding: 17px 16px;
            border-color: #eee9e1;
            vertical-align: middle;
        }
        .product-cell {
            display: flex;
            align-items: center;
            gap: 13px;
            min-width: 230px;
        }
        .product-image {
            width: 65px;
            height: 65px;
            border-radius: 10px;
            object-fit: cover;
            background: #f3f0ea;
            flex-shrink: 0;
        }
        .product-name {
            color: #25352d;
            font-size: 14px;
            font-weight: 800;
            margin-bottom: 4px;
        }
        .product-code {
            color: #999;
            font-size: 11px;
        }
        .variant-text {
            color: #666;
            font-size: 12px;
            line-height: 1.9;
        }
        .price-text {
            color: #25352d;
            font-weight: 700;
            white-space: nowrap;
        }
        .total-text {
            color: #6f5138;
            font-weight: 800;
            white-space: nowrap;
        }
        .quantity-badge {
            display: inline-flex;
            min-width: 30px;
            height: 30px;
            align-items: center;
            justify-content: center;
            background: #f3f0ea;
            border-radius: 8px;
            color: #25352d;
            font-weight: 700;
            font-size: 13px;
        }
        .address-box {
            background: #f8f6f1;
            border: 1px solid #e8e3da;
            border-radius: 12px;
            padding: 18px;
        }
        .address-title {
            color: #25352d;
            font-weight: 800;
            margin-bottom: 13px;
        }
        .address-line {
            color: #666;
            font-size: 13px;
            line-height: 1.9;
            margin-bottom: 7px;
        }
        .address-line:last-child {
            margin-bottom: 0;
        }
        .address-line strong {
            color: #25352d;
        }
        .payment-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            padding: 12px 0;
            border-bottom: 1px solid #eee9e1;
            font-size: 13px;
        }
        .payment-row:last-child {
            border-bottom: 0;
        }
        .payment-label {
            color: #777;
        }
        .payment-value {
            color: #25352d;
            font-weight: 700;
            text-align: right;
        }
        .tracking-code {
            font-family: monospace;
            direction: ltr;
            display: inline-block;
        }
        .price-summary {
            max-width: 440px;
            margin-left: auto;
        }
        .price-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            padding: 10px 0;
            color: #666;
            font-size: 13px;
        }
        .price-row strong {
            color: #25352d;
            white-space: nowrap;
        }
        .discount-value {
            color: #b24b3c !important;
        }
        .final-price {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            border-top: 1px solid #e8e3da;
            margin-top: 10px;
            padding-top: 17px;
            color: #25352d;
            font-size: 16px;
            font-weight: 800;
        }
        .final-price strong {
            color: #6f5138;
            font-size: 20px;
        }
        .note-box {
            background: #fffaf0;
            border: 1px solid #eee0c5;
            border-radius: 12px;
            padding: 17px;
            color: #666;
            line-height: 1.9;
            font-size: 13px;
        }
        .actions {
            display: flex;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 9px;
            margin-top: 25px;
        }
        .actions .btn {
            border-radius: 9px;
            padding: 9px 18px;
            font-size: 13px;
            font-weight: 700;
        }
        .btn-shop-dark {
            background: #25352d;
            border-color: #25352d;
            color: #fff;
        }
        .btn-shop-dark:hover {
            background: #6f5138;
            border-color: #6f5138;
            color: #fff;
        }
        .btn-pay {
            background: #6f5138;
            border-color: #6f5138;
            color: #fff;
        }
        .btn-pay:hover {
            background: #57402d;
            border-color: #57402d;
            color: #fff;
        }
        @media (max-width: 991px) {
            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 767px) {
            .order-page {
                padding: 35px 0 55px;
            }
            .page-header {
                align-items: flex-start;
                flex-direction: column;
            }
            .page-title {
                font-size: 23px;
            }
            .summary-grid {
                grid-template-columns: 1fr;
            }
            .order-summary,
            .card-body-shop {
                padding: 18px;
            }
            .product-cell {
                min-width: 200px;
            }
            .price-summary {
                max-width: none;
            }
            .actions {
                justify-content: flex-start;
            }
        }
    </style>

    <div class="order-page">
        <div class="container">
            <div class="page-header">
                <div>
                    <h1 class="page-title">
                        جزئیات سفارش
                    </h1>

                    <p class="page-subtitle">
                        شماره سفارش:
                        <strong>{{ $order->order_number }}</strong>
                    </p>
                </div>

                <a href="{{ route('orders.index') }}"
                   class="btn btn-outline-secondary">
                    بازگشت به سفارش‌ها
                </a>
            </div>

            {{-- Order Summary --}}
            <div class="shop-card mb-4">
                <div class="card-header-shop">
                    <h2 class="card-title">
                        وضعیت سفارش
                    </h2>
                </div>

                <div class="order-summary">
                    <div class="summary-grid">
                        <div class="summary-item">
                        <span class="summary-label">
                            شماره سفارش
                        </span>

                            <span class="summary-value">
                            {{ $order->order_number }}
                        </span>
                        </div>

                        <div class="summary-item">
                        <span class="summary-label">
                            تاریخ ثبت سفارش
                        </span>

                            <span class="summary-value">
                            {{ $order->created_at?->format('Y/m/d H:i') }}
                        </span>
                        </div>

                        <div class="summary-item">
                        <span class="summary-label">
                            وضعیت سفارش
                        </span>

                            @php
                                $statusLabels = [
                                    'pending' => 'در انتظار',
                                    'processing' => 'در حال پردازش',
                                    'shipped' => 'ارسال شده',
                                    'delivered' => 'تحویل شده',
                                    'cancelled' => 'لغو شده',
                                ];
                                $statusClasses = [
                                    'pending' => 'status-pending',
                                    'processing' => 'status-processing',
                                    'shipped' => 'status-shipped',
                                    'delivered' => 'status-delivered',
                                    'cancelled' => 'status-cancelled',
                                ];
                            @endphp

                            <span class="status-badge {{ $statusClasses[$order->status] ?? 'status-default' }}">
                            {{ $statusLabels[$order->status] ?? $order->status }}
                        </span>
                        </div>

                        <div class="summary-item">
                        <span class="summary-label">
                            وضعیت پرداخت
                        </span>

                            @php
                                $paymentLabels = [
                                    'pending' => 'در انتظار پرداخت',
                                    'paid' => 'پرداخت شده',
                                    'failed' => 'ناموفق',
                                    'refunded' => 'مسترد شده',
                                ];
                                $paymentClasses = [
                                    'pending' => 'status-pending',
                                    'paid' => 'status-paid',
                                    'failed' => 'status-failed',
                                    'refunded' => 'status-refunded',
                                ];
                            @endphp

                            <span class="status-badge {{ $paymentClasses[$order->payment_status] ?? 'status-default' }}">
                            {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                        </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Products --}}
            <div class="shop-card mb-4">
                <div class="card-header-shop">
                    <h2 class="card-title">
                        محصولات سفارش
                    </h2>
                </div>

                <div class="table-responsive">
                    <table class="table products-table align-middle mb-0">
                        <thead>
                        <tr>
                            <th>محصول</th>
                            <th>ویژگی</th>
                            <th>قیمت</th>
                            <th>تعداد</th>
                            <th>مبلغ</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="product-cell">
                                        @if($item->product_image)
                                            <img src="{{ asset('images/products/' . $item->product_image) }}"
                                                 alt="{{ $item->product_name }}"
                                                 class="product-image">
                                        @elseif($item->product && $item->product->images->count())
                                            <img src="{{ asset('images/products/' . $item->product->images->first()->image) }}"
                                                 alt="{{ $item->product_name }}"
                                                 class="product-image">
                                        @else
                                            <div class="product-image d-flex align-items-center justify-content-center">
                                                🛍
                                            </div>
                                        @endif

                                        <div>
                                            <div class="product-name">
                                                {{ $item->product_name }}
                                            </div>

                                            @if($item->product_code)
                                                <div class="product-code">
                                                    کد محصول:
                                                    {{ $item->product_code }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="variant-text">
                                        @if($item->size)
                                            <div>
                                                سایز:
                                                <strong>{{ $item->size }}</strong>
                                            </div>
                                        @endif

                                        @if($item->color)
                                            <div>
                                                رنگ:
                                                <strong>{{ $item->color }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <td>
                                <span class="price-text">
                                    {{ number_format($item->price) }}
                                    <small>تومان</small>
                                </span>
                                </td>

                                <td>
                                <span class="quantity-badge">
                                    {{ $item->quantity }}
                                </span>
                                </td>

                                <td>
                                <span class="total-text">
                                    {{ number_format($item->total) }}
                                    <small>تومان</small>
                                </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row g-4">
                {{-- Address --}}
                <div class="col-lg-6">
                    <div class="shop-card h-100">
                        <div class="card-header-shop">
                            <h2 class="card-title">
                                آدرس ارسال
                            </h2>
                        </div>

                        <div class="card-body-shop">
                            @if($order->address)
                                <div class="address-box">
                                    <div class="address-title">
                                        {{ $order->address->title }}
                                    </div>

                                    <div class="address-line">
                                        <strong>گیرنده:</strong>
                                        {{ $order->address->receiver_name }}
                                    </div>

                                    <div class="address-line">
                                        <strong>تلفن:</strong>
                                        {{ $order->address->receiver_phone }}
                                    </div>

                                    <div class="address-line">
                                        <strong>استان:</strong>
                                        {{ $order->address->province }}
                                    </div>

                                    <div class="address-line">
                                        <strong>شهر:</strong>
                                        {{ $order->address->city }}
                                    </div>

                                    <div class="address-line">
                                        <strong>کد پستی:</strong>
                                        {{ $order->address->postal_code }}
                                    </div>

                                    <div class="address-line">
                                        <strong>آدرس:</strong>
                                        {{ $order->address->address }}
                                    </div>
                                </div>
                            @else
                                <div class="text-muted">
                                    آدرس ثبت نشده است.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Payment --}}
                <div class="col-lg-6">
                    <div class="shop-card h-100">
                        <div class="card-header-shop">
                            <h2 class="card-title">
                                اطلاعات پرداخت
                            </h2>
                        </div>

                        <div class="card-body-shop">
                            @php
                                $paidPayment = $order->payments
                                    ->where('status', 'paid')
                                    ->sortByDesc('id')
                                    ->first();
                            @endphp

                            <div class="payment-row">
                            <span class="payment-label">
                                درگاه
                            </span>

                                <span class="payment-value">
                                {{ $paidPayment?->gateway ?? 'زرین‌پال' }}
                            </span>
                            </div>

                            <div class="payment-row">
                            <span class="payment-label">
                                وضعیت
                            </span>

                                <span class="payment-value">
                                @if($order->payment_status === 'paid')
                                        <span class="status-badge status-paid">
                                        پرداخت موفق
                                    </span>
                                    @elseif($order->payment_status === 'failed')
                                        <span class="status-badge status-failed">
                                        پرداخت ناموفق
                                    </span>
                                    @else
                                        <span class="status-badge status-pending">
                                        در انتظار پرداخت
                                    </span>
                                    @endif
                            </span>
                            </div>

                            @if($paidPayment?->reference_id)
                                <div class="payment-row">
                                <span class="payment-label">
                                    شماره پیگیری
                                </span>

                                    <span class="payment-value tracking-code">
                                    {{ $paidPayment->reference_id }}
                                </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Price Summary --}}
            <div class="shop-card mt-4">
                <div class="card-header-shop">
                    <h2 class="card-title">
                        خلاصه سفارش
                    </h2>
                </div>

                <div class="card-body-shop">
                    <div class="price-summary">
                        <div class="price-row">
                            <span>جمع محصولات</span>

                            <strong>
                                {{ number_format($order->subtotal) }}
                                تومان
                            </strong>
                        </div>

                        <div class="price-row">
                            <span>تخفیف</span>

                            <strong class="discount-value">
                                -
                                {{ number_format($order->discount) }}
                                تومان
                            </strong>
                        </div>

                        <div class="price-row">
                            <span>هزینه ارسال</span>

                            <strong>
                                {{ number_format($order->shipping_cost) }}
                                تومان
                            </strong>
                        </div>

                        <div class="price-row">
                            <span>مالیات</span>

                            <strong>
                                {{ number_format($order->tax) }}
                                تومان
                            </strong>
                        </div>

                        <div class="final-price">
                        <span>
                            مبلغ نهایی
                        </span>

                            <strong>
                                {{ number_format($order->total_price) }}
                                تومان
                            </strong>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Note --}}
            @if($order->note)
                <div class="shop-card mt-4">
                    <div class="card-header-shop">
                        <h2 class="card-title">
                            توضیحات سفارش
                        </h2>
                    </div>

                    <div class="card-body-shop">
                        <div class="note-box">
                            {{ $order->note }}
                        </div>
                    </div>
                </div>
            @endif

            {{-- Actions --}}
            <div class="actions">
                <a href="{{ route('orders.index') }}"
                   class="btn btn-outline-secondary">
                    بازگشت به سفارش‌ها
                </a>

                @if($order->payment_status !== 'paid')
                    <a href="{{ route('payment.start', $order) }}"
                       class="btn btn-pay">
                        پرداخت سفارش
                    </a>
                @endif

                @if($order->invoice)
                    <a href="{{ route('orders.invoice', $order) }}"
                       class="btn btn-shop-dark">
                        مشاهده فاکتور
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection
