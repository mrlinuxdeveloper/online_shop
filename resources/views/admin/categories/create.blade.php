@extends('admin.layouts.AdminMasterLayout')

@section('title', 'ایجاد دسته‌بندی')

@section('page-title', 'ایجاد دسته‌بندی')

@section('content')

    <div class="container-fluid">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <form
                    action="{{ route('admin.categories.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                >

                    @csrf


                    {{-- نام --}}

                    <div class="mb-3">

                        <label class="form-label">
                            نام دسته‌بندی
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="نام دسته‌بندی"
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
                            value="{{ old('slug') }}"
                            class="form-control @error('slug') is-invalid @enderror"
                            placeholder="نامک-دسته‌بندی"
                        >

                        @error('slug')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>


                    {{-- والد --}}

                    <div class="mb-3">

                        <label class="form-label">
                            دسته‌بندی والد
                        </label>

                        <select
                            name="parent_id"
                            class="form-select @error('parent_id') is-invalid @enderror"
                        >

                            <option value="">
                                — بدون والد —
                            </option>

                            @foreach($categories as $parent)

                                <option
                                    value="{{ $parent->id }}"
                                    @selected(old('parent_id') == $parent->id)
                                >
                                    {{ $parent->name }}
                                </option>

                            @endforeach

                        </select>

                        @error('parent_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>


                    {{-- تصویر --}}

                    <div class="mb-3">

                        <label class="form-label">
                            تصویر دسته‌بندی
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
                            placeholder="توضیحات دسته‌بندی"
                        >{{ old('description') }}</textarea>

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

                            <option value="1"
                                @selected(old('status', 1) == 1)>
                                فعال
                            </option>

                            <option value="0"
                                @selected(old('status') === '0')>
                                غیرفعال
                            </option>

                        </select>

                    </div>


                    <div class="d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            ایجاد دسته‌بندی
                        </button>

                        <a
                            href="{{ route('admin.categories.index') }}"
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
