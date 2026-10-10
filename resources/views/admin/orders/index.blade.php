@extends('admin.layouts.AdminMasterLayout')

@section('title', 'سفارش‌ها')

@section('page-title', 'سفارش‌ها')

@section('content')


    <div class="container-fluid">

        <div class="card shadow-sm border-0">

            <div class="card-header bg-white py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h5 class="mb-1">
                            سفارش‌ها
                        </h5>

                        <small class="text-muted">
                            مدیریت سفارش‌های مشتریان
                        </small>
                    </div>

                    <span class="badge bg-dark">
                    {{ $orders->total() }} سفارش‌ها
                </span>

                </div>

            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>شمارهٔ سفارش</th>

                            <th>مشتری</th>

                            <th>مبلغ کل</th>

                            <th>پرداخت</th>

                            <th>وضعیت سفارش</th>

                            <th>تاریخ</th>

                            <th class="text-end">
                                عملیات
                            </th>

                        </tr>

                        </thead>

                        <tbody>

                        @forelse($orders as $order)

                            <tr>

                                <td>
                                    {{ $order->id }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $order->order_number }}
                                    </strong>
                                </td>

                                <td>

                                    @if($order->user)

                                        <div>
                                            {{ $order->user->name }}
                                            {{ $order->user->lastname }}
                                        </div>

                                        <small class="text-muted">
                                            {{ $order->user->email }}
                                        </small>

                                    @else

                                        <span class="text-muted">
                                        کاربر نامشخص
                                    </span>

                                    @endif

                                </td>

                                <td>

                                    <strong>
                                        {{ number_format($order->total_price) }}
                                    </strong>

                                    <small>
                                        تومان
                                    </small>

                                </td>

                                <td>

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

                                </td>

                                <td>

                                    @switch($order->status)

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

                                        @case('cancelled')

                                            <span class="badge bg-danger">
                                            لغوشده
                                        </span>

                                            @break

                                        @default

                                            <span class="badge bg-secondary">
                                            {{ $order->status }}
                                        </span>

                                    @endswitch

                                </td>

                                <td>
                                    {{ $order->created_at->format('Y-m-d H:i') }}
                                </td>

                                <td class="text-end">

                                    <a
                                        href="{{ route('admin.orders.show', $order) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        مشاهده
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">
                                        سفارشی پیدا نشد.
                                    </div>

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

            @if($orders->hasPages())

                <div class="card-footer bg-white">

                    {{ $orders->links() }}

                </div>

            @endif

        </div>

    </div>


@endsection




{{--@extends('admin.layouts.AdminMasterLayout')--}}

{{--@section('title', 'سفارش‌ها')--}}

{{--@section('page-title', 'سفارش‌ها')--}}

{{--@section('content')--}}

{{--    <div class="container-fluid">--}}

{{--        <div class="card shadow-sm border-0">--}}

{{--            <div class="card-header bg-white py-3">--}}

{{--                <div class="d-flex justify-content-between align-items-center">--}}

{{--                    <div>--}}
{{--                        <h5 class="mb-1">--}}
{{--                            سفارش‌ها--}}
{{--                        </h5>--}}

{{--                        <small class="text-muted">--}}
{{--                            مدیریت سفارش‌های مشتریان--}}
{{--                        </small>--}}
{{--                    </div>--}}

{{--                    <span class="badge bg-dark">--}}
{{--                    {{ $orders->total() }} سفارش‌ها--}}
{{--                </span>--}}

{{--                </div>--}}

{{--            </div>--}}


{{--            <div class="card-body p-0">--}}

{{--                <div class="table-responsive">--}}

{{--                    <table class="table table-hover align-middle mb-0">--}}

{{--                        <thead class="table-light">--}}

{{--                        <tr>--}}

{{--                            <th>#</th>--}}

{{--                            <th>شمارهٔ سفارش</th>--}}

{{--                            <th>مشتری</th>--}}

{{--                            <th>مبلغ کل</th>--}}

{{--                            <th>پرداخت</th>--}}

{{--                            <th>وضعیت سفارش</th>--}}

{{--                            <th>تاریخ</th>--}}

{{--                            <th class="text-end">--}}
{{--                                عملیات--}}
{{--                            </th>--}}

{{--                        </tr>--}}

{{--                        </thead>--}}


{{--                        <tbody>--}}

{{--                        @forelse($orders as $order)--}}

{{--                            <tr>--}}

{{--                                <td>--}}
{{--                                    {{ $order->id }}--}}
{{--                                </td>--}}


{{--                                <td>--}}

{{--                                    <strong>--}}
{{--                                        {{ $order->order_number }}--}}
{{--                                    </strong>--}}

{{--                                </td>--}}


{{--                                <td>--}}

{{--                                    @if($order->user)--}}

{{--                                        <div>--}}
{{--                                            {{ $order->user->name }}--}}
{{--                                            {{ $order->user->lastname }}--}}
{{--                                        </div>--}}

{{--                                        <small class="text-muted">--}}
{{--                                            {{ $order->user->email }}--}}
{{--                                        </small>--}}

{{--                                    @else--}}

{{--                                        <span class="text-muted">--}}
{{--                                        کاربر نامشخص--}}
{{--                                    </span>--}}

{{--                                    @endif--}}

{{--                                </td>--}}


{{--                                <td>--}}

{{--                                    {{ number_format($order->total_price) }}--}}

{{--                                    <small>--}}
{{--                                        تومان--}}
{{--                                    </small>--}}

{{--                                </td>--}}


{{--                                <td>--}}

{{--                                    @if($order->payment_status === 'paid')--}}

{{--                                        <span class="badge bg-success">--}}
{{--                                        پرداخت‌شده--}}
{{--                                    </span>--}}

{{--                                    @elseif($order->payment_status === 'pending')--}}

{{--                                        <span class="badge bg-warning text-dark">--}}
{{--                                        در انتظار--}}
{{--                                    </span>--}}

{{--                                    @else--}}

{{--                                        <span class="badge bg-danger">--}}
{{--                                        {{ $order->payment_status }}--}}
{{--                                    </span>--}}

{{--                                    @endif--}}

{{--                                </td>--}}


{{--                                <td>--}}

{{--                                    @switch($order->status)--}}

{{--                                        @case('pending')--}}

{{--                                            <span class="badge bg-secondary">--}}
{{--                                            در انتظار--}}
{{--                                        </span>--}}

{{--                                            @break--}}

{{--                                        @case('processing')--}}

{{--                                            <span class="badge bg-primary">--}}
{{--                                            در حال پردازش--}}
{{--                                        </span>--}}

{{--                                            @break--}}

{{--                                        @case('shipped')--}}

{{--                                            <span class="badge bg-info text-dark">--}}
{{--                                            ارسال‌شده--}}
{{--                                        </span>--}}

{{--                                            @break--}}

{{--                                        @case('delivered')--}}

{{--                                            <span class="badge bg-success">--}}
{{--                                            تحویل‌شده--}}
{{--                                        </span>--}}

{{--                                            @break--}}

{{--                                        @case('cancelled')--}}

{{--                                            <span class="badge bg-danger">--}}
{{--                                            لغوشده--}}
{{--                                        </span>--}}

{{--                                            @break--}}

{{--                                        @default--}}

{{--                                            <span class="badge bg-secondary">--}}
{{--                                            {{ $order->status }}--}}
{{--                                        </span>--}}

{{--                                    @endswitch--}}

{{--                                </td>--}}


{{--                                <td>--}}

{{--                                    {{ $order->created_at->format('Y-m-d H:i') }}--}}

{{--                                </td>--}}


{{--                                <td class="text-end">--}}

{{--                                    <a href="{{ route(--}}
{{--                                    'admin.orders.show',--}}
{{--                                    $order--}}
{{--                                ) }}"--}}
{{--                                       class="btn btn-sm btn-outline-primary">--}}

{{--                                        مشاهده--}}

{{--                                    </a>--}}

{{--                                </td>--}}

{{--                            </tr>--}}

{{--                        @empty--}}

{{--                            <tr>--}}

{{--                                <td--}}
{{--                                    colspan="8"--}}
{{--                                    class="text-center py-5"--}}
{{--                                >--}}

{{--                                    <div class="text-muted">--}}

{{--                                        سفارشی پیدا نشد.--}}

{{--                                    </div>--}}

{{--                                </td>--}}

{{--                            </tr>--}}

{{--                        @endforelse--}}

{{--                        </tbody>--}}

{{--                    </table>--}}

{{--                </div>--}}

{{--            </div>--}}


{{--            @if($orders->hasPages())--}}

{{--                <div class="card-footer bg-white">--}}

{{--                    {{ $orders->links() }}--}}

{{--                </div>--}}

{{--            @endif--}}

{{--        </div>--}}

{{--    </div>--}}

{{--@endsection--}}

