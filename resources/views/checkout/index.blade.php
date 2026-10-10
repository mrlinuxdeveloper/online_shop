@extends('layouts.app')

@section('content')
    <style>
        .checkout-page {
            padding: 50px 0 80px;
            background: #f8f6f1;
            min-height: 70vh;
        }
        .checkout-title {
            font-size: 30px;
            font-weight: 800;
            color: #25352d;
        }
        .checkout-subtitle {
            color: #777;
            font-size: 14px;
        }
        .checkout-card,
        .summary-card {
            background: #fff;
            border: 1px solid #e8e3da;
            border-radius: 18px;
            box-shadow: 0 8px 30px rgba(0,0,0,.04);
            overflow: hidden;
        }
        .card-heading {
            padding: 20px 22px;
            border-bottom: 1px solid #eee9e1;
            color: #25352d;
            font-size: 18px;
            font-weight: 800;
        }
        .card-body-custom {
            padding: 22px;
        }
        .address-card {
            display: block;
            height: 100%;
            cursor: pointer;
            margin: 0;
        }
        .address-radio {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }
        .address-content {
            height: 100%;
            padding: 18px;
            border: 2px solid #e4dfd7;
            border-radius: 14px;
            background: #fff;
            transition: .2s;
        }
        .address-card:hover .address-content {
            border-color: #9b8975;
        }
        .address-radio:checked + .address-content {
            border-color: #25352d;
            background: #f5f7f3;
            box-shadow: 0 5px 18px rgba(37,53,45,.08);
        }
        .address-title {
            color: #25352d;
            font-weight: 800;
        }
        .default-badge {
            background: #25352d;
            color: #fff;
            border-radius: 20px;
            padding: 4px 9px;
            font-size: 11px;
        }
        .address-info {
            color: #777;
            font-size: 13px;
            line-height: 1.9;
        }
        .checkout-item {
            padding: 20px 22px;
            border-bottom: 1px solid #eee9e1;
        }
        .checkout-item:last-child {
            border-bottom: 0;
        }
        .product-image {
            width: 85px;
            height: 105px;
            object-fit: cover;
            border-radius: 12px;
            background: #f3f0eb;
        }
        .product-image-placeholder {
            width: 85px;
            height: 105px;
            border-radius: 12px;
            background: #f3f0eb;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 12px;
        }
        .product-name {
            color: #25352d;
            font-weight: 700;
            font-size: 16px;
        }
        .product-meta {
            color: #777;
            font-size: 12px;
            line-height: 1.9;
        }
        .summary-card {
            position: sticky;
            top: 25px;
        }
        .summary-body {
            padding: 23px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 0;
            color: #666;
        }
        .summary-row strong {
            color: #25352d;
        }
        .summary-total {
            border-top: 1px solid #eee9e1;
            margin-top: 15px;
            padding-top: 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #25352d;
            font-size: 20px;
            font-weight: 800;
        }
        .checkout-submit {
            width: 100%;
            border: 0;
            border-radius: 11px;
            background: #25352d;
            color: #fff;
            padding: 14px;
            font-weight: 700;
            margin-top: 22px;
            transition: .2s;
        }
        .checkout-submit:hover:not(:disabled) {
            background: #6f5138;
        }
        .checkout-submit:disabled {
            opacity: .5;
            cursor: not-allowed;
        }
        .back-cart {
            display: block;
            width: 100%;
            text-align: center;
            border: 1px solid #d8d1c8;
            border-radius: 11px;
            padding: 12px;
            color: #555;
            text-decoration: none;
            margin-top: 10px;
            transition: .2s;
        }
        .back-cart:hover {
            background: #f4f1ed;
            color: #25352d;
        }
        .coupon-input {
            direction: ltr;
            text-align: left;
            border-radius: 9px 0 0 9px !important;
        }
        .coupon-btn {
            border-radius: 0 9px 9px 0 !important;
        }
        .coupon-success {
            background: #f1f7f1;
            border: 1px solid #cfe1d1;
            border-radius: 12px;
            padding: 15px;
        }
        .note-input {
            border: 1px solid #ddd6cc;
            border-radius: 10px;
            resize: vertical;
        }
        .note-input:focus,
        .coupon-input:focus {
            border-color: #25352d;
            box-shadow: 0 0 0 .15rem rgba(37,53,45,.08);
        }
        .empty-address {
            background: #fff8e8;
            border: 1px solid #ead9ac;
            border-radius: 12px;
            padding: 16px;
        }
        @media (max-width: 991px) {
            .summary-card {
                position: static;
            }
        }
        @media (max-width: 767px) {
            .checkout-title {
                font-size: 24px;
            }
            .checkout-item {
                padding: 16px;
            }
            .product-image,
            .product-image-placeholder {
                width: 70px;
                height: 90px;
            }
        }
    </style>

    <div class="checkout-page">
        <div class="container">
            <div class="mb-4">
                <h1 class="checkout-title mb-1">تکمیل سفارش</h1>
                <p class="checkout-subtitle mb-0">
                    سفارش را مرور و نشانی ارسال را انتخاب کنید.
                </p>
            </div>

            @if(session('success'))
                <div class="alert alert-success rounded-3">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger rounded-3">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger rounded-3">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-4">
                <div class="col-lg-8">
                    <form id="checkout-form"
                          action="{{ route('checkout.store') }}"
                          method="POST">
                        @csrf

                        <div class="checkout-card mb-4">
                            <div class="card-heading">
                                نشانی ارسال
                            </div>

                            <div class="card-body-custom">
                                @if($addresses->isEmpty())
                                    <div class="empty-address">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                        <span>
                                            هنوز نشانی‌ای ذخیره نکرده‌اید.
                                        </span>

                                            <a href="{{ route('dashboard') }}"
                                               class="btn btn-sm btn-dark">
                                                افزودن نشانی
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="row g-3">
                                        @foreach($addresses as $address)
                                            <div class="col-md-6">
                                                <label class="address-card w-100"
                                                       for="address-{{ $address->id }}">
                                                    <input
                                                        type="radio"
                                                        name="address_id"
                                                        value="{{ $address->id }}"
                                                        id="address-{{ $address->id }}"
                                                        class="address-radio"
                                                        @checked(
                                                            old(
                                                                'address_id',
                                                                $addresses->firstWhere('is_default', true)?->id
                                                                ?? $addresses->first()?->id
                                                            ) == $address->id
                                                        )
                                                        required
                                                    >

                                                    <div class="address-content">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="address-title">
                                                            {{ $address->title }}
                                                        </span>

                                                            @if($address->is_default)
                                                                <span class="default-badge">
                                                                پیش‌فرض
                                                            </span>
                                                            @endif
                                                        </div>

                                                        <div class="address-info">
                                                            <div>
                                                                <strong>
                                                                    {{ $address->receiver_name }}
                                                                </strong>
                                                            </div>

                                                            <div>
                                                                {{ $address->receiver_phone }}
                                                            </div>

                                                            <div>
                                                                {{ $address->province }}
                                                                -
                                                                {{ $address->city }}
                                                            </div>

                                                            <div>
                                                                {{ $address->address }}
                                                            </div>

                                                            <div>
                                                                کد پستی:
                                                                {{ $address->postal_code }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="checkout-card mb-4">
                            <div class="card-heading">
                                اقلام سفارش
                            </div>

                            <div>
                                @foreach($cart->items as $item)
                                    <div class="checkout-item">
                                        <div class="row align-items-center g-3">
                                            <div class="col-auto">
                                                @php
                                                    $primaryImage = $item->product->images
                                                        ->firstWhere('is_primary', true);
                                                    $image = $primaryImage
                                                        ?? $item->product->images->first();
                                                @endphp

                                                @if($image)
                                                    <img
                                                        src="{{ asset('storage/' . $image->image) }}"
                                                        alt="{{ $item->product->name }}"
                                                        class="product-image"
                                                    >
                                                @else
                                                    <div class="product-image-placeholder">
                                                        بدون تصویر
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="col">
                                                <div class="product-name mb-1">
                                                    {{ $item->product->name }}
                                                </div>

                                                <div class="product-meta">
                                                    <div>
                                                        کد محصول:
                                                        {{ $item->product->product_code }}
                                                    </div>

                                                    @if($item->variant?->size)
                                                        <div>
                                                            سایز:
                                                            {{ $item->variant->size->name }}
                                                        </div>
                                                    @endif

                                                    @if($item->variant?->color)
                                                        <div>
                                                            رنگ:
                                                            {{ $item->variant->color->name }}
                                                        </div>
                                                    @endif

                                                    @if($item->variant?->sku)
                                                        <div>
                                                            شناسهٔ کالا:
                                                            {{ $item->variant->sku }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-auto text-center">
                                                <div class="product-meta mb-1">
                                                    تعداد
                                                </div>
                                                <strong>
                                                    {{ $item->quantity }}
                                                </strong>
                                            </div>

                                            <div class="col-auto text-end">
                                                <div class="product-meta mb-1">
                                                    قیمت
                                                </div>
                                                <strong>
                                                    {{ number_format($item->price) }}
                                                </strong>
                                            </div>

                                            <div class="col-auto text-end">
                                                <div class="product-meta mb-1">
                                                    مبلغ نهایی
                                                </div>
                                                <strong class="text-dark">
                                                    {{ number_format($item->price * $item->quantity) }}
                                                </strong>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="checkout-card mb-4">
                            <div class="card-heading">
                                یادداشت سفارش
                            </div>

                            <div class="card-body-custom">
                            <textarea
                                name="note"
                                class="form-control note-input"
                                rows="4"
                                placeholder="یادداشتی برای سفارش بنویسید (اختیاری)"
                            >{{ old('note') }}</textarea>
                            </div>
                        </div>
                    </form>

                    <div class="checkout-card mb-4">
                        <div class="card-heading">
                            تخفیف کد
                        </div>

                        <div class="card-body-custom">
                            @if(session('checkout_coupon'))
                                <div class="coupon-success">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                        <div>
                                            <strong class="text-success">
                                                کد تخفیف اعمال شد
                                            </strong>

                                            <div class="small mt-1">
                                                کد:
                                                <strong>
                                                    {{ session('checkout_coupon.code') }}
                                                </strong>
                                            </div>
                                        </div>

                                        <strong class="text-success">
                                            -{{ number_format(session('checkout_coupon.discount')) }}
                                            تومان
                                        </strong>
                                    </div>
                                </div>
                            @else
                                <form action="{{ route('checkout.coupon') }}"
                                      method="POST">
                                    @csrf

                                    <label for="coupon_code"
                                           class="form-label">
                                        کد تخفیف
                                    </label>

                                    <div class="input-group">
                                        <input
                                            type="text"
                                            name="coupon_code"
                                            id="coupon_code"
                                            class="form-control coupon-input @error('coupon_code') is-invalid @enderror"
                                            value="{{ old('coupon_code') }}"
                                            placeholder="کد تخفیف را وارد کنید"
                                            autocomplete="off"
                                            required
                                        >

                                        <button type="submit"
                                                class="btn btn-dark coupon-btn">
                                            اعمال
                                        </button>
                                    </div>

                                    @error('coupon_code')
                                    <div class="text-danger small mt-2">
                                        {{ $message }}
                                    </div>
                                    @enderror

                                    <div class="text-muted small mt-2">
                                        کد تخفیف را وارد کنید و روی «اعمال» بزنید.
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="summary-card">
                        <div class="card-heading">
                            خلاصه سفارش
                        </div>

                        <div class="summary-body">
                            <div class="summary-row">
                                <span>جمع جزء</span>
                                <strong>
                                    {{ number_format($subtotal) }}
                                </strong>
                            </div>

                            <div class="summary-row">
                                <span>تخفیف</span>
                                <strong class="text-success">
                                    -{{ number_format($discount) }}
                                </strong>
                            </div>

                            <div class="summary-row">
                                <span>هزینه ارسال</span>
                                <strong>
                                    {{ number_format($shippingCost) }}
                                </strong>
                            </div>

                            <div class="summary-row">
                                <span>مالیات</span>
                                <strong>
                                    {{ number_format($tax) }}
                                </strong>
                            </div>

                            <div class="summary-مجموع">
                                <span>مبلغ نهایی</span>
                                <strong>
                                    {{ number_format($total) }}
                                </strong>
                            </div>

                            <div class="text-muted small mt-2">
                                واحد پول: تومان
                            </div>

                            <button
                                type="submit"
                                form="checkout-form"
                                class="checkout-submit"
                                @disabled($addresses->isEmpty())
                            >
                                ثبت سفارش
                            </button>

                            <a href="{{ route('cart.index') }}"
                               class="back-cart">
                                بازگشت به سبد خرید
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
