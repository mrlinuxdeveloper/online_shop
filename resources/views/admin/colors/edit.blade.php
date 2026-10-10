@extends('admin.layouts.AdminMasterLayout')

@section('title', 'ویرایش رنگ')
@section('page-title', 'ویرایش رنگ')

@section('content')

    <div class="card shadow-sm">

        <div class="card-body">

            <form action="{{ route('admin.colors.update', $color) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">

                    <label class="form-label">
                        نام رنگ
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $color->name) }}"
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
                        value="{{ old('code', $color->code) }}"
                        class="form-control @error('code') is-invalid @enderror"
                    >

                    @error('code')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        کد رنگ HEX
                    </label>

                    <div class="d-flex gap-2">

                        <input
                            type="color"
                            id="colorPicker"
                            value="{{ old('hex', $color->hex) }}"
                            class="form-control form-control-color"
                        >

                        <input
                            type="text"
                            name="hex"
                            id="hexInput"
                            value="{{ old('hex', $color->hex) }}"
                            class="form-control @error('hex') is-invalid @enderror"
                        >

                    </div>

                    @error('hex')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                    @enderror

                </div>

                <button type="submit"
                        class="btn btn-primary">
                    به‌روزرسانی
                </button>

                <a href="{{ route('admin.colors.index') }}"
                   class="btn btn-secondary">
                    بازگشت
                </a>

            </form>

        </div>

    </div>

@endsection

@push('scripts')

    <script>
        const colorPicker = document.getElementById('colorPicker');
        const hexInput = document.getElementById('hexInput');

        colorPicker.addEventListener('input', function () {
            hexInput.value = this.value;
        });

        hexInput.addEventListener('input', function () {

            if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                colorPicker.value = this.value;
            }

        });
    </script>

@endpush
