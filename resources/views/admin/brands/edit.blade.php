@extends('admin.layouts.AdminMasterLayout')

@section('title', 'ویرایش برند')

@section('page-title', 'ویرایش برند')

@section('content')

    <div class="container-fluid">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <form
                    action="{{ route('admin.brands.update', $brand) }}"
                    method="POST"
                    enctype="multipart/form-data"
                >

                    @csrf

                    @method('PUT')


                    {{-- نام --}}

                    <div class="mb-3">

                        <label class="form-label">
                            نام برند
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $brand->name) }}"
                            class="form-control @error('name') is-invalid @enderror"
                        >

                        @error('name')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                        @enderror

                    </div>


                    {{-- نامک --}}

                    <div class="mb-3">

                        <label class="form-label">
                            نامک
                        </label>

                        <input
                            type="text"
                            name="slug"
                            value="{{ old('slug', $brand->slug) }}"
                            class="form-control @error('slug') is-invalid @enderror"
                        >

                        @error('slug')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                        @enderror

                    </div>


                    {{-- تصویر فعلی --}}

                    @if($brand->image)

                        <div class="mb-3">

                            <label class="form-label d-block">
                                تصویر فعلی
                            </label>

                            <img
                                src="{{ asset('storage/' . $brand->image) }}"
                                alt="{{ $brand->name }}"
                                width="120"
                                height="120"
                                class="rounded"
                                style="object-fit: cover;"
                            >

                        </div>

                    @endif


                    {{-- New تصویر --}}

                    <div class="mb-3">

                        <label class="form-label">
                            جایگزینی تصویر
                        </label>

                        <input
                            type="file"
                            name="image"
                            class="form-control @error('image') is-invalid @enderror"
                            accept="image/*"
                        >

                        @error('image')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                        @enderror

                    </div>


                    {{-- توضیحات --}}

                    <div class="mb-3">

                        <label class="form-label">
                            توضیحات
                        </label>

                        <textarea
                            name="description"
                            rows="5"
                            class="form-control @error('description') is-invalid @enderror"
                        >{{ old('description', $brand->description) }}</textarea>

                        @error('description')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                        @enderror

                    </div>


                    {{-- وضعیت --}}

                    <div class="mb-4">

                        <label class="form-label">
                            وضعیت
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option
                                value="1"
                                @selected(old('status', $brand->status) == 1)
                            >
                                فعال
                            </option>

                            <option
                                value="0"
                                @selected(old('status', $brand->status) == 0)
                            >
                                غیرفعال
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}

                    <div class="d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            به‌روزرسانی برند
                        </button>

                        <a
                            href="{{ route('admin.brands.index') }}"
                            class="btn btn-secondary"
                        >
                            لغو
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection
