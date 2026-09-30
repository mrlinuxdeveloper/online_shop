@extends('admin.layouts.AdminMasterLayout')

@section('title', 'Product Variants')
@section('page-title', 'Product Variants')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Product Variants
            </h4>

            <div class="text-muted">
                {{ $product->name }}
            </div>
        </div>

        <div>

            <a
                href="{{ route('admin.products.variants.create', $product) }}"
                class="btn btn-primary"
            >
                Add Variant
            </a>

            <a
                href="{{ route('admin.products.index') }}"
                class="btn btn-secondary"
            >
                Back
            </a>

        </div>

    </div>

    <div class="card shadow-sm">

        <div class="card-body">

            @if($product->variants->count())

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Size</th>
                            <th>Color</th>
                            <th>SKU</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Weight</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>

                        <tbody>

                        @foreach($product->variants as $variant)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    {{ $variant->size?->name ?? '-' }}
                                </td>

                                <td>

                                    @if($variant->color)

                                        <div class="d-flex align-items-center gap-2">

                                        <span
                                            style="
                                                width:24px;
                                                height:24px;
                                                display:inline-block;
                                                border-radius:50%;
                                                background:{{ $variant->color->hex }};
                                                border:1px solid #ccc;
                                            "
                                        ></span>

                                            <span>
                                            {{ $variant->color->name }}
                                        </span>

                                        </div>

                                    @else

                                        -

                                    @endif

                                </td>

                                <td>
                                    <code>
                                        {{ $variant->sku }}
                                    </code>
                                </td>

                                <td>
                                    {{ number_format($variant->price ?? $product->price) }}
                                </td>

                                <td>

                                    @if($variant->stock > 0)

                                        <span class="badge bg-success">
                                        {{ $variant->stock }}
                                    </span>

                                    @else

                                        <span class="badge bg-danger">
                                        Out of stock
                                    </span>

                                    @endif

                                </td>

                                <td>
                                    {{ $variant->weight ?? '-' }}
                                </td>

                                <td>

                                    @if($variant->status)

                                        <span class="badge bg-success">
                                        Active
                                    </span>

                                    @else

                                        <span class="badge bg-secondary">
                                        Inactive
                                    </span>

                                    @endif

                                </td>

                                <td>

                                    <a
                                        href="{{ route('admin.products.variants.edit', [$product, $variant]) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.products.variants.destroy', [$product, $variant]) }}"
                                        method="POST"
                                        class="d-inline"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Delete this variant?')"
                                        >
                                            Delete
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="alert alert-info mb-0">

                    No variants have been created for this product yet.

                </div>

            @endif

        </div>

    </div>

@endsection
