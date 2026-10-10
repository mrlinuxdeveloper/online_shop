@extends('admin.layouts.AdminMasterLayout')

@section('content')

    <div class="container-fluid py-4">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white">

                <h5 class="mb-0 fw-bold">
                    ایجاد کد تخفیف
                </h5>

            </div>

            <div class="card-body">

                <form
                    action="{{ route('admin.coupons.store') }}"
                    method="POST"
                >

                    @csrf

                    @include('admin.coupons._form')

                    <button
                        type="submit"
                        class="btn btn-dark"
                    >
                        ایجاد کد تخفیف
                    </button>

                    <a
                        href="{{ route('admin.coupons.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        انصراف
                    </a>

                </form>

            </div>

        </div>

    </div>

@endsection

