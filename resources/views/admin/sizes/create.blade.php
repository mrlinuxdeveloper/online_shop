@extends('admin.layouts.AdminMasterLayout')

@section('title', 'Add Size')
@section('page-title', 'Add Size')

@section('content')

    <div class="card shadow-sm">

        <div class="card-body">

            <form action="{{ route('admin.sizes.store') }}"
                  method="POST">

                @csrf

                <div class="mb-3">

                    <label class="form-label">
                        Size Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="form-control @error('name') is-invalid @enderror"
                        placeholder="Example: Medium"
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
                        placeholder="Example: M"
                    >

                    @error('code')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror

                </div>

                <button type="submit"
                        class="btn btn-primary">
                    Save
                </button>

                <a href="{{ route('admin.sizes.index') }}"
                   class="btn btn-secondary">
                    Back
                </a>

            </form>

        </div>

    </div>

@endsection
