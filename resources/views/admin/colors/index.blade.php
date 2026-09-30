@extends('admin.layouts.AdminMasterLayout')

@section('title', 'Colors')
@section('page-title', 'Colors')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h4 class="mb-0">Colors</h4>

        <a href="{{ route('admin.colors.create') }}"
           class="btn btn-primary">
            Add Color
        </a>

    </div>

    <div class="card shadow-sm">

        <div class="card-body">

            @if($colors->count())

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Color</th>
                            <th>Code</th>
                            <th>Hex</th>
                            <th>Preview</th>
                            <th>Actions</th>
                        </tr>
                        </thead>

                        <tbody>

                        @foreach($colors as $color)

                            <tr>

                                <td>
                                    {{ $colors->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    {{ $color->name }}
                                </td>

                                <td>
                                <span class="badge bg-secondary">
                                    {{ $color->code }}
                                </span>
                                </td>

                                <td>
                                    {{ $color->hex }}
                                </td>

                                <td>

                                <span
                                    style="
                                        display:inline-block;
                                        width:32px;
                                        height:32px;
                                        border-radius:50%;
                                        background:{{ $color->hex }};
                                        border:1px solid #ccc;
                                    ">
                                </span>

                                </td>

                                <td>

                                    <a href="{{ route('admin.colors.edit', $color) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.colors.destroy', $color) }}"
                                        method="POST"
                                        class="d-inline">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Delete this color?')">
                                            Delete
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

                {{ $colors->links() }}

            @else

                <div class="alert alert-info mb-0">
                    No colors found.
                </div>

            @endif

        </div>

    </div>

@endsection
