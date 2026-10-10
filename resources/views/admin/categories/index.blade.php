@extends('admin.layouts.AdminMasterLayout')

@section('title', 'دسته‌بندی‌ها')

@section('page-title', 'دسته‌بندی‌ها')

@section('content')

    <div class="container-fluid">

        @if(session('error'))
            <div class="alert alert-warning" role="alert">{{ session('error') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h2>دسته‌بندی‌ها</h2>

                <p class="text-muted mb-0">
                    مدیریت دسته‌بندی‌های محصولات
                </p>
            </div>

            <a href="{{ route('admin.categories.create') }}"
               class="btn btn-primary">

                + افزودن دسته‌بندی

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

                            <th>والد</th>

                            <th>وابستگی‌ها</th>

                            <th>وضعیت</th>

                            <th width="180">عملیات</th>

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
                                            بدون تصویر
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
                                    <span class="badge text-bg-light">{{ $category->products_count }} محصول</span>
                                    @if($category->children_count)
                                        <span class="badge text-bg-light">{{ $category->children_count }} زیرشاخه</span>
                                    @endif
                                </td>

                                <td>

                                    @if($category->status)

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
                                        href="{{ route('admin.categories.edit', $category) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        ویرایش
                                    </a>


                                    <form
                                        action="{{ route('admin.categories.destroy', $category) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('این دسته‌بندی حذف شود؟')"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            @disabled($category->products_count > 0 || $category->children_count > 0)
                                            title="{{ $category->products_count || $category->children_count ? 'ابتدا محصولات یا زیرشاخه‌ها را منتقل کنید یا دسته‌بندی را غیرفعال کنید.' : 'حذف دسته‌بندی' }}"
                                        >
                                            حذف
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-5"
                                >

                                    دسته‌بندی‌ای پیدا نشد.

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
