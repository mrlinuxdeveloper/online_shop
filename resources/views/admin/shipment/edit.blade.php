@extends('admin.layouts.AdminMasterLayout')

@section('content')

    <div class="container-fluid">

        <div class="mb-4">

            <h4 class="fw-bold">
                ویرایش ارسال
            </h4>

            <p class="text-muted">
                سفارش:
                {{ $shipment->order->order_number ?? '-' }}
            </p>

        </div>


        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <form
                    action="{{ route('admin.shipments.update', $shipment) }}"
                    method="POST"
                >

                    @csrf
                    @method('PUT')


                    <div class="row g-3">


                        {{-- Order --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                سفارش
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ $shipment->order->order_number ?? '-' }}"
                                disabled
                            >

                        </div>


                        {{-- روش ارسال --}}

                        <div class="col-md-6">

                            <label
                                for="shipping_method"
                                class="form-label"
                            >
                                روش ارسال
                            </label>

                            <input
                                type="text"
                                name="shipping_method"
                                id="shipping_method"
                                class="form-control @error('shipping_method') is-invalid @enderror"
                                value="{{ old('shipping_method', $shipment->shipping_method) }}"
                                required
                            >

                            @error('shipping_method')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>


                        {{-- کد رهگیری --}}

                        <div class="col-md-6">

                            <label
                                for="tracking_code"
                                class="form-label"
                            >
                                کد رهگیری
                            </label>

                            <input
                                type="text"
                                name="tracking_code"
                                id="tracking_code"
                                class="form-control @error('tracking_code') is-invalid @enderror"
                                value="{{ old('tracking_code', $shipment->tracking_code) }}"
                            >

                            @error('tracking_code')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>


                        {{-- وضعیت --}}

                        <div class="col-md-6">

                            <label
                                for="status"
                                class="form-label"
                            >
                                وضعیت
                            </label>

                            <select
                                name="status"
                                id="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required
                            >

                                <option
                                    value="pending"
                                    @selected(old('status', $shipment->status) === 'pending')
                                >
                                    در انتظار
                                </option>

                                <option
                                    value="processing"
                                    @selected(old('status', $shipment->status) === 'processing')
                                >
                                    در حال آماده‌سازی
                                </option>

                                <option
                                    value="shipped"
                                    @selected(old('status', $shipment->status) === 'shipped')
                                >
                                    ارسال شده
                                </option>

                                <option
                                    value="delivered"
                                    @selected(old('status', $shipment->status) === 'delivered')
                                >
                                    تحویل شده
                                </option>

                                <option
                                    value="returned"
                                    @selected(old('status', $shipment->status) === 'returned')
                                >
                                    مرجوع شده
                                </option>

                            </select>

                            @error('status')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>


                        {{-- تاریخ ارسال --}}

                        <div class="col-md-6">

                            <label
                                for="shipped_at"
                                class="form-label"
                            >
                                تاریخ ارسال
                            </label>

                            <input
                                type="datetime-local"
                                name="shipped_at"
                                id="shipped_at"
                                class="form-control @error('shipped_at') is-invalid @enderror"
                                value="{{ old(
                                'shipped_at',
                                $shipment->shipped_at
                                    ? $shipment->shipped_at->format('Y-m-d\TH:i')
                                    : ''
                            ) }}"
                            >

                            @error('shipped_at')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>


                        {{-- تاریخ تحویل --}}

                        <div class="col-md-6">

                            <label
                                for="delivered_at"
                                class="form-label"
                            >
                                تاریخ تحویل
                            </label>

                            <input
                                type="datetime-local"
                                name="delivered_at"
                                id="delivered_at"
                                class="form-control @error('delivered_at') is-invalid @enderror"
                                value="{{ old(
                                'delivered_at',
                                $shipment->delivered_at
                                    ? $shipment->delivered_at->format('Y-m-d\TH:i')
                                    : ''
                            ) }}"
                            >

                            @error('delivered_at')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>


                    </div>


                    <div class="mt-4 d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            ذخیره تغییرات
                        </button>

                        <a
                            href="{{ route('admin.shipments.show', $shipment) }}"
                            class="btn btn-secondary"
                        >
                            انصراف
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection
