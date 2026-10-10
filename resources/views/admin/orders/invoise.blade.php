@extends('admin.layouts.AdminMasterLayout')

@section('title', 'فاکتور')

@section('page-title', 'فاکتور')

@section('content')


    <div class="container-fluid">

        <div class="card shadow-sm border-0">

            <div class="card-header bg-white py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h4 class="mb-1">
                            فاکتور
                        </h4>

                        <small class="text-muted">
                            {{ $order->invoice?->invoice_number ?? '-' }}
                        </small>

                    </div>

                    <div>

                        <button
                            type="button"
                            onclick="window.print()"
                            class="btn btn-outline-secondary"
                        >
                            چاپ
                        </button>

                        <a
                            href="{{ route('admin.orders.show', $order) }}"
                            class="btn btn-outline-primary"
                        >
                            بازگشت به سفارش
                        </a>

                    </div>

                </div>

            </div>


            <div class="card-body p-4">

                {{-- فاکتور Information --}}
                <div class="row mb-4">

                    <div class="col-md-6">

                        <h6 class="text-muted">
                            شمارهٔ فاکتور
                        </h6>

                        <strong>
                            {{ $order->invoice?->invoice_number ?? '-' }}
                        </strong>

                    </div>


                    <div class="col-md-6 text-md-end">

                        <h6 class="text-muted">
                            تاریخ
                        </h6>

                        <strong>
                            {{ $order->invoice?->issued_at?->format('Y-m-d H:i') ?? $order->created_at->format('Y-m-d H:i') }}
                        </strong>

                    </div>

                </div>


                <hr>


                {{-- مشتری / نشانی --}}
                <div class="row my-4">

                    <div class="col-md-6">

                        <h6 class="mb-3">
                            مشتری
                        </h6>

                        @if($order->user)

                            <p class="mb-1">
                                <strong>نام:</strong>
                                {{ $order->user->name }}
                                {{ $order->user->lastname }}
                            </p>

                            <p class="mb-1">
                                <strong>رایانامه:</strong>
                                {{ $order->user->email }}
                            </p>

                            <p class="mb-0">
                                <strong>تلفن:</strong>
                                {{ $order->user->tel ?? '-' }}
                            </p>

                        @else

                            <span class="text-muted">
                            کاربر پیدا نشد.
                        </span>

                        @endif

                    </div>


                    <div class="col-md-6">

                        <h6 class="mb-3">
                            نشانی ارسال
                        </h6>

                        @if($order->address)

                            <p class="mb-1">
                                {{ $order->address->province }}
                                -
                                {{ $order->address->city }}
                            </p>

                            <p class="mb-1">
                                {{ $order->address->address }}
                            </p>

                            <p class="mb-1">
                                <strong>گیرنده:</strong>
                                {{ $order->address->receiver_name }}
                            </p>

                            <p class="mb-0">
                                <strong>تلفن:</strong>
                                {{ $order->address->receiver_phone }}
                            </p>

                        @else

                            <span class="text-muted">
                            نشانی پیدا نشد.
                        </span>

                        @endif

                    </div>

                </div>


                {{-- محصولات --}}
                <div class="table-responsive">

                    <table class="table table-bordered align-middle">

                        <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>محصول</th>

                            <th>کد</th>

                            <th>سایز</th>

                            <th>رنگ</th>

                            <th>قیمت</th>

                            <th>تعداد</th>

                            <th>مبلغ کل</th>

                        </tr>

                        </thead>

                        <tbody>

                        @foreach($order->items as $index => $item)

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    {{ $item->product_name }}
                                </td>

                                <td>
                                    {{ $item->product_code }}
                                </td>

                                <td>
                                    {{ $item->size ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->color ?? '-' }}
                                </td>

                                <td>
                                    {{ number_format($item->price) }}
                                    تومان
                                </td>

                                <td>
                                    {{ $item->quantity }}
                                </td>

                                <td>
                                    {{ number_format($item->total) }}
                                    تومان
                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- مبلغ کل --}}
                <div class="row justify-content-end mt-4">

                    <div class="col-md-5">

                        <div class="d-flex justify-content-between mb-2">

                        <span>
                            جمع جزء
                        </span>

                            <strong>
                                {{ number_format($order->subtotal) }}
                                تومان
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-2">

                        <span>
                            تخفیف
                        </span>

                            <strong>
                                {{ number_format($order->discount) }}
                                تومان
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-2">

                        <span>
                            هزینهٔ ارسال
                        </span>

                            <strong>
                                {{ number_format($order->shipping_cost) }}
                                تومان
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-2">

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
                                مبلغ کل
                            </strong>

                            <strong class="fs-5">
                                {{ number_format($order->total_price) }}
                                تومان
                            </strong>

                        </div>

                    </div>

                </div>


                {{-- پرداخت --}}
                <div class="mt-5">

                    <h6 class="mb-3">
                        پرداخت
                    </h6>

                    @switch($order->payment_status)

                        @case('paid')

                            <span class="badge bg-success">
                            پرداخت‌شده
                        </span>

                            @break

                        @case('pending')

                            <span class="badge bg-warning text-dark">
                            در انتظار
                        </span>

                            @break

                        @case('failed')

                            <span class="badge bg-danger">
                            ناموفق
                        </span>

                            @break

                        @case('refunded')

                            <span class="badge bg-info text-dark">
                            بازپرداخت‌شده
                        </span>

                            @break

                        @default

                            <span class="badge bg-secondary">
                            {{ $order->payment_status }}
                        </span>

                    @endswitch

                </div>

            </div>

        </div>

    </div>


    <style>

        @media print {

            .navbar,
            .sidebar,
            .btn,
            footer {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

        }

    </style>


@endsection
