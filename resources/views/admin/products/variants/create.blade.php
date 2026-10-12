@extends('admin.layouts.AdminMasterLayout')

    @section('title', 'افزودن تنوع محصول')
@section('page-title', 'افزودن محصول تنوع')

@section('content')

    <div class="card shadow-sm">

        <div class="card-header">
            <strong>
                افزودن تنوع - {{ $product->name }}
            </strong>
        </div>

        <div class="card-body">

            <form
                action="{{ route('admin.products.variants.store', $product) }}"
                method="POST"
            >

                @csrf

                <div class="row">

                    {{-- سایز --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            سایز
                        </label>

                        <select
                            name="size_id"
                            class="form-select @error('size_id') is-invalid @enderror"
                        >

                            <option value="">
                                انتخاب سایز
                            </option>

                            @foreach($sizes as $size)

                                <option
                                    value="{{ $size->id }}"
                                    @selected(old('size_id') == $size->id)
                                >
                                    {{ $size->name }}
                                    ({{ $size->code }})
                                </option>

                            @endforeach

                        </select>

                        @error('size_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>

                    {{-- رنگ --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            رنگ
                        </label>

                        @include('admin.products.variants._color-select', [
                            'colors' => $colors,
                            'selectedColorId' => old('color_id'),
                            'colorSelectId' => 'create-variant-color',
                        ])

                    </div>

                    {{-- شناسهٔ کالا --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            شناسهٔ کالا
                        </label>

                        <input
                            type="text"
                            name="sku"
                            value="{{ old('sku') }}"
                            class="form-control @error('sku') is-invalid @enderror"
                            placeholder="مثلاً TS-M-BLK"
                        >

                        @error('sku')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>

                    {{-- قیمت --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            قیمت تنوع
                        </label>

                        <input
                            type="number"
                            name="price"
                            value="{{ old('price') }}"
                            min="0"
                            step="0.01"
                            class="form-control @error('price') is-invalid @enderror"
                            placeholder="برای استفاده از قیمت محصول خالی بگذارید"
                        >

                        @error('price')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror

                        <small class="text-muted">
                            اگر خالی بماند، قیمت محصول اعمال می‌شود.
                        </small>

                    </div>

                    {{-- موجودی --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            موجودی
                        </label>

                        <input
                            type="number"
                            name="stock"
                            value="{{ old('stock', 0) }}"
                            min="0"
                            class="form-control @error('stock') is-invalid @enderror"
                        >

                        @error('stock')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>

                    {{-- وزن --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            وزن
                        </label>

                        <input
                            type="number"
                            name="weight"
                            value="{{ old('weight') }}"
                            min="0"
                            step="0.01"
                            class="form-control @error('weight') is-invalid @enderror"
                            placeholder="مثلاً ۰٫۵"
                        >

                        @error('weight')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>

                    {{-- وضعیت --}}
                    <div class="col-md-6 mb-3">

                        <div class="form-check mt-4">

                            <input
                                type="checkbox"
                                name="status"
                                value="1"
                                class="form-check-input"
                                id="status"
                                @checked(old('status', true))
                            >

                            <label
                                for="status"
                                class="form-check-label"
                            >
                                فعال
                            </label>

                        </div>

                    </div>

                </div>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    ذخیرهٔ تنوع
                </button>

                <a
                    href="{{ route('admin.products.variants.index', $product) }}"
                    class="btn btn-secondary"
                >
                    بازگشت
                </a>

            </form>

        </div>

    </div>

@endsection
