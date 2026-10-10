@extends('layouts.app')

@section('content')

    <div class="container py-5">


        <div class="mb-4">

            <h2>
                افزودن نشانی جدید
            </h2>

            <p class="text-muted">
                اطلاعات ارسال را وارد کنید.
            </p>

        </div>


        <form
            action="{{ route('addresses.store') }}"
            method="POST"
        >

            @csrf

            <div class="row g-3">

                <div class="col-md-6">

                    <label class="form-label">
                        عنوان نشانی
                    </label>

                    <input
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        class="form-control"
                        placeholder="خانه"
                        required
                    >

                    @error('title')
                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>
                    @enderror

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        نام گیرنده
                    </label>

                    <input
                        type="text"
                        name="receiver_name"
                        value="{{ old('receiver_name', auth()->user()->name) }}"
                        class="form-control"
                        required
                    >

                    @error('receiver_name')
                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>
                    @enderror

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        تلفن گیرنده
                    </label>

                    <input
                        type="text"
                        name="receiver_phone"
                        value="{{ old('receiver_phone', auth()->user()->tel) }}"
                        class="form-control"
                        required
                    >

                    @error('receiver_phone')
                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>
                    @enderror

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        استان
                    </label>

                    <input
                        type="text"
                        name="province"
                        value="{{ old('province') }}"
                        class="form-control"
                        required
                    >

                    @error('province')
                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>
                    @enderror

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        شهر
                    </label>

                    <input
                        type="text"
                        name="city"
                        value="{{ old('city') }}"
                        class="form-control"
                        required
                    >

                    @error('city')
                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>
                    @enderror

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        کد پستی
                    </label>

                    <input
                        type="text"
                        name="postal_code"
                        value="{{ old('postal_code') }}"
                        class="form-control"
                        required
                    >

                    @error('postal_code')
                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>
                    @enderror

                </div>


                <div class="col-12">

                    <label class="form-label">
                        نشانی کامل
                    </label>

                    <textarea
                        name="address"
                        rows="4"
                        class="form-control"
                        required
                    >{{ old('address') }}</textarea>

                    @error('address')
                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>
                    @enderror

                </div>


                <div class="col-12">

                    <div class="form-check">

                        <input
                            type="checkbox"
                            name="is_default"
                            value="1"
                            id="is_default"
                            class="form-check-input"
                        >

                        <label
                            for="is_default"
                            class="form-check-label"
                        >
                            انتخاب به‌عنوان نشانی پیش‌فرض
                        </label>

                    </div>

                </div>


                <div class="col-12 mt-4">

                    <button
                        type="submit"
                        class="btn btn-dark"
                    >
                        ذخیرهٔ نشانی
                    </button>

                    <a
                        href="{{ route('addresses.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        لغو
                    </a>

                </div>

            </div>

        </form>

    </div>

@endsection
