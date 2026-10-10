@extends('admin.layouts.AdminMasterLayout')

@section('title', 'محصولات')

@section('page-title', 'محصولات')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2>محصولات</h2>

                <p class="text-muted mb-0">
                    مدیریت محصولات فروشگاه
                </p>

            </div>

            <a
                href="{{ route('admin.products.create') }}"
                class="btn btn-primary"
            >
                + افزودن محصول
            </a>

        </div>


        <div class="card border-0 shadow-sm">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>محصول</th>

                            <th>کد</th>

                            <th>دسته‌بندی</th>

                            <th>برند</th>

                            <th>قیمت</th>

                            <th>تخفیف</th>

                            <th>وضعیت</th>

                            <th width="180">
                                عملیات
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
                                        href="{{ route('admin.products.images', $product) }}"
                                        class="btn btn-sm btn-outline-secondary"
                                    >
                                        تصاویر
                                    </a>
                                    <a
                                        href="{{ route('admin.products.variants.index', $product) }}"
                                        class="btn btn-sm btn-outline-info"
                                    >
                                        تنوع‌ها
                                    </a>

                                    <a
                                        href="{{ route('admin.products.edit', $product) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        ویرایش
                                    </a>



                                    <form
                                        action="{{ route('admin.products.destroy', $product) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('حذف this product?')"
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
                                    colspan="9"
                                    class="text-center py-5"
                                >

                                    محصولی پیدا نشد.

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
