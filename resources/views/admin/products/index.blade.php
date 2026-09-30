@extends('admin.layouts.AdminMasterLayout')

@section('title', 'Products')

@section('page-title', 'Products')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2>Products</h2>

                <p class="text-muted mb-0">
                    Manage store products
                </p>

            </div>

            <a
                href="{{ route('admin.products.create') }}"
                class="btn btn-primary"
            >
                + Add Product
            </a>

        </div>


        <div class="card border-0 shadow-sm">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Product</th>

                            <th>Code</th>

                            <th>Category</th>

                            <th>Brand</th>

                            <th>Price</th>

                            <th>Discount</th>

                            <th>Status</th>

                            <th width="180">
                                Actions
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @forelse($products as $product)

                            <tr>

                                <td>
                                    {{ $products->firstItem() + $loop->index }}
                                </td>


                                <td>

                                    <strong>
                                        {{ $product->name }}
                                    </strong>

                                </td>


                                <td>

                                    <code>
                                        {{ $product->product_code }}
                                    </code>

                                </td>


                                <td>

                                    {{ $product->category?->name ?? '-' }}

                                </td>


                                <td>

                                    {{ $product->brand?->name ?? '-' }}

                                </td>


                                <td>

                                    {{ number_format($product->price) }}

                                </td>


                                <td>

                                    @if($product->discount > 0)

                                        <span class="badge text-bg-warning">

                                        {{ $product->discount }}%

                                    </span>

                                    @else

                                        -

                                    @endif

                                </td>


                                <td>

                                    @if($product->status)

                                        <span class="badge text-bg-success">
                                        Active
                                    </span>

                                    @else

                                        <span class="badge text-bg-secondary">
                                        Inactive
                                    </span>

                                    @endif

                                </td>


                                <td>
                                    <a
                                        href="{{ route('admin.products.images', $product) }}"
                                        class="btn btn-sm btn-outline-secondary"
                                    >
                                        Images
                                    </a>
                                    <a
                                        href="{{ route('admin.products.variants.index', $product) }}"
                                        class="btn btn-sm btn-outline-info"
                                    >
                                        Variants
                                    </a>

                                    <a
                                        href="{{ route('admin.products.edit', $product) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Edit
                                    </a>



                                    <form
                                        action="{{ route('admin.products.destroy', $product) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Delete this product?')"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                        >
                                            Delete
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center py-5"
                                >

                                    No products found.

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <div class="mt-4">

            {{ $products->links() }}

        </div>

    </div>

@endsection
