
@extends('layouts.app')

@section('content')

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-md-8 col-lg-7">

                <div class="card border-0 shadow-sm">

                    <div class="card-body text-center p-5">

                        {{-- آیکون --}}
                        <div
                            class="mx-auto mb-4 d-flex align-items-center justify-content-center"
                            style="
                            width: 80px;
                            height: 80px;
                            border-radius: 50%;
                            background: #f8d7da;
                            color: #842029;
                            font-size: 40px;
                        "
                        >
                            ×
                        </div>

                        {{-- عنوان --}}
                        <h2 class="fw-bold mb-3">
                            پرداخت ناموفق بود
                        </h2>

                        <p class="text-muted mb-4">
                            متأسفانه پرداخت سفارش شما با موفقیت انجام نشد.
                            می‌توانید دوباره برای پرداخت سفارش تلاش کنید.
                        </p>

                        {{-- اطلاعات سفارش --}}
                        <div class="bg-light rounded p-4 mb-4 text-start">

                            <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">
                                شماره سفارش
                            </span>

                                <strong>
                                    {{ $order->order_number }}
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between">

                            <span class="text-muted">
                                مبلغ سفارش
                            </span>

                                <strong>
                                    {{ number_format($order->total_price) }}
                                    تومان
                                </strong>

                            </div>

                        </div>

                        {{-- پیام خطا --}}
                        @if(session('error'))

                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>

                        @endif

                        {{-- دکمه‌ها --}}
                        <div
                            class="d-flex flex-column flex-sm-row gap-2 justify-content-center"
                        >

                            <a
                                href="{{ route('payment.retry', $order) }}"
                                class="btn btn-dark px-4"
                            >
                                تلاش مجدد برای پرداخت
                            </a>

                            <a
                                href="{{ route('orders.show', $order) }}"
                                class="btn btn-outline-secondary px-4"
                            >
                                مشاهده سفارش
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
