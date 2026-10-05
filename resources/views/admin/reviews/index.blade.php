@extends('admin.layouts.AdminMasterLayout')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h3>
                    مدیریت نظرات
                </h3>

                <p class="text-muted mb-0">
                    بررسی و مدیریت نظرات کاربران
                </p>
            </div>

        </div>


        {{-- پیام موفقیت --}}

        @if(session('success'))

            <div class="alert alert-success">
                {{ session('success') }}
            </div>

        @endif


        {{-- خطاها --}}

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

                @if($reviews->count())

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle">

                            <thead>

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    کاربر
                                </th>

                                <th>
                                    محصول
                                </th>

                                <th>
                                    امتیاز
                                </th>

                                <th>
                                    نظر
                                </th>

                                <th>
                                    وضعیت
                                </th>

                                <th>
                                    تاریخ
                                </th>

                                <th>
                                    عملیات
                                </th>

                            </tr>

                            </thead>

                            <tbody>

                            @foreach($reviews as $review)

                                <tr>

                                    <td>
                                        {{ $review->id }}
                                    </td>


                                    <td>

                                        {{ $review->user->name ?? 'کاربر حذف شده' }}

                                        @if($review->user?->lastname)

                                            {{ $review->user->lastname }}

                                        @endif

                                    </td>


                                    <td>

                                        @if($review->product)

                                            {{ $review->product->name }}

                                        @else

                                            <span class="text-muted">
                                                محصول حذف شده
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        @for($i = 1; $i <= 5; $i++)

                                            @if($i <= $review->rating)

                                                ⭐

                                            @else

                                                ☆

                                            @endif

                                        @endfor

                                    </td>


                                    <td style="min-width: 250px;">

                                        @if($review->comment)

                                            {{ $review->comment }}

                                        @else

                                            <span class="text-muted">
                                                بدون متن
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        @if($review->status)

                                            <span class="badge bg-success">
                                                تأیید شده
                                            </span>

                                        @else

                                            <span class="badge bg-warning text-dark">
                                                در انتظار تأیید
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        {{ $review->created_at?->format('Y/m/d H:i') }}

                                    </td>


                                    <td>

                                        <div class="d-flex gap-2">


                                            @if(!$review->status)

                                                <form
                                                    action="{{ route('admin.reviews.approve', $review) }}"
                                                    method="POST"
                                                >

                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-success btn-sm"
                                                    >
                                                        تأیید
                                                    </button>

                                                </form>

                                            @else

                                                <form
                                                    action="{{ route('admin.reviews.reject', $review) }}"
                                                    method="POST"
                                                >

                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-warning btn-sm"
                                                    >
                                                        رد
                                                    </button>

                                                </form>

                                            @endif


                                            <form
                                                action="{{ route('admin.reviews.destroy', $review) }}"
                                                method="POST"
                                                onsubmit="return confirm('آیا از حذف این نظر مطمئن هستید؟')"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger btn-sm"
                                                >
                                                    حذف
                                                </button>

                                            </form>


                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                            </tbody>

                        </table>

                    </div>


                    <div class="mt-3">

                        {{ $reviews->links() }}

                    </div>


                @else

                    <div class="text-center py-5">

                        <h5>
                            نظری وجود ندارد
                        </h5>

                        <p class="text-muted">
                            هنوز هیچ نظری توسط کاربران ثبت نشده است.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>

@endsection
