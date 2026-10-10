@extends('layouts.app')

@section('content')

    <div class="container py-5">

        <div class="mb-4">

            <h2>
                ویرایش نشانی
            </h2>

            <p class="text-muted">
                اطلاعات ارسال را ویرایش کنید.
            </p>

        </div>


        <form
            action="{{ route('addresses.update', $address) }}"
            method="POST"
        >

            @csrf
            @method('PUT')

            <div class="row g-3">

                <div class="col-md-6">

                    <label class="form-label">
                        عنوان نشانی
                    </label>

                    <input
                        type="text"
                        name="title"
                        value="{{ old('title', $address->title) }}"
                        class="form-control"
                        required
                    >

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        نام گیرنده
                    </label>

                    <input
                        type="text"
                        name="receiver_name"
                        value="{{ old('receiver_name', $address->receiver_name) }}"
                        class="form-control"
                        required
                    >

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        تلفن گیرنده
                    </label>

                    <input
                        type="text"
                        name="receiver_phone"
                        value="{{ old('receiver_phone', $address->receiver_phone) }}"
                        class="form-control"
                        required
                    >

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        استان
                    </label>

                    <input
                        type="text"
                        name="province"
                        value="{{ old('province', $address->province) }}"
                        class="form-control"
                        required
                    >

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        شهر
                    </label>

                    <input
                        type="text"
                        name="city"
                        value="{{ old('city', $address->city) }}"
                        class="form-control"
                        required
                    >

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        کد پستی
                    </label>

                    <input
                        type="text"
                        name="postal_code"
                        value="{{ old('postal_code', $address->postal_code) }}"
                        class="form-control"
                        required
                    >

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
                    >{{ old('address', $address->address) }}</textarea>

                </div>


                <div class="col-12">

                    <div class="form-check">

                        <input
                            type="checkbox"
                            name="is_default"
                            value="1"
                            id="is_default"
                            class="form-check-input"
                            @checked(old('is_default', $address->is_default))
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
                        به‌روزرسانی نشانی
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
