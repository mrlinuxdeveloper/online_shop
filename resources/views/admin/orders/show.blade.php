@extends('admin.layouts.AdminMasterLayout')

@section('title', 'جزئیات سفارش')

@section('page-title', 'جزئیات سفارش')

@section('content')

    <div class="container-fluid">

        {{-- Order Header --}}
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h4 class="mb-1">
                            {{ $order->order_number }}
                        </h4>

                        <small class="text-muted">
                            {{ $order->created_at->format('Y-m-d H:i') }}
                        </small>

                    </div>

                    <a
                        href="{{ route('admin.orders.invoice', $order) }}"
                        class="btn btn-primary"
                    >
                        فاکتور
                    </a>

                    <a
                        href="{{ route('admin.orders.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        بازگشت
                    </a>

                </div>

            </div>

        </div>


        <div class="row g-4">

            {{-- مشتری --}}
            <div class="col-md-6">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header bg-white">
                        <strong>مشتری</strong>
                    </div>

                    <div class="card-body">

                        @if($order->user)

                            <p class="mb-2">
                                <strong>نام:</strong>
                                {{ $order->user->name }}
                                {{ $order->user->lastname }}
                            </p>

                            <p class="mb-2">
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

                </div>

            </div>


            {{-- نشانی --}}
            <div class="col-md-6">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header bg-white">
                        <strong>نشانی ارسال</strong>
                    </div>

                    <div class="card-body">

                        @if($order->address)

                            <p class="mb-2">
                                {{ $order->address->province }}
                                -
                                {{ $order->address->city }}
                            </p>

                            <p class="mb-2">
                                {{ $order->address->address }}
                            </p>

                            <p class="mb-2">
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

            </div>

        </div>


        {{-- محصولات --}}
        <div class="card shadow-sm border-0 mt-4">

            <div class="card-header bg-white">

                <strong>
                    اقلام سفارش
                </strong>

            </div>


            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead class="table-light">

                    <tr>

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

                    @foreach($order->items as $item)

                        <tr>

                            <td>

                                <div class="d-flex align-items-center gap-3">

                                    @if($item->product_image)

                                        <img
                                            src="{{ asset(
                                            'storage/' .
                                            $item->product_image
                                        ) }}"
                                            width="60"
                                            height="60"
                                            style="object-fit: cover;"
                                            class="rounded"
                                            alt=""
                                        >

                                    @endif

                                    <span>
                                    {{ $item->product_name }}
                                </span>

                                </div>

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
                            </td>

                            <td>
                                {{ $item->quantity }}
                            </td>

                            <td>
                                {{ number_format($item->total) }}
                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>


        {{-- مبلغ کل --}}
        <div class="row justify-content-end mt-4">

            <div class="col-md-5">

                <div class="card shadow-sm border-0">

                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-2">

                        <span>
                            جمع جزء
                        </span>

                            <strong>
                                {{ number_format($order->subtotal) }}
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-2">

                        <span>
                            تخفیف
                        </span>

                            <strong>
                                {{ number_format($order->discount) }}
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-2">

                        <span>
                            هزینهٔ ارسال
                        </span>

                            <strong>
                                {{ number_format($order->shipping_cost) }}
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

            </div>

        </div>


        {{-- وضعیت --}}
        <div class="card shadow-sm border-0 mt-4">

            <div class="card-header bg-white">
                <strong>وضعیت سفارش</strong>
            </div>

            <div class="card-body">

                <form
                    action="{{ route(
                    'admin.orders.status',
                    $order
                ) }}"
                    method="POST"
                >

                    @csrf
                    @method('PATCH')

                    <div class="row align-items-end">

                        <div class="col-md-5">

                            <label class="form-label">
                                وضعیت
                            </label>

                            <select
                                name="status"
                                class="form-select"
                            >

                                <option
                                    value="pending"
                                    @selected($order->status === 'pending')
                                >
                                    در انتظار
                                </option>

                                <option
                                    value="processing"
                                    @selected($order->status === 'processing')
                                >
                                    در حال پردازش
                                </option>

                                <option
                                    value="shipped"
                                    @selected($order->status === 'shipped')
                                >
                                    ارسال‌شده
                                </option>

                                <option
                                    value="delivered"
                                    @selected($order->status === 'delivered')
                                >
                                    تحویل‌شده
                                </option>

                                <option
                                    value="cancelled"
                                    @selected($order->status === 'cancelled')
                                >
                                    لغوشده
                                </option>

                            </select>

                        </div>


                        <div class="col-md-auto">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                به‌روزرسانی وضعیت
                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>
// payment status

        {{-- پرداخت --}}

        <div class="card shadow-sm border-0 mt-4">


            <div class="card-header bg-white">
                <strong>پرداخت</strong>
            </div>

            <div class="card-body">

                <div class="row mb-4">

                    <div class="col-md-4">

                        <small class="text-muted d-block mb-1">
                            وضعیت پرداخت
                        </small>

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

                    <div class="col-md-4">

                        <small class="text-muted d-block mb-1">
                            مبلغ کل سفارش
                        </small>

                        <strong>
                            {{ number_format($order->total_price) }}
                            تومان
                        </strong>

                    </div>

                    <div class="col-md-4">

                        <small class="text-muted d-block mb-1">
                            سوابق پرداخت
                        </small>

                        <strong>
                            {{ $order->payments->count() }}
                        </strong>

                    </div>

                </div>


                <form
                    action="{{ route('admin.orders.payment-status', $order) }}"
                    method="POST"
                >

                    @csrf
                    @method('PATCH')

                    <div class="row align-items-end">

                        <div class="col-md-5">

                            <label class="form-label">
                                تغییر وضعیت پرداخت
                            </label>

                            <select
                                name="payment_status"
                                class="form-select"
                            >

                                <option
                                    value="pending"
                                    @selected($order->payment_status === 'pending')
                                >
                                    در انتظار
                                </option>

                                <option
                                    value="paid"
                                    @selected($order->payment_status === 'paid')
                                >
                                    پرداخت‌شده
                                </option>

                                <option
                                    value="failed"
                                    @selected($order->payment_status === 'failed')
                                >
                                    ناموفق
                                </option>

                                <option
                                    value="refunded"
                                    @selected($order->payment_status === 'refunded')
                                >
                                    بازپرداخت‌شده
                                </option>

                            </select>

                        </div>

                        <div class="col-md-auto">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                به‌روزرسانی وضعیت پرداخت
                            </button>

                        </div>

                    </div>

                </form>

            </div>


        </div>


{{--        <div class="card mb-4">--}}
{{--            <div class="card-header">--}}
{{--                <h5 class="mb-0">وضعیت پرداخت</h5>--}}
{{--            </div>--}}

{{--            <div class="card-body">--}}

{{--                <form action="{{ route('admin.orders.payment-status', $order) }}"--}}
{{--                      method="POST">--}}

{{--                    @csrf--}}
{{--                    @method('PATCH')--}}

{{--                    <div class="row align-items-end">--}}

{{--                        <div class="col-md-6">--}}

{{--                            <label class="form-label">--}}
{{--                                وضعیت پرداخت--}}
{{--                            </label>--}}

{{--                            <select name="payment_status"--}}
{{--                                    class="form-select">--}}

{{--                                <option value="pending"--}}
{{--                                    {{ $order->payment_status === 'pending' ? 'selected' : '' }}>--}}
{{--                                    در انتظار--}}
{{--                                </option>--}}

{{--                                <option value="paid"--}}
{{--                                    {{ $order->payment_status === 'paid' ? 'selected' : '' }}>--}}
{{--                                    پرداخت‌شده--}}
{{--                                </option>--}}

{{--                                <option value="failed"--}}
{{--                                    {{ $order->payment_status === 'failed' ? 'selected' : '' }}>--}}
{{--                                    ناموفق--}}
{{--                                </option>--}}

{{--                                <option value="refunded"--}}
{{--                                    {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>--}}
{{--                                    بازپرداخت‌شده--}}
{{--                                </option>--}}

{{--                            </select>--}}

{{--                        </div>--}}

{{--                        <div class="col-md-3">--}}

{{--                            <button type="submit"--}}
{{--                                    class="btn btn-primary">--}}
{{--                                به‌روزرسانی وضعیت پرداخت--}}
{{--                            </button>--}}

{{--                        </div>--}}

{{--                    </div>--}}

{{--                </form>--}}

{{--            </div>--}}
{{--        </div>--}}


        {{-- Shipment --}}

        <div class="card shadow-sm border-0 mt-4">


            <div class="card-header bg-white">

                <strong>
                    ارسال
                </strong>

            </div>


            <div class="card-body">

                <form
                    action="{{ route('admin.orders.shipment', $order) }}"
                    method="POST"
                >

                    @csrf
                    @method('PATCH')


                    <div class="row g-3">


                        {{-- روش ارسال --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                شرکت یا روش ارسال
                            </label>

                            <input
                                type="text"
                                name="shipping_method"
                                class="form-control"
                                value="{{ $order->shipment?->shipping_method }}"
                                placeholder="مثلاً پست، تیپاکس و ..."
                            >

                        </div>


                        {{-- کد رهگیری --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                کد رهگیری
                            </label>

                            <input
                                type="text"
                                name="tracking_code"
                                class="form-control"
                                value="{{ $order->shipment?->tracking_code }}"
                                placeholder="شمارهٔ رهگیری"
                            >

                        </div>


                        {{-- وضعیت --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                وضعیت ارسال
                            </label>

                            <select
                                name="shipment_status"
                                class="form-select"
                            >

                                <option
                                    value="pending"
                                    @selected(
                                        $order->shipment?->status === 'pending'
                                        || !$order->shipment
                                    )
                                >
                                    در انتظار
                                </option>


                                <option
                                    value="processing"
                                    @selected(
                                        $order->shipment?->status === 'processing'
                                    )
                                >
                                    در حال پردازش
                                </option>


                                <option
                                    value="shipped"
                                    @selected(
                                        $order->shipment?->status === 'shipped'
                                    )
                                >
                                    ارسال‌شده
                                </option>


                                <option
                                    value="delivered"
                                    @selected(
                                        $order->shipment?->status === 'delivered'
                                    )
                                >
                                    تحویل‌شده
                                </option>


                                <option
                                    value="returned"
                                    @selected(
                                        $order->shipment?->status === 'returned'
                                    )
                                >
                                    مرجوع‌شده
                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- Current اطلاعات ارسال --}}
                    @if($order->shipment)

                        <div class="row mt-4">

                            <div class="col-md-4">

                                <small class="text-muted d-block mb-1">
                                    تاریخ ارسال
                                </small>

                                <strong>
                                    {{ $order->shipment->shipped_at?->format('Y-m-d H:i') ?? '-' }}
                                </strong>

                            </div>


                            <div class="col-md-4">

                                <small class="text-muted d-block mb-1">
                                    تاریخ تحویل
                                </small>

                                <strong>
                                    {{ $order->shipment->delivered_at?->format('Y-m-d H:i') ?? '-' }}
                                </strong>

                            </div>


                            <div class="col-md-4">

                                <small class="text-muted d-block mb-1">
                                    وضعیت فعلی
                                </small>


                                @switch($order->shipment->status)

                                    @case('pending')

                                        <span class="badge bg-secondary">
                                در انتظار
                            </span>

                                        @break


                                    @case('processing')

                                        <span class="badge bg-primary">
                                در حال پردازش
                            </span>

                                        @break


                                    @case('shipped')

                                        <span class="badge bg-info text-dark">
                                ارسال‌شده
                            </span>

                                        @break


                                    @case('delivered')

                                        <span class="badge bg-success">
                                تحویل‌شده
                            </span>

                                        @break


                                    @case('returned')

                                        <span class="badge bg-danger">
                                مرجوع‌شده
                            </span>

                                        @break


                                    @default

                                        <span class="badge bg-secondary">
                                {{ $order->shipment->status }}
                            </span>

                                @endswitch

                            </div>

                        </div>

                    @endif


                    <div class="mt-4">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            به‌روزرسانی ارسال
                        </button>

                    </div>

                </form>

            </div>


        </div>


    </div>

@endsection
