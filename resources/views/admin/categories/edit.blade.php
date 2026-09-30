@extends('admin.layouts.AdminMasterLayout')

@section('title', 'Edit Category')

@section('page-title', 'Edit Category')

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


                    {{-- Name --}}

                    <div class="mb-3">

                        <label class="form-label">
                            Category Name
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


                    {{-- Slug --}}

                    <div class="mb-3">

                        <label class="form-label">
                            Slug
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


                    {{-- Parent --}}

                    <div class="mb-3">

                        <label class="form-label">
                            Parent Category
                        </label>

                        <select
                            name="parent_id"
                            class="form-select @error('parent_id') is-invalid @enderror"
                        >

                            <option value="">
                                -- No Parent --
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


                    {{-- Current Image --}}

                    @if($category->image)

                        <div class="mb-3">

                            <label class="form-label d-block">
                                Current Image
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


                    {{-- New Image --}}

                    <div class="mb-3">

                        <label class="form-label">
                            Replace Image
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


                    {{-- Description --}}

                    <div class="mb-3">

                        <label class="form-label">
                            Description
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


                    {{-- Status --}}

                    <div class="mb-4">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option
                                value="1"
                                @selected(old('status', $category->status) == 1)
                            >
                                Active
                            </option>

                            <option
                                value="0"
                                @selected(old('status', $category->status) == 0)
                            >
                                Inactive
                            </option>

                        </select>

                    </div>


                    <div class="d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Update Category
                        </button>

                        <a
                            href="{{ route('admin.categories.index') }}"
                            class="btn btn-secondary"
                        >
                            Cancel
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection
