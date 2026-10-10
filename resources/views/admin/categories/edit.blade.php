@extends('admin.layouts.AdminMasterLayout')

@section('title', 'ویرایش دسته‌بندی')

@section('page-title', 'ویرایش دسته‌بندی')

@section('content')

    <div class="container-fluid">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <form
                    action="{{ route('admin.categories.update', $category) }}"
                    method="POST"
                    enctype="multipart/form-data"
                >

                    @csrf

                    @method('PUT')


                    {{-- نام --}}

                    <div class="mb-3">

                        <label class="form-label">
                            نام دسته‌بندی
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $category->name) }}"
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
                            value="{{ old('slug', $category->slug) }}"
                            class="form-control @error('slug') is-invalid @enderror"
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
                                    @selected(
                                        old(
                                            'parent_id',
                                            $category->parent_id
                                        ) == $parent->id
                                    )
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


                    {{-- تصویر فعلی --}}

                    @if($category->image)

                        <div class="mb-3">

                            <label class="form-label d-block">
                                تصویر فعلی
                            </label>

                            <img
                                src="{{ asset('storage/' . $category->image) }}"
                                alt="{{ $category->name }}"
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
                        >{{ old('description', $category->description) }}</textarea>

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
                                @selected(old('status', $category->status) == 1)
                            >
                                فعال
                            </option>

                            <option
                                value="0"
                                @selected(old('status', $category->status) == 0)
                            >
                                غیرفعال
                            </option>

                        </select>

                    </div>


                    <div class="d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            به‌روزرسانی دسته‌بندی
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
