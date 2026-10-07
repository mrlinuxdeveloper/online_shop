@extends('shop.layouts.app')

@section('content')
    <style>
        .orders-page {
            background: #f8f6f1;
            min-height: 75vh;
            padding: 55px 0 80px;
        }
        .page-title {
            color: #25352d;
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 8px;
        }
        .page-subtitle {
            color: #777;
            margin-bottom: 30px;
        }
        .orders-card {
            background: #fff;
            border: 1px solid #e8e3da;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0,0,0,.04);
        }
        .order-row {
            padding: 22px 25px;
            border-bottom: 1px solid #eee9e1;
            transition: .2s;
        }
        .order-row:last-child {
            border-bottom: 0;
        }
        .order-row:hover {
            background: #fcfbf8;
        }
        .order-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 18px;
        }
        .order-number {
            color: #25352d;
            font-weight: 800;
            font-size: 16px;
        }
        .order-date {
            color: #888;
            font-size: 13px;
            margin-top: 5px;
        }
        .order-info {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }
        .info-box {
            background: #f8f6f1;
            border-radius: 10px;
            padding: 12px 15px;
        }
        .info-label {
            display: block;
            color: #888;
            font-size: 12px;
            margin-bottom: 5px;
        }
        .info-value {
            color: #25352d;
            font-weight: 700;
            font-size: 14px;
        }
        .status {
            display: inline-block;
            padding: 5px 11px;
            border-radius: 20px;
            font-size: 11px;
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
        .status-processing {
            background: #e4eef8;
            color: #315d86;
        }
        .status-shipped {
            background: #eee8f8;
            color: #65478a;
        }
        .status-delivered {
            background: #dff3ed;
            color: #287563;
        }
        .status-default {
            background: #eee;
            color: #666;
        }
        .order-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-top: 18px;
        }
        .order-actions .btn {
            border-radius: 9px;
            font-size: 13px;
            font-weight: 700;
            padding: 8px 15px;
        }
        .btn-details {
            background: #25352d;
            border-color: #25352d;
            color: #fff;
        }
        .btn-details:hover {
            background: #6f5138;
            border-color: #6f5138;
            color: #fff;
        }
        .empty-orders {
            background: #fff;
            border: 1px solid #e8e3da;
            border-radius: 18px;
            padding: 60px 20px;
            text-align: center;
        }
        .empty-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 18px;
            border-radius: 50%;
            background: #f1eee8;
            color: #25352d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
        }
        .empty-orders h3 {
            color: #25352d;
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 8px;
        }
        .empty-orders p {
            color: #888;
            margin-bottom: 20px;
        }
        .pagination-wrapper {
            padding: 25px;
            border-top: 1px solid #eee9e1;
        }
        @media (max-width: 768px) {
            .orders-page {
                padding: 35px 0 55px;
            }
            .page-title {
                font-size: 23px;
            }
            .order-top {
                align-items: flex-start;
                flex-direction: column;
            }
            .order-info {
                grid-template-columns: 1fr;
            }
            .order-actions {
                justify-content: flex-start;
                flex-wrap: wrap;
            }
        }
    </style>

    <div class="orders-page">
        <div class="container">
            <div class="mb-4">
                <h1 class="page-title">
                    My Orders
                </h1>

                <p class="page-subtitle">
                    View and manage your orders.
                </p>
            </div>

            @if($orders->count())
                <div class="orders-card">
                    @foreach($orders as $order)
                        <div class="order-row">
                            <div class="order-top">
                                <div>
                                    <div class="order-number">
                                        Order #{{ $order->order_number }}
                                    </div>

                                    <div class="order-date">
                                        {{ $order->created_at->format('Y-m-d H:i') }}
                                    </div>
                                </div>

                                @if($order->payment_status === 'paid')
                                    <span class="status status-paid">
                                    Paid
                                </span>
                                @elseif($order->payment_status === 'pending')
                                    <span class="status status-pending">
                                    Payment Pending
                                </span>
                                @elseif($order->payment_status === 'failed')
                                    <span class="status status-failed">
                                    Payment Failed
                                </span>
                                @else
                                    <span class="status status-default">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                                @endif
                            </div>

                            <div class="order-info">
                                <div class="info-box">
                                <span class="info-label">
                                    Order Status
                                </span>

                                    @switch($order->status)
                                        @case('processing')
                                            <span class="status status-processing">
                                            Processing
                                        </span>
                                            @break

                                        @case('shipped')
                                            <span class="status status-shipped">
                                            Shipped
                                        </span>
                                            @break

                                        @case('delivered')
                                            <span class="status status-delivered">
                                            Delivered
                                        </span>
                                            @break

                                        @case('pending')
                                            <span class="status status-pending">
                                            Pending
                                        </span>
                                            @break

                                        @default
                                            <span class="status status-default">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    @endswitch
                                </div>

                                <div class="info-box">
                                <span class="info-label">
                                    Items
                                </span>

                                    <span class="info-value">
                                    {{ $order->items->count() }}
                                </span>
                                </div>

                                <div class="info-box">
                                <span class="info-label">
                                    Total
                                </span>

                                    <span class="info-value">
                                    {{ number_format($order->total_price) }} Toman
                                </span>
                                </div>
                            </div>

                            <div class="order-actions">
                                <a href="{{ route('orders.show', $order) }}"
                                   class="btn btn-details">
                                    View Details
                                </a>

                                @if($order->invoice)
                                    <a href="{{ route('orders.invoice', $order) }}"
                                       class="btn btn-outline-secondary">
                                        Invoice
                                    </a>
                                @endif

                                @if($order->payment_status !== 'paid')
                                    <a href="{{ route('payment.start', $order) }}"
                                       class="btn btn-outline-success">
                                        Pay Now
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    @if($orders->hasPages())
                        <div class="pagination-wrapper">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            @else
                <div class="empty-orders">
                    <div class="empty-icon">
                        🛍
                    </div>

                    <h3>
                        No Orders Yet
                    </h3>

                    <p>
                        You haven't placed any orders yet.
                    </p>

                    <a href="{{ route('shop.products.index') }}"
                       class="btn btn-details">
                        Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
