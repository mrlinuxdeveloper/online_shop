@extends('admin.layouts.AdminMasterLayout')

@section('title', 'Add Color')
@section('page-title', 'Add Color')

@section('content')

    <div class="card shadow-sm">

        <div class="card-body">

            <form action="{{ route('admin.colors.store') }}"
                  method="POST">

                @csrf

                <div class="mb-3">

                    <label class="form-label">
                        Color Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="form-control @error('name') is-invalid @enderror"
                        placeholder="Example: Black"
                    >

                    @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Code
                    </label>

                    <input
                        type="text"
                        name="code"
                        value="{{ old('code') }}"
                        class="form-control @error('code') is-invalid @enderror"
                        placeholder="Example: BLK"
                    >

                    @error('code')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        HEX Color
                    </label>

                    <div class="d-flex gap-2">

                        <input
                            type="color"
                            id="colorPicker"
                            value="{{ old('hex', '#000000') }}"
                            class="form-control form-control-color"
                        >

                        <input
                            type="text"
                            name="hex"
                            id="hexInput"
                            value="{{ old('hex', '#000000') }}"
                            class="form-control @error('hex') is-invalid @enderror"
                            placeholder="#000000"
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
                    Save
                </button>

                <a href="{{ route('admin.colors.index') }}"
                   class="btn btn-secondary">
                    Back
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
