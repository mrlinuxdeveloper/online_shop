@extends('admin.layouts.AdminMasterLayout')

@section('title', 'تنوع‌های محصول')
@section('page-title', 'تنوع‌های محصول')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                تنوع‌های محصول
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
                افزودن تنوع
            </a>

            <a
                href="{{ route('admin.products.index') }}"
                class="btn btn-secondary"
            >
                بازگشت
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
                            <th>سایز</th>
                            <th>رنگ</th>
                            <th>شناسهٔ کالا</th>
                            <th>قیمت</th>
                            <th>موجودی</th>
                            <th>وزن</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
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
                                        ناموجود
                                    </span>

                                    @endif

                                </td>

                                <td>
                                    {{ $variant->weight ?? '-' }}
                                </td>

                                <td>

                                    @if($variant->status)

                                        <span class="badge bg-success">
                                        فعال
                                    </span>

                                    @else

                                        <span class="badge bg-secondary">
                                        غیرفعال
                                    </span>

                                    @endif

                                </td>

                                <td>

                                    <a
                                        href="{{ route('admin.products.variants.edit', [$product, $variant]) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        ویرایش
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
                                            onclick="return confirm('حذف this variant?')"
                                        >
                                            حذف
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

                    هنوز تنوعی برای این محصول ثبت نشده است.

                </div>

            @endif

        </div>

    </div>

@endsection
