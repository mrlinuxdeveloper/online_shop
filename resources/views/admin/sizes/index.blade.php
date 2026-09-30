@extends('admin.layouts.AdminMasterLayout')

@section('title', 'Sizes')
@section('page-title', 'Sizes')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h4 class="mb-0">Sizes</h4>

        <a href="{{ route('admin.sizes.create') }}"
           class="btn btn-primary">
            Add Size
        </a>

    </div>

    <div class="card shadow-sm">

        <div class="card-body">

            @if($sizes->count())

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Actions</th>
                        </tr>
                        </thead>

                        <tbody>

                        @foreach($sizes as $size)

                            <tr>

                                <td>
                                    {{ $sizes->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    {{ $size->name }}
                                </td>

                                <td>
                                <span class="badge bg-secondary">
                                    {{ $size->code }}
                                </span>
                                </td>

                                <td>

                                    <a href="{{ route('admin.sizes.edit', $size) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.sizes.destroy', $size) }}"
                                          method="POST"
                                          class="d-inline">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Delete this size?')">
                                            Delete
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

                {{ $sizes->links() }}

            @else

                <div class="alert alert-info mb-0">
                    No sizes found.
                </div>

            @endif

        </div>

    </div>

@endsection
