@extends('layouts.app')

@section('content')

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h2 class="mb-1">My Addresses</h2>

                <p class="text-muted mb-0">
                    Manage your delivery addresses
                </p>
            </div>

            <a
                href="{{ route('addresses.create') }}"
                class="btn btn-dark"
            >
                Add New Address
            </a>

        </div>


        @if(session('success'))

            <div class="alert alert-success">
                {{ session('success') }}
            </div>

        @endif


        @if($addresses->isEmpty())

            <div class="card border-0 shadow-sm">

                <div class="card-body text-center py-5">

                    <h5>
                        You don't have any saved addresses.
                    </h5>

                    <p class="text-muted">
                        Add an address to continue with checkout.
                    </p>

                    <a
                        href="{{ route('addresses.create') }}"
                        class="btn btn-dark"
                    >
                        Add Address
                    </a>

                </div>

            </div>

        @else

            <div class="row g-4">

                @foreach($addresses as $address)

                    <div class="col-md-6">

                        <div class="card h-100 shadow-sm">

                            <div class="card-body">

                                <div class="d-flex justify-content-between align-items-start">

                                    <div>

                                        <h5 class="mb-1">
                                            {{ $address->title }}
                                        </h5>

                                        @if($address->is_default)

                                            <span class="badge bg-success">
                                        Default
                                    </span>

                                        @endif

                                    </div>

                                </div>


                                <hr>


                                <div class="mb-2">
                                    <strong>Receiver:</strong>
                                    {{ $address->receiver_name }}
                                </div>

                                <div class="mb-2">
                                    <strong>Phone:</strong>
                                    {{ $address->receiver_phone }}
                                </div>

                                <div class="mb-2">
                                    <strong>Province:</strong>
                                    {{ $address->province }}
                                </div>

                                <div class="mb-2">
                                    <strong>City:</strong>
                                    {{ $address->city }}
                                </div>

                                <div class="mb-2">
                                    <strong>Postal Code:</strong>
                                    {{ $address->postal_code }}
                                </div>

                                <div class="mb-3">
                                    <strong>Address:</strong>
                                    {{ $address->address }}
                                </div>


                                <div class="d-flex gap-2">

                                    <a
                                        href="{{ route('addresses.edit', $address) }}"
                                        class="btn btn-outline-dark btn-sm"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('addresses.destroy', $address) }}"
                                        method="POST"
                                        onsubmit="return confirm('Delete this address?')"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-outline-danger btn-sm"
                                        >
                                            Delete
                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @endif

    </div>

@endsection
