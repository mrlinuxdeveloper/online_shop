@extends('admin.layouts.AdminMasterLayout')

@section('title', 'برندها')

@section('page-title', 'برندها')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2>برندها</h2>

                <p class="text-muted mb-0">
                    مدیریت برندهای محصولات
                </p>

            </div>

            <a
                href="{{ route('admin.brands.create') }}"
                class="btn btn-primary"
            >
                + افزودن برند
            </a>

        </div>


        <div class="card border-0 shadow-sm">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>تصویر</th>

                            <th>نام</th>

                            <th>نامک</th>

                            <th>محصولات</th>

                            <th>وضعیت</th>

                            <th width="180">
                                عملیات
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @forelse($brands as $brand)

                            <tr>

                                <td>
                                    {{ $brands->firstItem() + $loop->index }}
                                </td>


                                <td>

                                    @if($brand->logo)

                                        <img
                                            src="{{ asset('storage/' . $brand->logo) }}"
                                            alt="{{ $brand->name }}"
                                            width="60"
                                            height="60"
                                            class="rounded"
                                            style="object-fit: cover;"
                                        >

                                    @else

                                        <div
                                            class="bg-light rounded d-flex align-items-center justify-content-center"
                                            style="width:60px;height:60px;"
                                        >
                                            بدون تصویر
                                        </div>

                                    @endif

                                </td>


                                <td>

                                    <strong>
                                        {{ $brand->name }}
                                    </strong>

                                </td>


                                <td>

                                    <code>
                                        {{ $brand->slug }}
                                    </code>

                                </td>


                                <td>

                                    {{ $brand->products()->count() }}

                                </td>


                                <td>

                                    @if($brand->status)

                                        <span class="badge text-bg-success">
                                        فعال
                                    </span>

                                    @else

                                        <span class="badge text-bg-secondary">
                                        غیرفعال
                                    </span>

                                    @endif

                                </td>


                                <td>

                                    <a
                                        href="{{ route('admin.brands.edit', $brand) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        ویرایش
                                    </a>


                                    <form
                                        action="{{ route('admin.brands.destroy', $brand) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('این برند حذف شود؟')"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                        >
                                            حذف
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
                                    برندی پیدا نشد.
                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <div class="mt-4">

            {{ $brands->links() }}

        </div>

    </div>

@endsection
