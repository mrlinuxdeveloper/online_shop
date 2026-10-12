@extends('admin.layouts.AdminMasterLayout')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h4 class="fw-bold mb-1">
                    مدیریت ارسال‌ها
                </h4>

                <p class="text-muted mb-0">
                    لیست ارسال سفارش‌ها
                </p>
            </div>

            <a
                href="{{ route('admin.shipments.create') }}"
                class="btn btn-primary"
            >
                ایجاد ارسال جدید
            </a>

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


        <div class="card border-0 shadow-sm">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>
                            <th>#</th>
                            <th>شماره سفارش</th>
                            <th>کاربر</th>
                            <th>روش ارسال</th>
                            <th>کد رهگیری</th>
                            <th>وضعیت</th>
                            <th>تاریخ ارسال</th>
                            <th>عملیات</th>
                        </tr>

                        </thead>

                        <tbody>

                        @forelse($shipments as $shipment)

                            <tr>

                                <td>
                                    {{ $shipment->id }}
                                </td>


                                <td>

                                    <a
                                        href="{{ route('admin.shipments.show', $shipment) }}"
                                        class="text-decoration-none fw-bold"
                                    >
                                        {{ $shipment->order->order_number ?? '-' }}
                                    </a>

                                </td>


                                <td>

                                    @if($shipment->order?->user)

                                        {{ $shipment->order->user->name }}
                                        {{ $shipment->order->user->lastname }}

                                    @else

                                        -

                                    @endif

                                </td>


                                <td>
                                    {{ $shipment->shipping_method }}
                                </td>


                                <td>

                                    @if($shipment->tracking_code)

                                        <span class="font-monospace">
                                        {{ $shipment->tracking_code }}
                                    </span>

                                    @else

                                        <span class="text-muted">
                                        ندارد
                                    </span>

                                    @endif

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

                                </td>


                                <td>

                                    @if($shipment->shipped_at)

                                        {{ $shipment->shipped_at->format('Y/m/d H:i') }}

                                    @else

                                        -

                                    @endif

                                </td>


                                <td>

                                    <div class="d-flex gap-1">

                                        <a
                                            href="{{ route('admin.shipments.show', $shipment) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            مشاهده
                                        </a>

                                        <a
                                            href="{{ route('admin.shipments.edit', $shipment) }}"
                                            class="btn btn-sm btn-outline-secondary"
                                        >
                                            ویرایش
                                        </a>

                                        <form
                                            action="{{ route('admin.shipments.destroy', $shipment) }}"
                                            method="POST"
                                            onsubmit="return confirm('آیا از حذف این ارسال مطمئن هستید؟')"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                            >
                                                حذف
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-5 text-muted"
                                >
                                    هنوز هیچ ارسال ثبت نشده است.
                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <div class="mt-4">

            {{ $shipments->links() }}

        </div>

    </div>

@endsection
