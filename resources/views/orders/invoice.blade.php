@extends('shop.layouts.app')

@section('content')
    <style>
        .invoice-page {
            background: #f8f6f1;
            min-height: 75vh;
            padding: 45px 0 70px;
        }
        .invoice-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        .invoice-actions .btn {
            border-radius: 9px;
            padding: 9px 18px;
            font-size: 13px;
            font-weight: 700;
        }
        .invoice-box {
            background: #fff;
            border: 1px solid #e8e3da;
            border-radius: 18px;
            box-shadow: 0 8px 30px rgba(0,0,0,.05);
            overflow: hidden;
        }
        .invoice-header {
            padding: 30px;
            border-bottom: 1px solid #eee9e1;
        }
        .brand-title {
            color: #25352d;
            font-size: 25px;
            font-weight: 900;
            margin-bottom: 6px;
        }
        .brand-subtitle {
            color: #888;
            font-size: 13px;
        }
        .invoice-title {
            color: #25352d;
            font-size: 28px;
            font-weight: 900;
            margin-bottom: 12px;
        }
        .invoice-meta {
            color: #777;
            font-size: 13px;
            line-height: 2;
        }
        .invoice-meta strong {
            color: #25352d;
        }
        .invoice-number {
            display: inline-block;
            background: #f8f6f1;
            border-radius: 9px;
            padding: 7px 12px;
            color: #25352d;
            font-weight: 800;
        }
        .info-section {
            padding: 25px 30px;
            border-bottom: 1px solid #eee9e1;
        }
        .section-title {
            color: #25352d;
            font-size: 15px;
            font-weight: 800;
            margin-bottom: 15px;
        }
        .info-box {
            height: 100%;
            background: #f8f6f1;
            border: 1px solid #e8e3da;
            border-radius: 12px;
            padding: 17px;
        }
        .info-line {
            color: #666;
            font-size: 13px;
            line-height: 1.9;
            margin-bottom: 6px;
        }
        .info-line:last-child {
            margin-bottom: 0;
        }
        .info-line strong {
            color: #25352d;
        }
        .invoice-table-wrapper {
            padding: 25px 30px 0;
        }
        .invoice-table {
            border-color: #e8e3da;
            margin-bottom: 0;
        }
        .invoice-table th {
            background: #25352d;
            color: #fff;
            border-color: #25352d;
            font-size: 12px;
            font-weight: 700;
            padding: 13px 12px;
            white-space: nowrap;
        }
        .invoice-table td {
            color: #555;
            font-size: 13px;
            padding: 14px 12px;
            border-color: #eee9e1;
            vertical-align: middle;
        }
        .invoice-table tbody tr:hover {
            background: #fcfbf8;
        }
        .product-name {
            color: #25352d;
            font-weight: 800;
        }
        .product-code {
            color: #999;
            font-size: 11px;
            margin-top: 3px;
        }
        .variant-text {
            color: #777;
            font-size: 12px;
            line-height: 1.8;
        }
        .invoice-price {
            color: #25352d;
            font-weight: 700;
            white-space: nowrap;
        }
        .invoice-total {
            color: #6f5138;
            font-weight: 900;
            white-space: nowrap;
        }
        .summary-section {
            padding: 25px 30px;
        }
        .summary-box {
            max-width: 430px;
            margin-left: auto;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            padding: 9px 0;
            color: #666;
            font-size: 13px;
        }
        .summary-row strong {
            color: #25352d;
            white-space: nowrap;
        }
        .discount {
            color: #b24b3c !important;
        }
        .final-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-top: 10px;
            padding-top: 16px;
            border-top: 1px solid #e8e3da;
            color: #25352d;
            font-size: 16px;
            font-weight: 900;
        }
        .final-row strong {
            color: #6f5138;
            font-size: 20px;
        }
        .payment-section {
            margin: 0 30px;
            padding: 25px 0;
            border-top: 1px solid #eee9e1;
        }
        .payment-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }
        .payment-item {
            background: #f8f6f1;
            border-radius: 11px;
            padding: 13px 15px;
        }
        .payment-label {
            display: block;
            color: #888;
            font-size: 11px;
            margin-bottom: 5px;
        }
        .payment-value {
            color: #25352d;
            font-size: 13px;
            font-weight: 800;
        }
        .payment-success {
            color: #28733d;
        }
        .tracking-code {
            font-family: monospace;
            direction: ltr;
            display: inline-block;
        }
        .invoice-footer {
            margin: 0 30px;
            padding: 20px 0 25px;
            border-top: 1px solid #eee9e1;
            text-align: center;
            color: #888;
            font-size: 12px;
        }
        .print-btn {
            background: #25352d;
            border-color: #25352d;
            color: #fff;
        }
        .print-btn:hover {
            background: #6f5138;
            border-color: #6f5138;
            color: #fff;
        }
        @media (max-width: 767px) {
            .invoice-page {
                padding: 30px 0 50px;
            }
            .invoice-actions {
                align-items: flex-start;
                flex-direction: column;
            }
            .invoice-header {
                padding: 22px;
            }
            .info-section,
            .invoice-table-wrapper,
            .summary-section {
                padding-left: 20px;
                padding-right: 20px;
            }
            .payment-section,
            .invoice-footer {
                margin-left: 20px;
                margin-right: 20px;
            }
            .payment-grid {
                grid-template-columns: 1fr;
            }
            .summary-box {
                max-width: none;
            }
            .invoice-title {
                font-size: 23px;
            }
        }
        @media print {
            body {
                background: #fff !important;
            }
            nav,
            header,
            footer,
            .invoice-actions {
                display: none !important;
            }
            .invoice-page {
                background: #fff !important;
                padding: 0;
                min-height: auto;
            }
            .invoice-box {
                border: none;
                box-shadow: none;
                border-radius: 0;
            }
            .invoice-header,
            .info-section,
            .invoice-table-wrapper,
            .summary-section {
                padding-left: 15px;
                padding-right: 15px;
            }
            .payment-section,
            .invoice-footer {
                margin-left: 15px;
                margin-right: 15px;
            }
            .invoice-table th {
                background: #25352d !important;
                color: #fff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .invoice-table {
                border-color: #333 !important;
            }
            .invoice-table th,
            .invoice-table td {
                border-color: #333 !important;
            }
        }
    </style>

    <div class="invoice-page">
        <div class="container">
            <div class="invoice-actions">
                <a href="{{ route('orders.show', $order) }}"
                   class="btn btn-outline-secondary">
                    بازگشت به سفارش
                </a>

                <button type="button"
                        onclick="window.print()"
                        class="btn print-btn">
                    چاپ فاکتور
                </button>
            </div>

            <div class="invoice-box">
                {{-- Header --}}
                <div class="invoice-header">
                    <div class="row align-items-start">
                        <div class="col-md-7">
                            <div class="invoice-title">
                                فاکتور فروش
                            </div>

                            <div class="invoice-meta">
                                <div>
                                    شماره فاکتور:
                                    <span class="invoice-number">
                                    {{ $order->invoice?->invoice_number ?? '-' }}
                                </span>
                                </div>

                                <div>
                                    شماره سفارش:
                                    <strong>
                                        {{ $order->order_number }}
                                    </strong>
                                </div>

                                <div>
                                    تاریخ:
                                    <strong>
                                        {{ $order->created_at?->format('Y/m/d H:i') }}
                                    </strong>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5 text-md-end mt-4 mt-md-0">
                            <div class="brand-title">
                                فروشگاه آنلاین
                            </div>

                            <div class="brand-subtitle">
                                فاکتور سفارش مشتری
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Customer / Address --}}
                <div class="info-section">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="section-title">
                                اطلاعات مشتری
                            </div>

                            <div class="info-box">
                                <div class="info-line">
                                    <strong>نام:</strong>
                                    {{ $order->user?->name }}
                                    {{ $order->user?->lastname }}
                                </div>

                                <div class="info-line">
                                    <strong>تلفن:</strong>
                                    {{ $order->address?->receiver_phone ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="section-title">
                                آدرس ارسال
                            </div>

                            <div class="info-box">
                                @if($order->address)
                                    <div class="info-line">
                                        <strong>استان و شهر:</strong>
                                        {{ $order->address->province }}
                                        -
                                        {{ $order->address->city }}
                                    </div>

                                    <div class="info-line">
                                        <strong>آدرس:</strong>
                                        {{ $order->address->address }}
                                    </div>

                                    <div class="info-line">
                                        <strong>کد پستی:</strong>
                                        {{ $order->address->postal_code }}
                                    </div>
                                @else
                                    <div class="text-muted small">
                                        آدرس ثبت نشده است.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Products --}}
                <div class="invoice-table-wrapper">
                    <div class="table-responsive">
                        <table class="table table-bordered invoice-table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>محصول</th>
                                <th>ویژگی</th>
                                <th>قیمت</th>
                                <th>تعداد</th>
                                <th>مبلغ</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($order->items as $index => $item)
                                <tr>
                                    <td>
                                        {{ $index + 1 }}
                                    </td>

                                    <td>
                                        <div class="product-name">
                                            {{ $item->product_name }}
                                        </div>

                                        @if($item->product_code)
                                            <div class="product-code">
                                                کد:
                                                {{ $item->product_code }}
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="variant-text">
                                            @if($item->size)
                                                <div>
                                                    سایز:
                                                    {{ $item->size }}
                                                </div>
                                            @endif

                                            @if($item->color)
                                                <div>
                                                    رنگ:
                                                    {{ $item->color }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    <td>
                                    <span class="invoice-price">
                                        {{ number_format($item->price) }}
                                        تومان
                                    </span>
                                    </td>

                                    <td>
                                        {{ $item->quantity }}
                                    </td>

                                    <td>
                                    <span class="invoice-total">
                                        {{ number_format($item->total) }}
                                        تومان
                                    </span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Summary --}}
                <div class="summary-section">
                    <div class="summary-box">
                        <div class="summary-row">
                            <span>جمع محصولات</span>

                            <strong>
                                {{ number_format($order->subtotal) }}
                                تومان
                            </strong>
                        </div>

                        <div class="summary-row">
                            <span>تخفیف</span>

                            <strong class="discount">
                                -
                                {{ number_format($order->discount) }}
                                تومان
                            </strong>
                        </div>

                        <div class="summary-row">
                            <span>هزینه ارسال</span>

                            <strong>
                                {{ number_format($order->shipping_cost) }}
                                تومان
                            </strong>
                        </div>

                        <div class="summary-row">
                            <span>مالیات</span>

                            <strong>
                                {{ number_format($order->tax) }}
                                تومان
                            </strong>
                        </div>

                        <div class="final-row">
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

                {{-- Payment --}}
                <div class="payment-section">
                    <div class="section-title">
                        اطلاعات پرداخت
                    </div>

                    @if($payment)
                        <div class="payment-grid">
                            <div class="payment-item">
                            <span class="payment-label">
                                درگاه
                            </span>

                                <span class="payment-value">
                                {{ $payment->gateway ?? 'ZarinPal' }}
                            </span>
                            </div>

                            <div class="payment-item">
                            <span class="payment-label">
                                وضعیت
                            </span>

                                <span class="payment-value payment-success">
                                پرداخت موفق
                            </span>
                            </div>

                            <div class="payment-item">
                            <span class="payment-label">
                                شماره پیگیری
                            </span>

                                <span class="payment-value tracking-code">
                                {{ $payment->reference_id }}
                            </span>
                            </div>
                        </div>
                    @else
                        <div class="text-muted small">
                            پرداختی برای این سفارش ثبت نشده است.
                        </div>
                    @endif
                </div>

                {{-- Footer --}}
                <div class="invoice-footer">
                    از خرید شما سپاسگزاریم.
                </div>
            </div>
        </div>
    </div>
@endsection
