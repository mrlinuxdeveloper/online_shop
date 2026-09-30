@extends('admin.layouts.AdminMasterLayout')

@section('title', 'Categories')

@section('page-title', 'Categories')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h2>Categories</h2>

                <p class="text-muted mb-0">
                    Manage product categories
                </p>
            </div>

            <a href="{{ route('admin.categories.create') }}"
               class="btn btn-primary">

                + Add Category

            </a>

        </div>


        <div class="card border-0 shadow-sm">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Image</th>

                            <th>Name</th>

                            <th>Slug</th>

                            <th>Parent</th>

                            <th>Status</th>

                            <th width="180">Actions</th>

                        </tr>

                        </thead>


                        <tbody>

                        @forelse($categories as $category)

                            <tr>

                                <td>
                                    {{ $categories->firstItem() + $loop->index }}
                                </td>


                                <td>

                                    @if($category->image)

                                        <img
                                            src="{{ asset('storage/' . $category->image) }}"
                                            alt="{{ $category->name }}"
                                            width="60"
                                            height="60"
                                            style="object-fit: cover;"
                                            class="rounded"
                                        >

                                    @else

                                        <div
                                            class="bg-light rounded d-flex align-items-center justify-content-center"
                                            style="width:60px;height:60px;"
                                        >
                                            No Image
                                        </div>

                                    @endif

                                </td>


                                <td>

                                    <strong>
                                        {{ $category->name }}
                                    </strong>

                                </td>


                                <td>

                                    <code>
                                        {{ $category->slug }}
                                    </code>

                                </td>


                                <td>

                                    {{ $category->parent?->name ?? '-' }}

                                </td>


                                <td>

                                    @if($category->status)

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
                                        href="{{ route('admin.categories.edit', $category) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Edit
                                    </a>


                                    <form
                                        action="{{ route('admin.categories.destroy', $category) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Delete this category?')"
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
                                    colspan="7"
                                    class="text-center py-5"
                                >

                                    No categories found.

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <div class="mt-4">

            {{ $categories->links() }}

        </div>

    </div>

@endsection
