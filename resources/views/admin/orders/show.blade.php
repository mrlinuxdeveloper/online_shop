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

    </div>

@endsection

