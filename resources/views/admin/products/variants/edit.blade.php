@extends('admin.layouts.AdminMasterLayout')

    @section('title', 'ویرایش تنوع محصول')
@section('page-title', 'ویرایش محصول تنوع')

@section('content')

    <div class="card shadow-sm">

        <div class="card-header">
            <strong>
                ویرایش تنوع - {{ $product->name }}
            </strong>
        </div>

        <div class="card-body">

            <form
                action="{{ route('admin.products.variants.update', [$product, $variant]) }}"
                method="POST"
            >

                @csrf
                @method('PUT')

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
                                    @selected(old('size_id', $variant->size_id) == $size->id)
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
                            'selectedColorId' => old('color_id', $variant->color_id),
                            'colorSelectId' => 'edit-variant-color',
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
                            value="{{ old('sku', $variant->sku) }}"
                            class="form-control @error('sku') is-invalid @enderror"
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
                            value="{{ old('price', $variant->price) }}"
                            min="0"
                            step="0.01"
                            class="form-control @error('price') is-invalid @enderror"
                        >

                        @error('price')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>

                    {{-- موجودی --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            موجودی
                        </label>

                        <input
                            type="number"
                            name="stock"
                            value="{{ old('stock', $variant->stock) }}"
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
                            value="{{ old('weight', $variant->weight) }}"
                            min="0"
                            step="0.01"
                            class="form-control @error('weight') is-invalid @enderror"
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
                                @checked(old('status', $variant->status))
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
                    به‌روزرسانی تنوع
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
