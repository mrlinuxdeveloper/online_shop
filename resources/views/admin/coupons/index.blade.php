@extends('admin.layouts.AdminMasterLayout')

@section('content')

    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h4 class="fw-bold mb-1">
                    مدیریت کدهای تخفیف
                </h4>

                <p class="text-muted mb-0">
                    مدیریت Coupon های فروشگاه
                </p>
            </div>

            <a
                href="{{ route('admin.coupons.create') }}"
                class="btn btn-dark"
            >
                ایجاد کد تخفیف
            </a>

        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card border-0 shadow-sm">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>
                            <th>کد</th>
                            <th>نوع</th>
                            <th>مقدار</th>
                            <th>حداقل سفارش</th>
                            <th>استفاده</th>
                            <th>اعتبار</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>

                        </thead>

                        <tbody>

                        @forelse($coupons as $coupon)

                            <tr>

                                <td>
                                    <strong>
                                        {{ $coupon->code }}
                                    </strong>
                                </td>

                                <td>

                                    @if($coupon->type === 'percent')
                                        درصدی
                                    @else
                                        مبلغ ثابت
                                    @endif

                                </td>

                                <td>

                                    @if($coupon->type === 'percent')

                                        {{ number_format($coupon->value) }}%

                                    @else

                                        {{ number_format($coupon->value) }}
                                        تومان

                                    @endif

                                </td>

                                <td>

                                    @if($coupon->min_order_amount)
                                        {{ number_format($coupon->min_order_amount) }}
                                        تومان
                                    @else
                                        بدون محدودیت
                                    @endif

                                </td>

                                <td>

                                    {{ $coupon->used_count }}

                                    @if($coupon->usage_limit)
                                        / {{ $coupon->usage_limit }}
                                    @else
                                        / ∞
                                    @endif

                                </td>

                                <td>

                                    @if($coupon->starts_at)
                                        {{ $coupon->starts_at->format('Y/m/d') }}
                                    @else
                                        -
                                    @endif

                                    تا

                                    @if($coupon->expires_at)
                                        {{ $coupon->expires_at->format('Y/m/d') }}
                                    @else
                                        -
                                    @endif

                                </td>

                                <td>

                                    @if($coupon->status)

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

                                    <div class="d-flex gap-1">

                                        <a
                                            href="{{ route('admin.coupons.edit', $coupon) }}"
                                            class="btn btn-sm btn-outline-dark"
                                        >
                                            ویرایش
                                        </a>

                                        <form
                                            action="{{ route('admin.coupons.toggle-status', $coupon) }}"
                                            method="POST"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                تغییر وضعیت
                                            </button>

                                        </form>

                                        <form
                                            action="{{ route('admin.coupons.destroy', $coupon) }}"
                                            method="POST"
                                            onsubmit="return confirm('آیا از حذف این کد تخفیف مطمئن هستید؟')"
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

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="8"
                                    class="text-center py-5 text-muted"
                                >
                                    هنوز کد تخفیفی ایجاد نشده است.
                                </td>
                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <div class="mt-4">
            {{ $coupons->links() }}
        </div>

    </div>

@endsection
