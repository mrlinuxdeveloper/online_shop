@extends('layouts.app')

@section('content')

    <div class="container py-5">


        <div class="mb-4">

            <h2>
                Add New Address
            </h2>

            <p class="text-muted">
                Enter your delivery information.
            </p>

        </div>


        <form
            action="{{ route('addresses.store') }}"
            method="POST"
        >

            @csrf

            <div class="row g-3">

                <div class="col-md-6">

                    <label class="form-label">
                        Address Title
                    </label>

                    <input
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        class="form-control"
                        placeholder="Home"
                        required
                    >

                    @error('title')
                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>
                    @enderror

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Receiver Name
                    </label>

                    <input
                        type="text"
                        name="receiver_name"
                        value="{{ old('receiver_name', auth()->user()->name) }}"
                        class="form-control"
                        required
                    >

                    @error('receiver_name')
                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>
                    @enderror

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Receiver Phone
                    </label>

                    <input
                        type="text"
                        name="receiver_phone"
                        value="{{ old('receiver_phone', auth()->user()->tel) }}"
                        class="form-control"
                        required
                    >

                    @error('receiver_phone')
                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>
                    @enderror

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Province
                    </label>

                    <input
                        type="text"
                        name="province"
                        value="{{ old('province') }}"
                        class="form-control"
                        required
                    >

                    @error('province')
                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>
                    @enderror

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        City
                    </label>

                    <input
                        type="text"
                        name="city"
                        value="{{ old('city') }}"
                        class="form-control"
                        required
                    >

                    @error('city')
                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>
                    @enderror

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Postal Code
                    </label>

                    <input
                        type="text"
                        name="postal_code"
                        value="{{ old('postal_code') }}"
                        class="form-control"
                        required
                    >

                    @error('postal_code')
                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>
                    @enderror

                </div>


                <div class="col-12">

                    <label class="form-label">
                        Full Address
                    </label>

                    <textarea
                        name="address"
                        rows="4"
                        class="form-control"
                        required
                    >{{ old('address') }}</textarea>

                    @error('address')
                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>
                    @enderror

                </div>


                <div class="col-12">

                    <div class="form-check">

                        <input
                            type="checkbox"
                            name="is_default"
                            value="1"
                            id="is_default"
                            class="form-check-input"
                        >

                        <label
                            for="is_default"
                            class="form-check-label"
                        >
                            Set as default address
                        </label>

                    </div>

                </div>


                <div class="col-12 mt-4">

                    <button
                        type="submit"
                        class="btn btn-dark"
                    >
                        Save Address
                    </button>

                    <a
                        href="{{ route('addresses.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        Cancel
                    </a>

                </div>

            </div>

        </form>

    </div>

@endsection
