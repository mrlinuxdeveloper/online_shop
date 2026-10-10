@extends('layouts.app')

@section('content')

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h2 class="mb-1">نشانی‌های من</h2>

                <p class="text-muted mb-0">
                    نشانی‌های ارسال خود را مدیریت کنید
                </p>
            </div>

            <a
                href="{{ route('addresses.create') }}"
                class="btn btn-dark"
            >
                افزودن نشانی جدید
            </a>

        </div>


        @if(session('success'))

            <div class="alert alert-success">
                {{ session('success') }}
            </div>

        @endif


        @if($addresses->isEmpty())

            <div class="card border-0 shadow-sm">

                <div class="card-body text-center py-5">

                    <h5>
                        هنوز نشانی‌ای ذخیره نکرده‌اید.
                    </h5>

                    <p class="text-muted">
                        برای ادامهٔ ثبت سفارش، یک نشانی اضافه کنید.
                    </p>

                    <a
                        href="{{ route('addresses.create') }}"
                        class="btn btn-dark"
                    >
                        افزودن نشانی
                    </a>

                </div>

            </div>

        @else

            <div class="row g-4">

                @foreach($addresses as $address)

                    <div class="col-md-6">

                        <div class="card h-100 shadow-sm">

                            <div class="card-body">

                                <div class="d-flex justify-content-between align-items-start">

                                    <div>

                                        <h5 class="mb-1">
                                            {{ $address->title }}
                                        </h5>

                                        @if($address->is_default)

                                            <span class="badge bg-success">
                                        پیش‌فرض
                                    </span>

                                        @endif

                                    </div>

                                </div>


                                <hr>


                                <div class="mb-2">
                                    <strong>گیرنده:</strong>
                                    {{ $address->receiver_name }}
                                </div>

                                <div class="mb-2">
                                    <strong>تلفن:</strong>
                                    {{ $address->receiver_phone }}
                                </div>

                                <div class="mb-2">
                                    <strong>استان:</strong>
                                    {{ $address->province }}
                                </div>

                                <div class="mb-2">
                                    <strong>شهر:</strong>
                                    {{ $address->city }}
                                </div>

                                <div class="mb-2">
                                    <strong>کد پستی:</strong>
                                    {{ $address->postal_code }}
                                </div>

                                <div class="mb-3">
                                    <strong>نشانی:</strong>
                                    {{ $address->address }}
                                </div>


                                <div class="d-flex gap-2">

                                    <a
                                        href="{{ route('addresses.edit', $address) }}"
                                        class="btn btn-outline-dark btn-sm"
                                    >
                                        ویرایش
                                    </a>

                                    <form
                                        action="{{ route('addresses.destroy', $address) }}"
                                        method="POST"
                                        onsubmit="return confirm('این نشانی حذف شود؟')"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-outline-danger btn-sm"
                                        >
                                            حذف
                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @endif

    </div>

@endsection
