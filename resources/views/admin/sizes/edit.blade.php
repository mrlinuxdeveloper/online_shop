@extends('admin.layouts.AdminMasterLayout')

@section('title', 'ویرایش سایز')
@section('page-title', 'ویرایش سایز')

@section('content')

    <div class="card shadow-sm">

        <div class="card-body">

            <form action="{{ route('admin.sizes.update', $size) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">

                    <label class="form-label">
                        نام سایز
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $size->name) }}"
                        class="form-control @error('name') is-invalid @enderror"
                    >

                    @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        کد
                    </label>

                    <input
                        type="text"
                        name="code"
                        value="{{ old('code', $size->code) }}"
                        class="form-control @error('code') is-invalid @enderror"
                    >

                    @error('code')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror

                </div>

                <button type="submit"
                        class="btn btn-primary">
                    به‌روزرسانی
                </button>

                <a href="{{ route('admin.sizes.index') }}"
                   class="btn btn-secondary">
                    بازگشت
                </a>

            </form>

        </div>

    </div>

@endsection
