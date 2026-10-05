@extends('layouts.app')

@section('content')

    <style>

        .invoice-page {
            max-width: 1000px;
            margin: 40px auto;
        }

        .invoice-box {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            padding: 35px;
        }

        .invoice-title {
            font-size: 28px;
            font-weight: 700;
        }

        .invoice-number {
            color: #666;
            font-size: 14px;
        }

        .invoice-table th,
        .invoice-table td {
            vertical-align: middle;
        }

        .invoice-total {
            font-size: 20px;
            font-weight: 700;
        }

        .invoice-actions {
            max-width: 1000px;
            margin: 20px auto;
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
                max-width: 100%;
                margin: 0;
            }

            .invoice-box {
                border: none;
                box-shadow: none;
                border-radius: 0;
                padding: 20px;
            }

            .table {
                border-color: #000 !important;
            }

        }

    </style>


    <div class="container">

        <div class="invoice-actions d-flex justify-content-between">

            <a
                href="{{ route('orders.show', $order) }}"
                class="btn btn-outline-secondary"
            >
                بازگشت به سفارش
            </a>

            <button
                type="button"
                onclick="window.print()"
                class="btn btn-dark"
            >
                چاپ فاکتور
            </button>

        </div>


        <div class="invoice-page">

            <div class="invoice-box">


                {{-- Header --}}
                <div class="row align-items-start mb-5">

                    <div class="col-md-6">

                        <div class="invoice-title">
                            فاکتور فروش
                        </div>

                        <div class="invoice-number mt-2">

                            شماره فاکتور:
                            <strong>
                                {{ $order->invoice?->invoice_number }}
                            </strong>

                        </div>

                        <div class="invoice-number">

                            شماره سفارش:
                            <strong>
                                {{ $order->order_number }}
                            </strong>

                        </div>

                        <div class="invoice-number">

                            تاریخ:
                            {{ $order->created_at?->format('Y/m/d H:i') }}

                        </div>

                    </div>


                    <div class="col-md-6 text-md-end mt-4 mt-md-0">

                        <h4 class="fw-bold">
                            فروشگاه آنلاین
                        </h4>

                        <div class="text-muted">
                            فاکتور سفارش مشتری
                        </div>

                    </div>

                </div>


                {{-- Customer / Address --}}
                <div class="row mb-5">

                    <div class="col-md-6">

                        <h6 class="fw-bold mb-3">
                            اطلاعات مشتری
                        </h6>

                        <p class="mb-1">

                            <strong>
                                نام:
                            </strong>

                            {{ $order->user?->name }}
                            {{ $order->user?->lastname }}

                        </p>

                        <p class="mb-1">

                            <strong>
                                تلفن:
                            </strong>

                            {{ $order->address?->receiver_phone }}

                        </p>

                    </div>


                    <div class="col-md-6">

                        <h6 class="fw-bold mb-3">
                            آدرس ارسال
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

                            <p class="mb-0">

                                کد پستی:

                                {{ $order->address->postal_code }}

                            </p>

                        @else

                            <p class="text-muted">
                                آدرس ثبت نشده است.
                            </p>

                        @endif

                    </div>

                </div>


                {{-- Products --}}
                <div class="table-responsive mb-4">

                    <table class="table table-bordered invoice-table">

                        <thead>

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
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

                        @foreach($order->items as $index => $item)

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>

                                    <strong>
                                        {{ $item->product_name }}
                                    </strong>

                                    @if($item->product_code)

                                        <div class="small text-muted">

                                            کد:
                                            {{ $item->product_code }}

                                        </div>

                                    @endif

                                </td>


                                <td>

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


                {{-- Summary --}}
                <div class="row justify-content-end">

                    <div class="col-md-6 col-lg-5">

                        <div class="d-flex justify-content-between mb-2">

                        <span>
                            جمع محصولات
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

                                -
                                {{ number_format($order->discount) }}
                                تومان

                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-2">

                        <span>
                            هزینه ارسال
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

                        <span class="invoice-total">
                            مبلغ نهایی
                        </span>

                            <span class="invoice-total">

                            {{ number_format($order->total_price) }}
                            تومان

                        </span>

                        </div>

                    </div>

                </div>


                {{-- Payment --}}
                <div class="border-top mt-5 pt-4">

                    <h6 class="fw-bold mb-3">
                        اطلاعات پرداخت
                    </h6>

                    @if($payment)

                        <div class="row">

                            <div class="col-md-4">

                                <small class="text-muted">
                                    درگاه
                                </small>

                                <div>
                                    {{ $payment->gateway ?? 'ZarinPal' }}
                                </div>

                            </div>


                            <div class="col-md-4">

                                <small class="text-muted">
                                    وضعیت
                                </small>

                                <div class="text-success">
                                    پرداخت موفق
                                </div>

                            </div>


                            <div class="col-md-4">

                                <small class="text-muted">
                                    شماره پیگیری
                                </small>

                                <div class="font-monospace">

                                    {{ $payment->reference_id }}

                                </div>

                            </div>

                        </div>

                    @else

                        <div class="text-muted">
                            پرداختی برای این سفارش ثبت نشده است.
                        </div>

                    @endif

                </div>


                {{-- Footer --}}
                <div class="text-center border-top mt-5 pt-4">

                    <small class="text-muted">
                        از خرید شما سپاسگزاریم.
                    </small>

                </div>


            </div>

        </div>

    </div>

@endsection
