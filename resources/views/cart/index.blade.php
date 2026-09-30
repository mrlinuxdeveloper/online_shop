@extends('layouts.app')

@section('content')

    <div class="container py-5">

        <h2 class="mb-4">
            Shopping Cart
        </h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if($cart->items->count())

            <div class="card shadow-sm">

                <div class="card-body">

                    @foreach($cart->items as $item)

                        <div class="row align-items-center border-bottom py-3">

                            {{-- Product --}}
                            <div class="col-md-4">

                                <strong>
                                    {{ $item->product->name }}
                                </strong>

                                <div class="small text-muted mt-1">

                                    @if($item->variant?->size)
                                        Size:
                                        {{ $item->variant->size->name }}
                                    @endif

                                    @if($item->variant?->color)
                                        <br>

                                        Color:
                                        {{ $item->variant->color->name }}

                                        <span
                                            style="
                                            display:inline-block;
                                            width:16px;
                                            height:16px;
                                            border-radius:50%;
                                            background:{{ $item->variant->color->hex }};
                                            border:1px solid #ccc;
                                            vertical-align:middle;
                                        "
                                        ></span>

                                    @endif

                                </div>

                                <div class="small mt-1">
                                    SKU:
                                    {{ $item->variant?->sku ?? '-' }}
                                </div>

                            </div>

                            {{-- Price --}}
                            <div class="col-md-2">

                                {{ number_format($item->price) }}

                            </div>

                            {{-- Quantity --}}
                            <div class="col-md-3">

                                <form
                                    action="{{ route('cart.update', $item) }}"
                                    method="POST"
                                    class="d-flex gap-2"
                                >

                                    @csrf
                                    @method('PATCH')

                                    <input
                                        type="number"
                                        name="quantity"
                                        value="{{ $item->quantity }}"
                                        min="1"
                                        max="{{ $item->variant?->stock }}"
                                        class="form-control"
                                        style="width:100px;"
                                    >

                                    <button
                                        type="submit"
                                        class="btn btn-outline-primary"
                                    >
                                        Update
                                    </button>

                                </form>

                            </div>

                            {{-- Delete --}}
                            <div class="col-md-2 text-end">

                                <form
                                    action="{{ route('cart.destroy', $item) }}"
                                    method="POST"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-outline-danger"
                                    >
                                        Remove
                                    </button>

                                </form>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

            {{-- Total --}}

            <div class="card shadow-sm mt-4">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="mb-0">
                            Total
                        </h5>

                        <h4 class="mb-0">
                            {{ number_format($total) }}
                        </h4>

                    </div>

                </div>

            </div>

            <div class="mt-4">
                <a
                    href="{{ route('checkout.index') }}"
                    class="btn btn-primary"
                >
                    Proceed to Checkout
                </a>
            </div>

        @else

            <div class="alert alert-info">
                Your shopping cart is empty.
            </div>

        @endif

    </div>

@endsection
