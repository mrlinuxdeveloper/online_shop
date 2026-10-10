@extends('admin.layouts.AdminMasterLayout')

@section('title', 'تصاویر محصول')

@section('page-title', 'تصاویر محصول')

@section('content')

    <div class="container-fluid">

        {{-- Header --}}

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2>
                    {{ $product->name }}
                </h2>

                <p class="text-muted mb-0">
                    مدیریت تصاویر محصول
                </p>

            </div>

            <a
                href="{{ route('admin.products.index') }}"
                class="btn btn-secondary"
            >
                بازگشت به محصولات
            </a>

        </div>


        {{-- بارگذاری --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    بارگذاری تصویر جدید
                </h5>

            </div>


            <div class="card-body">

                <form
                    action="{{ route('admin.products.images.store', $product) }}"
                    method="POST"
                    enctype="multipart/form-data"
                >

                    @csrf


                    <div class="row align-items-end">

                        <div class="col-md-7">

                            <label class="form-label">
                                تصویر
                            </label>

                            <input
                                type="file"
                                name="image"
                                class="form-control @error('image') is-invalid @enderror"
                                accept="image/*"
                                required
                            >

                            @error('image')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>


                        <div class="col-md-3">

                            <div class="form-check mb-2">

                                <input
                                    type="checkbox"
                                    name="is_primary"
                                    value="1"
                                    class="form-check-input"
                                    id="is_primary"
                                >

                                <label
                                    class="form-check-label"
                                    for="is_primary"
                                >
                                    انتخاب به‌عنوان تصویر اصلی
                                </label>

                            </div>

                        </div>


                        <div class="col-md-2">

                            <button
                                type="submit"
                                class="btn btn-primary w-100"
                            >
                                بارگذاری
                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- تصاویر --}}

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    تصاویر محصول
                </h5>

            </div>


            <div class="card-body">

                @if($product->images->count())

                    <div class="row g-4">

                        @foreach($product->images as $image)

                            <div class="col-md-3">

                                <div class="card h-100">

                                    <img
                                        src="{{ asset('storage/' . $image->image) }}"
                                        class="card-img-top"
                                        style="
                                        height:220px;
                                        object-fit:cover;
                                    "
                                        alt="{{ $product->name }}"
                                    >


                                    <div class="card-body">

                                        @if($image->is_primary)

                                            <span class="badge text-bg-success mb-3">
                                            تصویر اصلی
                                        </span>

                                        @else

                                            <form
                                                action="{{ route(
                                                'admin.products.images.primary',
                                                [
                                                    'product' => $product,
                                                    'image' => $image
                                                ]
                                            ) }}"
                                                method="POST"
                                                class="mb-2"
                                            >

                                                @csrf

                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-primary w-100"
                                                >
                                                    انتخاب به‌عنوان اصلی
                                                </button>

                                            </form>

                                        @endif


                                        <form
                                            action="{{ route(
                                            'admin.products.images.destroy',
                                            [
                                                'product' => $product,
                                                'image' => $image
                                            ]
                                        ) }}"
                                            method="POST"
                                            onsubmit="return confirm('این تصویر حذف شود؟')"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger w-100"
                                            >
                                                حذف
                                            </button>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="text-center py-5">

                        <p class="text-muted mb-0">
                            تصویری برای این محصول ثبت نشده است.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>

@endsection
