
@extends('layouts.app')

@section('content')

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">سفارش‌های من</h2>
                <p class="text-muted mb-0">
                    لیست سفارش‌های ثبت‌شده شما
                </p>
            </div>
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

        @if($orders->count())

            <div class="card shadow-sm border-0">

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                            <tr>
                                <th class="px-4">شماره سفارش</th>
                                <th>تاریخ</th>
                                <th>مبلغ</th>
                                <th>وضعیت سفارش</th>
                                <th>وضعیت پرداخت</th>
                                <th class="text-center">عملیات</th>
                            </tr>

                            </thead>

                            <tbody>

                            @foreach($orders as $order)

                                <tr>

                                    <td class="px-4">
                                        <strong>
                                            {{ $order->order_number }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $order->created_at?->format('Y/m/d H:i') }}
                                    </td>

                                    <td>
                                        {{ number_format($order->total_price) }}
                                        تومان
                                    </td>

                                    <td>

                                        @php
                                            $statusLabels = [
                                                'pending' => 'در انتظار',
                                                'processing' => 'در حال پردازش',
                                                'shipped' => 'ارسال شده',
                                                'delivered' => 'تحویل شده',
                                                'cancelled' => 'لغو شده',
                                            ];

                                            $statusClasses = [
                                                'pending' => 'bg-warning text-dark',
                                                'processing' => 'bg-info text-dark',
                                                'shipped' => 'bg-primary',
                                                'delivered' => 'bg-success',
                                                'cancelled' => 'bg-danger',
                                            ];
                                        @endphp

                                        <span class="badge {{ $statusClasses[$order->status] ?? 'bg-secondary' }}">
                                        {{ $statusLabels[$order->status] ?? $order->status }}
                                    </span>

                                    </td>

                                    <td>

                                        @php
                                            $paymentLabels = [
                                                'pending' => 'در انتظار پرداخت',
                                                'paid' => 'پرداخت شده',
                                                'failed' => 'ناموفق',
                                                'refunded' => 'مسترد شده',
                                            ];

                                            $paymentClasses = [
                                                'pending' => 'bg-warning text-dark',
                                                'paid' => 'bg-success',
                                                'failed' => 'bg-danger',
                                                'refunded' => 'bg-secondary',
                                            ];
                                        @endphp

                                        <span class="badge {{ $paymentClasses[$order->payment_status] ?? 'bg-secondary' }}">
                                        {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                                    </span>

                                    </td>

                                    <td class="text-center">

                                        <a
                                            href="{{ route('orders.show', $order) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            مشاهده سفارش
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

            <div class="mt-4">
                {{ $orders->links() }}
            </div>

        @else

            <div class="card border-0 shadow-sm">

                <div class="card-body text-center py-5">

                    <h4 class="mb-3">
                        هنوز سفارشی ثبت نکرده‌اید
                    </h4>

                    <p class="text-muted mb-4">
                        می‌توانید از فروشگاه محصولات موردنظر خود را انتخاب کنید.
                    </p>

                    <a
                        href="{{ route('shop.products.index') }}"
                        class="btn btn-primary"
                    >
                        مشاهده محصولات
                    </a>

                </div>

            </div>

        @endif

    </div>

@endsection
