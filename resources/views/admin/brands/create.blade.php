@extends('admin.layouts.AdminMasterLayout')

@section('title', 'ایجاد برند')

@section('page-title', 'ایجاد برند')

@section('content')

    <div class="container-fluid">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <form
                    action="{{ route('admin.brands.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                >

                    @csrf


                    {{-- نام --}}

                    <div class="mb-3">

                        <label class="form-label">
                            نام برند
                        </label>

                        @error('name')
                            <div class="text-danger small mb-1">{{ $message }}</div>
                        @enderror
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="نام برند"
                        >

                    </div>


                    {{-- slug --}}

                    <div class="mb-3">

                        <label class="form-label">
                            slug
                        </label>

                        @error('slug')
                            <div class="text-danger small mb-1">{{ $message }}</div>
                        @enderror
                        <input
                            type="text"
                            name="slug"
                            value="{{ old('slug') }}"
                            class="form-control @error('slug') is-invalid @enderror"
                            placeholder="slug-برند"
                        >

                        <small class="text-muted">
                            برای ساخت خودکار، این بخش را خالی بگذارید.
                        </small>

                    </div>


                    {{-- تصویر --}}

                    <div class="mb-3">

                        <label class="form-label">
                            تصویر برند
                        </label>

                        @error('image')
                            <div class="text-danger small mb-1">{{ $message }}</div>
                        @enderror
                        <input
                            type="file"
                            name="image"
                            class="form-control @error('image') is-invalid @enderror"
                            accept="image/*"
                        >

                    </div>


                    {{-- توضیحات --}}

                    <div class="mb-3">

                        <label class="form-label">
                            توضیحات
                        </label>

                        @error('description')
                            <div class="text-danger small mb-1">{{ $message }}</div>
                        @enderror
                        <textarea
                            name="description"
                            rows="5"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="توضیحات برند"
                        >{{ old('description') }}</textarea>

                    </div>


                    {{-- وضعیت --}}

                    <div class="mb-4">

                        <label class="form-label">
                            وضعیت
                        </label>

                        @error('status')
                            <div class="text-danger small mb-1">{{ $message }}</div>
                        @enderror
                        <select
                            name="status"
                            class="form-select @error('status') is-invalid @enderror"
                        >

                            <option
                                value="1"
                                @selected(old('status', 1) == 1)
                            >
                                فعال
                            </option>

                            <option
                                value="0"
                                @selected(old('status') === '0')
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
                            ایجاد برند
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
