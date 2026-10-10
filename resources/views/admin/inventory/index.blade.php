@extends('admin.layouts.AdminMasterLayout')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3>
                    مدیریت موجودی
                </h3>

                <p class="text-muted mb-0">
                    مدیریت موجودی تنوع‌های محصولات
                </p>

            </div>

            <a
                href="{{ route('admin.inventory.transactions') }}"
                class="btn btn-outline-dark"
            >
                تاریخچه تراکنش‌ها
            </a>

        </div>


        @if(session('success'))

            <div class="alert alert-success">
                {{ session('success') }}
            </div>

        @endif


        @if($errors->any())

            <div class="alert alert-danger">

                @foreach($errors->all() as $error)

                    <div>
                        {{ $error }}
                    </div>

                @endforeach

            </div>

        @endif


        <div class="card">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead>

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                محصول
                            </th>

                            <th>
                                سایز
                            </th>

                            <th>
                                رنگ
                            </th>

                            <th>
                                شناسهٔ کالا
                            </th>

                            <th>
                                موجودی
                            </th>

                            <th>
                                عملیات
                            </th>

                        </tr>

                        </thead>

                        <tbody>

                        @forelse($variants as $variant)

                            <tr>

                                <td>
                                    {{ $variant->id }}
                                </td>


                                <td>
                                    {{ $variant->product?->name ?? '-' }}
                                </td>


                                <td>
                                    {{ $variant->size?->name ?? '-' }}
                                </td>


                                <td>
                                    {{ $variant->color?->name ?? '-' }}
                                </td>


                                <td>
                                    {{ $variant->sku ?? '-' }}
                                </td>


                                <td>

                                    @if($variant->stock <= 0)

                                        <span class="badge bg-danger">
                                            ناموجود
                                        </span>

                                    @elseif($variant->stock <= 5)

                                        <span class="badge bg-warning text-dark">
                                            {{ $variant->stock }}
                                        </span>

                                    @else

                                        <span class="badge bg-success">
                                            {{ $variant->stock }}
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    <div class="d-flex gap-2">

                                        {{-- افزایش موجودی --}}

                                        <button
                                            type="button"
                                            class="btn btn-success btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#increaseModal{{ $variant->id }}"
                                        >
                                            + موجودی
                                        </button>


                                        {{-- کاهش موجودی --}}

                                        <button
                                            type="button"
                                            class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#decreaseModal{{ $variant->id }}"
                                        >
                                            - موجودی
                                        </button>

                                    </div>


                                    {{-- Increase Modal --}}

                                    <div
                                        class="modal fade"
                                        id="increaseModal{{ $variant->id }}"
                                        tabindex="-1"
                                    >

                                        <div class="modal-dialog">

                                            <div class="modal-content">

                                                <form
                                                    action="{{ route('admin.inventory.increase', $variant) }}"
                                                    method="POST"
                                                >

                                                    @csrf
                                                    @method('PATCH')

                                                    <div class="modal-header">

                                                        <h5 class="modal-title">
                                                            افزایش موجودی
                                                        </h5>

                                                        <button
                                                            type="button"
                                                            class="btn-close"
                                                            data-bs-dismiss="modal"
                                                        ></button>

                                                    </div>


                                                    <div class="modal-body">

                                                        <p>
                                                            {{ $variant->product?->name }}
                                                        </p>

                                                        <p>
                                                            موجودی فعلی:
                                                            <strong>
                                                                {{ $variant->stock }}
                                                            </strong>
                                                        </p>


                                                        <div class="mb-3">

                                                            <label class="form-label">
                                                                نوع تراکنش
                                                            </label>

                                                            <select
                                                                name="type"
                                                                class="form-select"
                                                                required
                                                            >

                                                                <option value="purchase">
                                                                    خرید
                                                                </option>

                                                                <option value="return">
                                                                    برگشتی
                                                                </option>

                                                                <option value="adjustment">
                                                                    اصلاح موجودی
                                                                </option>

                                                            </select>

                                                        </div>


                                                        <div class="mb-3">

                                                            <label class="form-label">
                                                                تعداد
                                                            </label>

                                                            <input
                                                                type="number"
                                                                name="quantity"
                                                                class="form-control"
                                                                min="1"
                                                                required
                                                            >

                                                        </div>


                                                        <div class="mb-3">

                                                            <label class="form-label">
                                                                توضیحات
                                                            </label>

                                                            <textarea
                                                                name="description"
                                                                class="form-control"
                                                                rows="3"
                                                            ></textarea>

                                                        </div>

                                                    </div>


                                                    <div class="modal-footer">

                                                        <button
                                                            type="button"
                                                            class="btn btn-secondary"
                                                            data-bs-dismiss="modal"
                                                        >
                                                            انصراف
                                                        </button>

                                                        <button
                                                            type="submit"
                                                            class="btn btn-success"
                                                        >
                                                            ثبت افزایش
                                                        </button>

                                                    </div>

                                                </form>

                                            </div>

                                        </div>

                                    </div>


                                    {{-- Decrease Modal --}}

                                    <div
                                        class="modal fade"
                                        id="decreaseModal{{ $variant->id }}"
                                        tabindex="-1"
                                    >

                                        <div class="modal-dialog">

                                            <div class="modal-content">

                                                <form
                                                    action="{{ route('admin.inventory.decrease', $variant) }}"
                                                    method="POST"
                                                >

                                                    @csrf
                                                    @method('PATCH')

                                                    <div class="modal-header">

                                                        <h5 class="modal-title">
                                                            کاهش موجودی
                                                        </h5>

                                                        <button
                                                            type="button"
                                                            class="btn-close"
                                                            data-bs-dismiss="modal"
                                                        ></button>

                                                    </div>


                                                    <div class="modal-body">

                                                        <p>
                                                            {{ $variant->product?->name }}
                                                        </p>

                                                        <p>
                                                            موجودی فعلی:
                                                            <strong>
                                                                {{ $variant->stock }}
                                                            </strong>
                                                        </p>


                                                        <div class="mb-3">

                                                            <label class="form-label">
                                                                تعداد کاهش
                                                            </label>

                                                            <input
                                                                type="number"
                                                                name="quantity"
                                                                class="form-control"
                                                                min="1"
                                                                max="{{ $variant->stock }}"
                                                                required
                                                            >

                                                        </div>


                                                        <div class="mb-3">

                                                            <label class="form-label">
                                                                توضیحات
                                                            </label>

                                                            <textarea
                                                                name="description"
                                                                class="form-control"
                                                                rows="3"
                                                            ></textarea>

                                                        </div>

                                                    </div>


                                                    <div class="modal-footer">

                                                        <button
                                                            type="button"
                                                            class="btn btn-secondary"
                                                            data-bs-dismiss="modal"
                                                        >
                                                            انصراف
                                                        </button>

                                                        <button
                                                            type="submit"
                                                            class="btn btn-warning"
                                                        >
                                                            ثبت کاهش
                                                        </button>

                                                    </div>

                                                </form>

                                            </div>

                                        </div>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-5"
                                >
                                    تنوعی وجود ندارد.
                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>


                <div class="mt-3">

                    {{ $variants->links() }}

                </div>

            </div>

        </div>

    </div>

@endsection
