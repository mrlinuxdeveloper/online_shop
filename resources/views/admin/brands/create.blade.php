@extends('admin.layouts.AdminMasterLayout')

@section('title', 'Create Brand')

@section('page-title', 'Create Brand')

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


                    {{-- Name --}}

                    <div class="mb-3">

                        <label class="form-label">
                            Brand Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Brand name"
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
                            value="{{ old('slug') }}"
                            class="form-control @error('slug') is-invalid @enderror"
                            placeholder="brand-slug"
                        >

                        <small class="text-muted">
                            Leave empty to generate automatically.
                        </small>

                        @error('slug')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                        @enderror

                    </div>


                    {{-- Image --}}

                    <div class="mb-3">

                        <label class="form-label">
                            Brand Image
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
                            placeholder="Brand description"
                        >{{ old('description') }}</textarea>

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
                                @selected(old('status', 1) == 1)
                            >
                                Active
                            </option>

                            <option
                                value="0"
                                @selected(old('status') === '0')
                            >
                                Inactive
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}

                    <div class="d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Create Brand
                        </button>

                        <a
                            href="{{ route('admin.brands.index') }}"
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
