@extends('admin.layouts.AdminMasterLayout')

@section('title', 'Orders')

@section('page-title', 'Orders')

@section('content')


    <div class="container-fluid">

        <div class="card shadow-sm border-0">

            <div class="card-header bg-white py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h5 class="mb-1">
                            Orders
                        </h5>

                        <small class="text-muted">
                            Manage customer orders
                        </small>
                    </div>

                    <span class="badge bg-dark">
                    {{ $orders->total() }} Orders
                </span>

                </div>

            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Order Number</th>

                            <th>Customer</th>

                            <th>Total</th>

                            <th>Payment</th>

                            <th>Order Status</th>

                            <th>Date</th>

                            <th class="text-end">
                                Action
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
                                        Unknown User
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
                                            Paid
                                        </span>

                                            @break

                                        @case('pending')

                                            <span class="badge bg-warning text-dark">
                                            Pending
                                        </span>

                                            @break

                                        @case('failed')

                                            <span class="badge bg-danger">
                                            Failed
                                        </span>

                                            @break

                                        @case('refunded')

                                            <span class="badge bg-info text-dark">
                                            Refunded
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
                                            Pending
                                        </span>

                                            @break

                                        @case('processing')

                                            <span class="badge bg-primary">
                                            Processing
                                        </span>

                                            @break

                                        @case('shipped')

                                            <span class="badge bg-info text-dark">
                                            Shipped
                                        </span>

                                            @break

                                        @case('delivered')

                                            <span class="badge bg-success">
                                            Delivered
                                        </span>

                                            @break

                                        @case('cancelled')

                                            <span class="badge bg-danger">
                                            Cancelled
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
                                        View
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
                                        No orders found.
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

{{--@section('title', 'Orders')--}}

{{--@section('page-title', 'Orders')--}}

{{--@section('content')--}}

{{--    <div class="container-fluid">--}}

{{--        <div class="card shadow-sm border-0">--}}

{{--            <div class="card-header bg-white py-3">--}}

{{--                <div class="d-flex justify-content-between align-items-center">--}}

{{--                    <div>--}}
{{--                        <h5 class="mb-1">--}}
{{--                            Orders--}}
{{--                        </h5>--}}

{{--                        <small class="text-muted">--}}
{{--                            Manage customer orders--}}
{{--                        </small>--}}
{{--                    </div>--}}

{{--                    <span class="badge bg-dark">--}}
{{--                    {{ $orders->total() }} Orders--}}
{{--                </span>--}}

{{--                </div>--}}

{{--            </div>--}}


{{--            <div class="card-body p-0">--}}

{{--                <div class="table-responsive">--}}

{{--                    <table class="table table-hover align-middle mb-0">--}}

{{--                        <thead class="table-light">--}}

{{--                        <tr>--}}

{{--                            <th>#</th>--}}

{{--                            <th>Order Number</th>--}}

{{--                            <th>Customer</th>--}}

{{--                            <th>Total</th>--}}

{{--                            <th>Payment</th>--}}

{{--                            <th>Order Status</th>--}}

{{--                            <th>Date</th>--}}

{{--                            <th class="text-end">--}}
{{--                                Action--}}
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
{{--                                        Unknown User--}}
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
{{--                                        Paid--}}
{{--                                    </span>--}}

{{--                                    @elseif($order->payment_status === 'pending')--}}

{{--                                        <span class="badge bg-warning text-dark">--}}
{{--                                        Pending--}}
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
{{--                                            Pending--}}
{{--                                        </span>--}}

{{--                                            @break--}}

{{--                                        @case('processing')--}}

{{--                                            <span class="badge bg-primary">--}}
{{--                                            Processing--}}
{{--                                        </span>--}}

{{--                                            @break--}}

{{--                                        @case('shipped')--}}

{{--                                            <span class="badge bg-info text-dark">--}}
{{--                                            Shipped--}}
{{--                                        </span>--}}

{{--                                            @break--}}

{{--                                        @case('delivered')--}}

{{--                                            <span class="badge bg-success">--}}
{{--                                            Delivered--}}
{{--                                        </span>--}}

{{--                                            @break--}}

{{--                                        @case('cancelled')--}}

{{--                                            <span class="badge bg-danger">--}}
{{--                                            Cancelled--}}
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

{{--                                        View--}}

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

{{--                                        No orders found.--}}

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

