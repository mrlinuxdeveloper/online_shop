@extends('admin.layouts.AdminMasterLayout')

@section('title', 'Add Product Variant')
@section('page-title', 'Add Product Variant')

@section('content')

    <div class="card shadow-sm">

        <div class="card-header">
            <strong>
                Add Variant - {{ $product->name }}
            </strong>
        </div>

        <div class="card-body">

            <form
                action="{{ route('admin.products.variants.store', $product) }}"
                method="POST"
            >

                @csrf

                <div class="row">

                    {{-- Size --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Size
                        </label>

                        <select
                            name="size_id"
                            class="form-select @error('size_id') is-invalid @enderror"
                        >

                            <option value="">
                                Select Size
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

                    {{-- Color --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Color
                        </label>

                        <select
                            name="color_id"
                            class="form-select @error('color_id') is-invalid @enderror"
                        >

                            <option value="">
                                Select Color
                            </option>

                            @foreach($colors as $color)

                                <option
                                    value="{{ $color->id }}"
                                    @selected(old('color_id') == $color->id)
                                >
                                    {{ $color->name }}
                                    ({{ $color->code }})
                                </option>

                            @endforeach

                        </select>

                        @error('color_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>

                    {{-- SKU --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            SKU
                        </label>

                        <input
                            type="text"
                            name="sku"
                            value="{{ old('sku') }}"
                            class="form-control @error('sku') is-invalid @enderror"
                            placeholder="Example: TS-M-BLK"
                        >

                        @error('sku')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>

                    {{-- Price --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Variant Price
                        </label>

                        <input
                            type="number"
                            name="price"
                            value="{{ old('price') }}"
                            min="0"
                            step="0.01"
                            class="form-control @error('price') is-invalid @enderror"
                            placeholder="Leave empty to use product price"
                        >

                        @error('price')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror

                        <small class="text-muted">
                            If empty, product price will be used.
                        </small>

                    </div>

                    {{-- Stock --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Stock
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

                    {{-- Weight --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Weight
                        </label>

                        <input
                            type="number"
                            name="weight"
                            value="{{ old('weight') }}"
                            min="0"
                            step="0.01"
                            class="form-control @error('weight') is-invalid @enderror"
                            placeholder="Example: 0.5"
                        >

                        @error('weight')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>

                    {{-- Status --}}
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
                                Active
                            </label>

                        </div>

                    </div>

                </div>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Save Variant
                </button>

                <a
                    href="{{ route('admin.products.variants.index', $product) }}"
                    class="btn btn-secondary"
                >
                    Back
                </a>

            </form>

        </div>

    </div>

@endsection
