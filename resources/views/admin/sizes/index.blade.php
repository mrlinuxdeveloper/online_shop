@extends('admin.layouts.AdminMasterLayout')

@section('title', 'سایزها')
@section('page-title', 'سایزها')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h4 class="mb-0">سایزها</h4>

        <a href="{{ route('admin.sizes.create') }}"
           class="btn btn-primary">
            افزودن سایز
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
                            <th>نام</th>
                            <th>کد</th>
                            <th>عملیات</th>
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
                                        ویرایش
                                    </a>

                                    <form action="{{ route('admin.sizes.destroy', $size) }}"
                                          method="POST"
                                          class="d-inline">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('این سایز حذف شود؟')">
                                            حذف
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
                    سایزی پیدا نشد.
                </div>

            @endif

        </div>

    </div>

@endsection
