@extends('admin.layouts.AdminMasterLayout')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3>
                    تاریخچه تراکنش‌های انبار
                </h3>

                <p class="text-muted mb-0">
                    سابقه ورود و خروج موجودی
                </p>

            </div>

            <a
                href="{{ route('admin.inventory.index') }}"
                class="btn btn-outline-dark"
            >
                بازگشت به موجودی
            </a>

        </div>


        @if($transactions->count())

            <div class="card">

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover">

                            <thead>

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    محصول
                                </th>

                                <th>
                                    تنوع
                                </th>

                                <th>
                                    نوع
                                </th>

                                <th>
                                    تعداد
                                </th>

                                <th>
                                    قبل
                                </th>

                                <th>
                                    بعد
                                </th>

                                <th>
                                    توضیحات
                                </th>

                                <th>
                                    تاریخ
                                </th>

                            </tr>

                            </thead>

                            <tbody>

                            @foreach($transactions as $transaction)

                                <tr>

                                    <td>
                                        {{ $transaction->id }}
                                    </td>


                                    <td>
                                        {{ $transaction->product?->name ?? '-' }}
                                    </td>


                                    <td>

                                        @if($transaction->variant)

                                            {{ $transaction->variant->sku }}

                                            @if($transaction->variant->size)

                                                -
                                                {{ $transaction->variant->size->name }}

                                            @endif

                                            @if($transaction->variant->color)

                                                -
                                                {{ $transaction->variant->color->name }}

                                            @endif

                                        @else

                                            -

                                        @endif

                                    </td>


                                    <td>

                                        @switch($transaction->type)

                                            @case('purchase')

                                                <span class="badge bg-success">
                                                    خرید
                                                </span>

                                                @break

                                            @case('sale')

                                                <span class="badge bg-danger">
                                                    فروش
                                                </span>

                                                @break

                                            @case('return')

                                                <span class="badge bg-info">
                                                    برگشت
                                                </span>

                                                @break

                                            @case('adjustment')

                                                <span class="badge bg-warning text-dark">
                                                    اصلاح
                                                </span>

                                                @break

                                        @endswitch

                                    </td>


                                    <td>

                                        @if($transaction->quantity > 0)

                                            <span class="text-success">
                                                +{{ $transaction->quantity }}
                                            </span>

                                        @else

                                            <span class="text-danger">
                                                {{ $transaction->quantity }}
                                            </span>

                                        @endif

                                    </td>


                                    <td>
                                        {{ $transaction->stock_before }}
                                    </td>


                                    <td>
                                        {{ $transaction->stock_after }}
                                    </td>


                                    <td>
                                        {{ $transaction->description ?? '-' }}
                                    </td>


                                    <td>
                                        {{ $transaction->created_at?->format('Y/m/d H:i') }}
                                    </td>

                                </tr>

                            @endforeach

                            </tbody>

                        </table>

                    </div>


                    <div class="mt-3">

                        {{ $transactions->links() }}

                    </div>

                </div>

            </div>

        @else

            <div class="alert alert-info">
                هنوز هیچ تراکنشی ثبت نشده است.
            </div>

        @endif

    </div>

@endsection
