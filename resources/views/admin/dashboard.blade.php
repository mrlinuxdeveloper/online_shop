@extends('admin.layouts.AdminMasterLayout')

@section('content')

    <div class="container-fluid">

        {{-- Header --}}

        <div class="mb-4">

            <h4 class="fw-bold mb-1">
                داشبورد مدیریت
            </h4>

            <p class="text-muted mb-0">
                نمای کلی وضعیت فروشگاه
            </p>

        </div>


        {{-- Messages --}}

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


        {{-- Main Statistics --}}

        <div class="row g-4 mb-4">


            {{-- Users --}}

            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <p class="text-muted mb-1">
                                    کاربران
                                </p>

                                <h3 class="fw-bold mb-0">
                                    {{ number_format($usersCount) }}
                                </h3>

                            </div>

                            <div class="fs-2">
                                👤
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Products --}}

            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <p class="text-muted mb-1">
                                    محصولات
                                </p>

                                <h3 class="fw-bold mb-0">
                                    {{ number_format($productsCount) }}
                                </h3>

                            </div>

                            <div class="fs-2">
                                📦
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Orders --}}

            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <p class="text-muted mb-1">
                                    کل سفارش‌ها
                                </p>

                                <h3 class="fw-bold mb-0">
                                    {{ number_format($ordersCount) }}
                                </h3>

                            </div>

                            <div class="fs-2">
                                🛒
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Low Stock --}}

            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <p class="text-muted mb-1">
                                    موجودی کم
                                </p>

                                <h3 class="fw-bold mb-0">
                                    {{ number_format($lowStockVariants) }}
                                </h3>

                            </div>

                            <div class="fs-2">
                                ⚠️
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Sales Statistics --}}

        <div class="row g-4 mb-4">


            {{-- Total Sales --}}

            <div class="col-lg-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <p class="text-muted mb-2">
                            مجموع فروش
                        </p>

                        <h3 class="fw-bold">

                            {{ number_format($totalSales) }}

                            <small class="fs-6 text-muted">
                                تومان
                            </small>

                        </h3>

                    </div>

                </div>

            </div>


            {{-- Monthly Sales --}}

            <div class="col-lg-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <p class="text-muted mb-2">
                            فروش ماه جاری
                        </p>

                        <h3 class="fw-bold">

                            {{ number_format($monthlySales) }}

                            <small class="fs-6 text-muted">
                                تومان
                            </small>

                        </h3>

                    </div>

                </div>

            </div>

        </div>


        {{-- Orders & Payments --}}

        <div class="row g-4 mb-4">


            {{-- Orders Status --}}

            <div class="col-lg-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-header bg-white">

                        <h5 class="mb-0 fw-bold">
                            وضعیت سفارش‌ها
                        </h5>

                    </div>


                    <div class="card-body">


                        <div class="d-flex justify-content-between mb-3">

                        <span>
                            در انتظار
                        </span>

                            <span class="badge bg-secondary">
                            {{ $pendingOrders }}
                        </span>

                        </div>


                        <div class="d-flex justify-content-between mb-3">

                        <span>
                            در حال پردازش
                        </span>

                            <span class="badge bg-warning text-dark">
                            {{ $processingOrders }}
                        </span>

                        </div>


                        <div class="d-flex justify-content-between mb-3">

                        <span>
                            ارسال شده
                        </span>

                            <span class="badge bg-primary">
                            {{ $shippedOrders }}
                        </span>

                        </div>


                        <div class="d-flex justify-content-between">

                        <span>
                            تحویل شده
                        </span>

                            <span class="badge bg-success">
                            {{ $deliveredOrders }}
                        </span>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Payment Status --}}

            <div class="col-lg-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-header bg-white">

                        <h5 class="mb-0 fw-bold">
                            وضعیت پرداخت‌ها
                        </h5>

                    </div>


                    <div class="card-body">


                        <div class="d-flex justify-content-between mb-3">

                        <span>
                            پرداخت شده
                        </span>

                            <span class="badge bg-success">
                            {{ $paidOrders }}
                        </span>

                        </div>


                        <div class="d-flex justify-content-between mb-3">

                        <span>
                            در انتظار پرداخت
                        </span>

                            <span class="badge bg-warning text-dark">
                            {{ $pendingPayments }}
                        </span>

                        </div>


                        <div class="d-flex justify-content-between">

                        <span>
                            پرداخت ناموفق
                        </span>

                            <span class="badge bg-danger">
                            {{ $failedPayments }}
                        </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Shipment Status --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0 fw-bold">
                        وضعیت ارسال‌ها
                    </h5>

                    <a
                        href="{{ route('admin.shipments.index') }}"
                        class="btn btn-sm btn-outline-primary"
                    >
                        مدیریت ارسال‌ها
                    </a>

                </div>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    <div class="col-md">

                        <div class="border rounded p-3 text-center">

                            <div class="text-muted mb-1">
                                در انتظار
                            </div>

                            <h4 class="fw-bold mb-0">
                                {{ $pendingShipments }}
                            </h4>

                        </div>

                    </div>


                    <div class="col-md">

                        <div class="border rounded p-3 text-center">

                            <div class="text-muted mb-1">
                                در حال پردازش
                            </div>

                            <h4 class="fw-bold mb-0">
                                {{ $processingShipments }}
                            </h4>

                        </div>

                    </div>


                    <div class="col-md">

                        <div class="border rounded p-3 text-center">

                            <div class="text-muted mb-1">
                                ارسال شده
                            </div>

                            <h4 class="fw-bold mb-0">
                                {{ $shippedShipments }}
                            </h4>

                        </div>

                    </div>


                    <div class="col-md">

                        <div class="border rounded p-3 text-center">

                            <div class="text-muted mb-1">
                                تحویل شده
                            </div>

                            <h4 class="fw-bold mb-0">
                                {{ $deliveredShipments }}
                            </h4>

                        </div>

                    </div>


                    <div class="col-md">

                        <div class="border rounded p-3 text-center">

                            <div class="text-muted mb-1">
                                مرجوع شده
                            </div>

                            <h4 class="fw-bold mb-0">
                                {{ $returnedShipments }}
                            </h4>

                        </div>

                    </div>


                </div>

            </div>

        </div>


        {{-- Recent Orders --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0 fw-bold">
                        آخرین سفارش‌ها
                    </h5>

                    <a
                        href="{{ route('admin.orders.index') }}"
                        class="btn btn-sm btn-outline-primary"
                    >
                        مشاهده همه
                    </a>

                </div>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>
                                سفارش
                            </th>

                            <th>
                                مشتری
                            </th>

                            <th>
                                مبلغ
                            </th>

                            <th>
                                پرداخت
                            </th>

                            <th>
                                وضعیت
                            </th>

                            <th>
                                تاریخ
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @forelse($recentOrders as $order)

                            <tr>

                                <td>

                                    <a
                                        href="{{ route('admin.orders.show', $order) }}"
                                        class="fw-bold text-decoration-none"
                                    >
                                        {{ $order->order_number }}
                                    </a>

                                </td>


                                <td>

                                    {{ $order->user->name ?? '-' }}
                                    {{ $order->user->lastname ?? '' }}

                                </td>


                                <td>

                                    {{ number_format($order->total_price) }}

                                    تومان

                                </td>


                                <td>

                                    @if($order->payment_status === 'paid')

                                        <span class="badge bg-success">
                                        پرداخت شده
                                    </span>

                                    @elseif($order->payment_status === 'pending')

                                        <span class="badge bg-warning text-dark">
                                        در انتظار
                                    </span>

                                    @else

                                        <span class="badge bg-danger">
                                        ناموفق
                                    </span>

                                    @endif

                                </td>


                                <td>

                                    @switch($order->status)

                                        @case('pending')

                                            <span class="badge bg-secondary">
                                            در انتظار
                                        </span>

                                            @break

                                        @case('processing')

                                            <span class="badge bg-warning text-dark">
                                            پردازش
                                        </span>

                                            @break

                                        @case('shipped')

                                            <span class="badge bg-primary">
                                            ارسال شده
                                        </span>

                                            @break

                                        @case('delivered')

                                            <span class="badge bg-success">
                                            تحویل شده
                                        </span>

                                            @break

                                        @default

                                            <span class="badge bg-secondary">
                                            {{ $order->status }}
                                        </span>

                                    @endswitch

                                </td>


                                <td>

                                    {{ $order->created_at->format('Y/m/d H:i') }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center py-4 text-muted"
                                >
                                    هنوز سفارشی ثبت نشده است.
                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- Recent Payments --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0 fw-bold">
                    آخرین پرداخت‌ها
                </h5>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>
                                سفارش
                            </th>

                            <th>
                                کاربر
                            </th>

                            <th>
                                مبلغ
                            </th>

                            <th>
                                درگاه
                            </th>

                            <th>
                                وضعیت
                            </th>

                            <th>
                                تاریخ
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @forelse($recentPayments as $payment)

                            <tr>

                                <td>

                                    {{ $payment->order->order_number ?? '-' }}

                                </td>


                                <td>

                                    {{ $payment->user->name ?? '-' }}
                                    {{ $payment->user->lastname ?? '' }}

                                </td>


                                <td>

                                    {{ number_format($payment->amount) }}

                                    تومان

                                </td>


                                <td>

                                    {{ $payment->gateway ?? '-' }}

                                </td>


                                <td>

                                    @if($payment->status === 'paid')

                                        <span class="badge bg-success">
                                        پرداخت شده
                                    </span>

                                    @elseif($payment->status === 'pending')

                                        <span class="badge bg-warning text-dark">
                                        در انتظار
                                    </span>

                                    @else

                                        <span class="badge bg-danger">
                                        ناموفق
                                    </span>

                                    @endif

                                </td>


                                <td>

                                    {{ $payment->created_at->format('Y/m/d H:i') }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center py-4 text-muted"
                                >
                                    هنوز پرداختی ثبت نشده است.
                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- Recent Shipments --}}

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0 fw-bold">
                        آخرین ارسال‌ها
                    </h5>

                    <a
                        href="{{ route('admin.shipments.index') }}"
                        class="btn btn-sm btn-outline-primary"
                    >
                        مشاهده همه
                    </a>

                </div>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>
                                سفارش
                            </th>

                            <th>
                                مشتری
                            </th>

                            <th>
                                روش ارسال
                            </th>

                            <th>
                                کد رهگیری
                            </th>

                            <th>
                                وضعیت
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @forelse($recentShipments as $shipment)

                            <tr>

                                <td>

                                    <a
                                        href="{{ route('admin.shipments.show', $shipment) }}"
                                        class="text-decoration-none fw-bold"
                                    >
                                        {{ $shipment->order->order_number ?? '-' }}
                                    </a>

                                </td>


                                <td>

                                    {{ $shipment->order->user->name ?? '-' }}
                                    {{ $shipment->order->user->lastname ?? '' }}

                                </td>


                                <td>

                                    {{ $shipment->shipping_method }}

                                </td>


                                <td>

                                    {{ $shipment->tracking_code ?? '-' }}

                                </td>


                                <td>

                                    @switch($shipment->status)

                                        @case('pending')

                                            <span class="badge bg-secondary">
                                            در انتظار
                                        </span>

                                            @break

                                        @case('processing')

                                            <span class="badge bg-warning text-dark">
                                            پردازش
                                        </span>

                                            @break

                                        @case('shipped')

                                            <span class="badge bg-primary">
                                            ارسال شده
                                        </span>

                                            @break

                                        @case('delivered')

                                            <span class="badge bg-success">
                                            تحویل شده
                                        </span>

                                            @break

                                        @case('returned')

                                            <span class="badge bg-danger">
                                            مرجوع شده
                                        </span>

                                            @break

                                    @endswitch

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center py-4 text-muted"
                                >
                                    هنوز ارسالی ثبت نشده است.
                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

@endsection
