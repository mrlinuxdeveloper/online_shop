@extends('admin.layouts.AdminMasterLayout')

@section('content')

    <div class="container-fluid">

        <div class="mb-4">

            <h4 class="fw-bold">
                ایجاد ارسال جدید
            </h4>

            <p class="text-muted">
                برای یک سفارش پرداخت‌شده اطلاعات ارسال را ثبت کنید.
            </p>

        </div>


        @if(session('error'))

            <div class="alert alert-danger">
                {{ session('error') }}
            </div>

        @endif


        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <form
                    action="{{ route('admin.shipments.store') }}"
                    method="POST"
                >

                    @csrf


                    <div class="row g-3">


                        {{-- Order --}}

                        <div class="col-md-6">

                            <label
                                for="order_id"
                                class="form-label"
                            >
                                سفارش
                            </label>

                            <select
                                name="order_id"
                                id="order_id"
                                class="form-select @error('order_id') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    انتخاب سفارش
                                </option>

                                @foreach($orders as $order)

                                    <option
                                        value="{{ $order->id }}"
                                        @selected(old('order_id') == $order->id)
                                    >

                                        {{ $order->order_number }}

                                        -
                                        {{ $order->user->name ?? 'کاربر' }}

                                        {{ $order->user->lastname ?? '' }}

                                    </option>

                                @endforeach

                            </select>


                            @error('order_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>


                        {{-- Shipping Method --}}

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
                                value="{{ old('shipping_method') }}"
                                placeholder="مثلاً پست پیشتاز"
                                required
                            >

                            @error('shipping_method')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>


                        {{-- Tracking Code --}}

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
                                value="{{ old('tracking_code') }}"
                            >

                            @error('tracking_code')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>


                        {{-- Status --}}

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
                                    @selected(old('status', 'pending') === 'pending')
                                >
                                    در انتظار
                                </option>

                                <option
                                    value="processing"
                                    @selected(old('status') === 'processing')
                                >
                                    در حال آماده‌سازی
                                </option>

                                <option
                                    value="shipped"
                                    @selected(old('status') === 'shipped')
                                >
                                    ارسال شده
                                </option>

                                <option
                                    value="delivered"
                                    @selected(old('status') === 'delivered')
                                >
                                    تحویل شده
                                </option>

                                <option
                                    value="returned"
                                    @selected(old('status') === 'returned')
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


                        {{-- Shipped At --}}

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
                                value="{{ old('shipped_at') }}"
                            >

                            @error('shipped_at')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>


                        {{-- Delivered At --}}

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
                                value="{{ old('delivered_at') }}"
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
                            ثبت ارسال
                        </button>

                        <a
                            href="{{ route('admin.shipments.index') }}"
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
