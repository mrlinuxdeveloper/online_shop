@extends('layouts.app')

@section('content')

    <div class="container py-5">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h2 class="fw-bold mb-1">
                    جزئیات سفارش
                </h2>

                <p class="text-muted mb-0">
                    شماره سفارش:
                    <strong>{{ $order->order_number }}</strong>
                </p>
            </div>

            <a
                href="{{ route('orders.index') }}"
                class="btn btn-outline-secondary"
            >
                بازگشت به سفارش‌ها
            </a>

        </div>


        {{-- Order Status --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="row g-4">

                    <div class="col-md-3">

                        <div class="text-muted small mb-1">
                            شماره سفارش
                        </div>

                        <strong>
                            {{ $order->order_number }}
                        </strong>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small mb-1">
                            تاریخ ثبت سفارش
                        </div>

                        <strong>
                            {{ $order->created_at?->format('Y/m/d H:i') }}
                        </strong>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small mb-1">
                            وضعیت سفارش
                        </div>

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

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small mb-1">
                            وضعیت پرداخت
                        </div>

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

                    </div>

                </div>

            </div>

        </div>


        {{-- Products --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <h5 class="mb-0">
                    محصولات سفارش
                </h5>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th class="px-4">
                                محصول
                            </th>

                            <th>
                                ویژگی
                            </th>

                            <th>
                                قیمت
                            </th>

                            <th>
                                تعداد
                            </th>

                            <th>
                                مبلغ
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach($order->items as $item)

                            <tr>

                                {{-- Product --}}
                                <td class="px-4">

                                    <div class="d-flex align-items-center">

                                        @if($item->product_image)

                                            <img
                                                src="{{ asset('images/products/' . $item->product_image) }}"
                                                alt="{{ $item->product_name }}"
                                                width="70"
                                                height="70"
                                                class="rounded object-fit-cover me-3"
                                            >

                                        @elseif($item->product && $item->product->images->count())

                                            <img
                                                src="{{ asset('images/products/' . $item->product->images->first()->image) }}"
                                                alt="{{ $item->product_name }}"
                                                width="70"
                                                height="70"
                                                class="rounded object-fit-cover me-3"
                                            >

                                        @endif


                                        <div>

                                            <div class="fw-semibold">
                                                {{ $item->product_name }}
                                            </div>

                                            @if($item->product_code)

                                                <small class="text-muted">
                                                    کد محصول:
                                                    {{ $item->product_code }}
                                                </small>

                                            @endif

                                        </div>

                                    </div>

                                </td>


                                {{-- Variant --}}
                                <td>

                                    @if($item->size)

                                        <div>
                                            <small class="text-muted">
                                                سایز:
                                            </small>

                                            {{ $item->size }}
                                        </div>

                                    @endif


                                    @if($item->color)

                                        <div>
                                            <small class="text-muted">
                                                رنگ:
                                            </small>

                                            {{ $item->color }}
                                        </div>

                                    @endif

                                </td>


                                {{-- Price --}}
                                <td>

                                    {{ number_format($item->price) }}

                                    <small>
                                        تومان
                                    </small>

                                </td>


                                {{-- Quantity --}}
                                <td>
                                    {{ $item->quantity }}
                                </td>


                                {{-- Total --}}
                                <td class="fw-semibold">

                                    {{ number_format($item->total) }}

                                    <small>
                                        تومان
                                    </small>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <div class="row g-4">


            {{-- Address --}}
            <div class="col-lg-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">
                            آدرس ارسال
                        </h5>

                    </div>


                    <div class="card-body">

                        @if($order->address)

                            <div class="mb-3">

                                <strong>
                                    {{ $order->address->title }}
                                </strong>

                            </div>


                            <p class="mb-2">

                                <strong>
                                    گیرنده:
                                </strong>

                                {{ $order->address->receiver_name }}

                            </p>


                            <p class="mb-2">

                                <strong>
                                    تلفن:
                                </strong>

                                {{ $order->address->receiver_phone }}

                            </p>


                            <p class="mb-2">

                                <strong>
                                    استان:
                                </strong>

                                {{ $order->address->province }}

                            </p>


                            <p class="mb-2">

                                <strong>
                                    شهر:
                                </strong>

                                {{ $order->address->city }}

                            </p>


                            <p class="mb-2">

                                <strong>
                                    کد پستی:
                                </strong>

                                {{ $order->address->postal_code }}

                            </p>


                            <p class="mb-0">

                                <strong>
                                    آدرس:
                                </strong>

                                {{ $order->address->address }}

                            </p>

                        @else

                            <p class="text-muted mb-0">
                                آدرس ثبت نشده است.
                            </p>

                        @endif

                    </div>

                </div>

            </div>


            {{-- Payment --}}
            <div class="col-lg-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">
                            اطلاعات پرداخت
                        </h5>

                    </div>


                    <div class="card-body">

                        @php
                            $paidPayment = $order->payments
                                ->where('status', 'paid')
                                ->sortByDesc('id')
                                ->first();
                        @endphp


                        <p class="mb-3">

                            <strong>
                                درگاه:
                            </strong>

                            {{ $paidPayment?->gateway ?? 'زرین‌پال' }}

                        </p>


                        <p class="mb-3">

                            <strong>
                                وضعیت:
                            </strong>

                            @if($order->payment_status === 'paid')

                                <span class="text-success">
                                پرداخت موفق
                            </span>

                            @elseif($order->payment_status === 'failed')

                                <span class="text-danger">
                                پرداخت ناموفق
                            </span>

                            @else

                                <span class="text-warning">
                                در انتظار پرداخت
                            </span>

                            @endif

                        </p>


                        @if($paidPayment?->reference_id)

                            <p class="mb-0">

                                <strong>
                                    شماره پیگیری:
                                </strong>

                                <span class="font-monospace">
                                {{ $paidPayment->reference_id }}
                            </span>

                            </p>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- Price Summary --}}
        <div class="card border-0 shadow-sm mt-4">

            <div class="card-header bg-white py-3">

                <h5 class="mb-0">
                    خلاصه سفارش
                </h5>

            </div>


            <div class="card-body">

                <div class="row justify-content-end">

                    <div class="col-md-6 col-lg-4">

                        <div class="d-flex justify-content-between mb-3">

                        <span>
                            جمع محصولات
                        </span>

                            <strong>
                                {{ number_format($order->subtotal) }}
                                تومان
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-3">

                        <span>
                            تخفیف
                        </span>

                            <strong class="text-danger">

                                -
                                {{ number_format($order->discount) }}
                                تومان

                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-3">

                        <span>
                            هزینه ارسال
                        </span>

                            <strong>
                                {{ number_format($order->shipping_cost) }}
                                تومان
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-3">

                        <span>
                            مالیات
                        </span>

                            <strong>
                                {{ number_format($order->tax) }}
                                تومان
                            </strong>

                        </div>


                        <hr>


                        <div class="d-flex justify-content-between">

                            <strong>
                                مبلغ نهایی
                            </strong>

                            <strong class="fs-5">

                                {{ number_format($order->total_price) }}
                                تومان

                            </strong>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Note --}}
        @if($order->note)

            <div class="card border-0 shadow-sm mt-4">

                <div class="card-body">

                    <strong>
                        توضیحات سفارش:
                    </strong>

                    <p class="mb-0 mt-2">
                        {{ $order->note }}
                    </p>

                </div>

            </div>

        @endif


        {{-- Invoice --}}


        @if($order->invoice)

            <div class="text-end mt-4">

                <a
                    href="{{ route('orders.invoice', $order) }}"
                    class="btn btn-outline-dark"
                >
                    مشاهده فاکتور
                </a>

            </div>

        @endif

    </div>

@endsection
