@extends('admin.layouts.AdminMasterLayout')

@section('title', 'Order Details')

@section('page-title', 'Order Details')

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
                        Invoice
                    </a>

                    <a
                        href="{{ route('admin.orders.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        Back
                    </a>

                </div>

            </div>

        </div>


        <div class="row g-4">

            {{-- Customer --}}
            <div class="col-md-6">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header bg-white">
                        <strong>Customer</strong>
                    </div>

                    <div class="card-body">

                        @if($order->user)

                            <p class="mb-2">
                                <strong>Name:</strong>
                                {{ $order->user->name }}
                                {{ $order->user->lastname }}
                            </p>

                            <p class="mb-2">
                                <strong>Email:</strong>
                                {{ $order->user->email }}
                            </p>

                            <p class="mb-0">
                                <strong>Phone:</strong>
                                {{ $order->user->tel ?? '-' }}
                            </p>

                        @else

                            <span class="text-muted">
                            User not found.
                        </span>

                        @endif

                    </div>

                </div>

            </div>


            {{-- Address --}}
            <div class="col-md-6">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header bg-white">
                        <strong>Shipping Address</strong>
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
                                <strong>Receiver:</strong>
                                {{ $order->address->receiver_name }}
                            </p>

                            <p class="mb-0">
                                <strong>Phone:</strong>
                                {{ $order->address->receiver_phone }}
                            </p>

                        @else

                            <span class="text-muted">
                            Address not found.
                        </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- Products --}}
        <div class="card shadow-sm border-0 mt-4">

            <div class="card-header bg-white">

                <strong>
                    Order Items
                </strong>

            </div>


            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead class="table-light">

                    <tr>

                        <th>Product</th>

                        <th>Code</th>

                        <th>Size</th>

                        <th>Color</th>

                        <th>Price</th>

                        <th>Quantity</th>

                        <th>Total</th>

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
                                            'storage/products/' .
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


        {{-- Total --}}
        <div class="row justify-content-end mt-4">

            <div class="col-md-5">

                <div class="card shadow-sm border-0">

                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-2">

                        <span>
                            Subtotal
                        </span>

                            <strong>
                                {{ number_format($order->subtotal) }}
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-2">

                        <span>
                            Discount
                        </span>

                            <strong>
                                {{ number_format($order->discount) }}
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-2">

                        <span>
                            Shipping
                        </span>

                            <strong>
                                {{ number_format($order->shipping_cost) }}
                            </strong>

                        </div>


                        <hr>


                        <div class="d-flex justify-content-between">

                            <strong>
                                Total
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


        {{-- Status --}}
        <div class="card shadow-sm border-0 mt-4">

            <div class="card-header bg-white">
                <strong>Order Status</strong>
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
                                Status
                            </label>

                            <select
                                name="status"
                                class="form-select"
                            >

                                <option
                                    value="pending"
                                    @selected($order->status === 'pending')
                                >
                                    Pending
                                </option>

                                <option
                                    value="processing"
                                    @selected($order->status === 'processing')
                                >
                                    Processing
                                </option>

                                <option
                                    value="shipped"
                                    @selected($order->status === 'shipped')
                                >
                                    Shipped
                                </option>

                                <option
                                    value="delivered"
                                    @selected($order->status === 'delivered')
                                >
                                    Delivered
                                </option>

                                <option
                                    value="cancelled"
                                    @selected($order->status === 'cancelled')
                                >
                                    Cancelled
                                </option>

                            </select>

                        </div>


                        <div class="col-md-auto">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Update Status
                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>
// payment status

        {{-- Payment --}}

        <div class="card shadow-sm border-0 mt-4">


            <div class="card-header bg-white">
                <strong>Payment</strong>
            </div>

            <div class="card-body">

                <div class="row mb-4">

                    <div class="col-md-4">

                        <small class="text-muted d-block mb-1">
                            Payment Status
                        </small>

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

                    </div>

                    <div class="col-md-4">

                        <small class="text-muted d-block mb-1">
                            Order Total
                        </small>

                        <strong>
                            {{ number_format($order->total_price) }}
                            تومان
                        </strong>

                    </div>

                    <div class="col-md-4">

                        <small class="text-muted d-block mb-1">
                            Payment Records
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
                                Change Payment Status
                            </label>

                            <select
                                name="payment_status"
                                class="form-select"
                            >

                                <option
                                    value="pending"
                                    @selected($order->payment_status === 'pending')
                                >
                                    Pending
                                </option>

                                <option
                                    value="paid"
                                    @selected($order->payment_status === 'paid')
                                >
                                    Paid
                                </option>

                                <option
                                    value="failed"
                                    @selected($order->payment_status === 'failed')
                                >
                                    Failed
                                </option>

                                <option
                                    value="refunded"
                                    @selected($order->payment_status === 'refunded')
                                >
                                    Refunded
                                </option>

                            </select>

                        </div>

                        <div class="col-md-auto">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Update Payment Status
                            </button>

                        </div>

                    </div>

                </form>

            </div>


        </div>


{{--        <div class="card mb-4">--}}
{{--            <div class="card-header">--}}
{{--                <h5 class="mb-0">Payment Status</h5>--}}
{{--            </div>--}}

{{--            <div class="card-body">--}}

{{--                <form action="{{ route('admin.orders.payment-status', $order) }}"--}}
{{--                      method="POST">--}}

{{--                    @csrf--}}
{{--                    @method('PATCH')--}}

{{--                    <div class="row align-items-end">--}}

{{--                        <div class="col-md-6">--}}

{{--                            <label class="form-label">--}}
{{--                                Payment Status--}}
{{--                            </label>--}}

{{--                            <select name="payment_status"--}}
{{--                                    class="form-select">--}}

{{--                                <option value="pending"--}}
{{--                                    {{ $order->payment_status === 'pending' ? 'selected' : '' }}>--}}
{{--                                    Pending--}}
{{--                                </option>--}}

{{--                                <option value="paid"--}}
{{--                                    {{ $order->payment_status === 'paid' ? 'selected' : '' }}>--}}
{{--                                    Paid--}}
{{--                                </option>--}}

{{--                                <option value="failed"--}}
{{--                                    {{ $order->payment_status === 'failed' ? 'selected' : '' }}>--}}
{{--                                    Failed--}}
{{--                                </option>--}}

{{--                                <option value="refunded"--}}
{{--                                    {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>--}}
{{--                                    Refunded--}}
{{--                                </option>--}}

{{--                            </select>--}}

{{--                        </div>--}}

{{--                        <div class="col-md-3">--}}

{{--                            <button type="submit"--}}
{{--                                    class="btn btn-primary">--}}
{{--                                Update Payment Status--}}
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
                    Shipment
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


                        {{-- Shipping Method --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Shipping Company / Method
                            </label>

                            <input
                                type="text"
                                name="shipping_method"
                                class="form-control"
                                value="{{ $order->shipment?->shipping_method }}"
                                placeholder="Post, Tipax, ..."
                            >

                        </div>


                        {{-- Tracking Code --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Tracking Code
                            </label>

                            <input
                                type="text"
                                name="tracking_code"
                                class="form-control"
                                value="{{ $order->shipment?->tracking_code }}"
                                placeholder="Tracking number"
                            >

                        </div>


                        {{-- Status --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Shipment Status
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
                                    Pending
                                </option>


                                <option
                                    value="processing"
                                    @selected(
                                        $order->shipment?->status === 'processing'
                                    )
                                >
                                    Processing
                                </option>


                                <option
                                    value="shipped"
                                    @selected(
                                        $order->shipment?->status === 'shipped'
                                    )
                                >
                                    Shipped
                                </option>


                                <option
                                    value="delivered"
                                    @selected(
                                        $order->shipment?->status === 'delivered'
                                    )
                                >
                                    Delivered
                                </option>


                                <option
                                    value="returned"
                                    @selected(
                                        $order->shipment?->status === 'returned'
                                    )
                                >
                                    Returned
                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- Current Shipment Information --}}
                    @if($order->shipment)

                        <div class="row mt-4">

                            <div class="col-md-4">

                                <small class="text-muted d-block mb-1">
                                    Shipped At
                                </small>

                                <strong>
                                    {{ $order->shipment->shipped_at?->format('Y-m-d H:i') ?? '-' }}
                                </strong>

                            </div>


                            <div class="col-md-4">

                                <small class="text-muted d-block mb-1">
                                    Delivered At
                                </small>

                                <strong>
                                    {{ $order->shipment->delivered_at?->format('Y-m-d H:i') ?? '-' }}
                                </strong>

                            </div>


                            <div class="col-md-4">

                                <small class="text-muted d-block mb-1">
                                    Current Status
                                </small>


                                @switch($order->shipment->status)

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


                                    @case('returned')

                                        <span class="badge bg-danger">
                                Returned
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
                            Update Shipment
                        </button>

                    </div>

                </form>

            </div>


        </div>


    </div>

@endsection

