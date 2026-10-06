@extends('admin.layouts.AdminMasterLayout')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h4 class="fw-bold mb-1">
                    جزئیات ارسال
                </h4>

                <p class="text-muted mb-0">
                    سفارش:
                    {{ $shipment->order->order_number ?? '-' }}
                </p>

            </div>

            <div class="d-flex gap-2">

                <a
                    href="{{ route('admin.shipments.edit', $shipment) }}"
                    class="btn btn-outline-primary"
                >
                    ویرایش
                </a>

                <a
                    href="{{ route('admin.shipments.index') }}"
                    class="btn btn-secondary"
                >
                    بازگشت
                </a>

            </div>

        </div>


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


        <div class="row g-4">


            {{-- Shipment Information --}}

            <div class="col-lg-6">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">
                            اطلاعات ارسال
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="mb-3">

                            <strong>
                                روش ارسال:
                            </strong>

                            <span class="ms-2">
                            {{ $shipment->shipping_method }}
                        </span>

                        </div>


                        <div class="mb-3">

                            <strong>
                                کد رهگیری:
                            </strong>

                            <span class="ms-2 font-monospace">

                            {{ $shipment->tracking_code ?? 'ثبت نشده' }}

                        </span>

                        </div>


                        <div class="mb-3">

                            <strong>
                                وضعیت:
                            </strong>

                            <span class="ms-2">

                            @switch($shipment->status)

                                    @case('pending')

                                        <span class="badge bg-secondary">
                                        در انتظار
                                    </span>

                                        @break

                                    @case('processing')

                                        <span class="badge bg-warning text-dark">
                                        در حال آماده‌سازی
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

                        </span>

                        </div>


                        <div class="mb-3">

                            <strong>
                                تاریخ ارسال:
                            </strong>

                            <span class="ms-2">

                            @if($shipment->shipped_at)

                                    {{ $shipment->shipped_at->format('Y/m/d H:i') }}

                                @else

                                    ثبت نشده

                                @endif

                        </span>

                        </div>


                        <div>

                            <strong>
                                تاریخ تحویل:
                            </strong>

                            <span class="ms-2">

                            @if($shipment->delivered_at)

                                    {{ $shipment->delivered_at->format('Y/m/d H:i') }}

                                @else

                                    ثبت نشده

                                @endif

                        </span>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Order Information --}}

            <div class="col-lg-6">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">
                            اطلاعات سفارش
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="mb-3">

                            <strong>
                                شماره سفارش:
                            </strong>

                            <span class="ms-2">
                            {{ $shipment->order->order_number ?? '-' }}
                        </span>

                        </div>


                        <div class="mb-3">

                            <strong>
                                مشتری:
                            </strong>

                            <span class="ms-2">

                            {{ $shipment->order->user->name ?? '-' }}
                                {{ $shipment->order->user->lastname ?? '' }}

                        </span>

                        </div>


                        @if($shipment->order->user?->email)

                            <div class="mb-3">

                                <strong>
                                    ایمیل:
                                </strong>

                                <span class="ms-2">
                                {{ $shipment->order->user->email }}
                            </span>

                            </div>

                        @endif


                        @if($shipment->order->address)

                            <div>

                                <strong>
                                    آدرس:
                                </strong>

                                <div class="mt-2 text-muted">

                                    {{ $shipment->order->address->province }}
                                    -
                                    {{ $shipment->order->address->city }}

                                    <br>

                                    {{ $shipment->order->address->address }}

                                    <br>

                                    کد پستی:
                                    {{ $shipment->order->address->postal_code }}

                                </div>

                            </div>

                        @endif

                    </div>

                </div>

            </div>


            {{-- Change Status --}}

            <div class="col-12">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">
                            تغییر وضعیت ارسال
                        </h5>

                    </div>


                    <div class="card-body">

                        <form
                            action="{{ route('admin.shipments.update-status', $shipment) }}"
                            method="POST"
                            class="row g-3 align-items-end"
                        >

                            @csrf
                            @method('PATCH')


                            <div class="col-md-6">

                                <label
                                    for="status"
                                    class="form-label"
                                >
                                    وضعیت جدید
                                </label>

                                <select
                                    name="status"
                                    id="status"
                                    class="form-select"
                                    required
                                >

                                    <option
                                        value="pending"
                                        @selected($shipment->status === 'pending')
                                    >
                                        در انتظار
                                    </option>

                                    <option
                                        value="processing"
                                        @selected($shipment->status === 'processing')
                                    >
                                        در حال آماده‌سازی
                                    </option>

                                    <option
                                        value="shipped"
                                        @selected($shipment->status === 'shipped')
                                    >
                                        ارسال شده
                                    </option>

                                    <option
                                        value="delivered"
                                        @selected($shipment->status === 'delivered')
                                    >
                                        تحویل شده
                                    </option>

                                    <option
                                        value="returned"
                                        @selected($shipment->status === 'returned')
                                    >
                                        مرجوع شده
                                    </option>

                                </select>

                            </div>


                            <div class="col-md-auto">

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    تغییر وضعیت
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>


            {{-- Order Items --}}

            <div class="col-12">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">
                            اقلام سفارش
                        </h5>

                    </div>


                    <div class="card-body p-0">

                        <div class="table-responsive">

                            <table class="table table-hover align-middle mb-0">

                                <thead class="table-light">

                                <tr>

                                    <th>
                                        محصول
                                    </th>

                                    <th>
                                        سایز
                                    </th>

                                    <th>
                                        رنگ
                                    </th>

                                    <th>
                                        تعداد
                                    </th>

                                    <th>
                                        قیمت
                                    </th>

                                </tr>

                                </thead>


                                <tbody>

                                @forelse($shipment->order->items as $item)

                                    <tr>

                                        <td>

                                            {{ $item->product_name }}

                                        </td>


                                        <td>

                                            {{ $item->size ?? '-' }}

                                        </td>


                                        <td>

                                            {{ $item->color ?? '-' }}

                                        </td>


                                        <td>

                                            {{ $item->quantity }}

                                        </td>


                                        <td>

                                            {{ number_format($item->price) }}

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="5"
                                            class="text-center py-4 text-muted"
                                        >
                                            آیتمی برای این سفارش وجود ندارد.
                                        </td>

                                    </tr>

                                @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
