@extends('admin.layouts.AdminMasterLayout')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">گزارش‌ها</h3>
                <p class="text-muted mb-0">گزارش فروش، سفارش‌ها و محصولات</p>
            </div>
            <button onclick="window.print()" class="btn btn-dark">
                چاپ گزارش
            </button>
        </div>

        {{-- فیلترها --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">فیلتر گزارش</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.reports.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">از تاریخ</label>
                            <input type="date" name="from_date" class="form-control"
                                   value="{{ $fromDate }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">تا تاریخ</label>
                            <input type="date" name="to_date" class="form-control"
                                   value="{{ $toDate }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">وضعیت سفارش</label>
                            <select name="status" class="form-select">
                                <option value="">همه وضعیت‌ها</option>
                                <option value="pending" @selected($status === 'pending')>در انتظار</option>
                                <option value="processing" @selected($status === 'processing')>در حال پردازش</option>
                                <option value="shipped" @selected($status === 'shipped')>ارسال شده</option>
                                <option value="delivered" @selected($status === 'delivered')>تحویل شده</option>
                                <option value="returned" @selected($status === 'returned')>مرجوع شده</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                اعمال فیلتر
                            </button>
                            <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
                                حذف فیلتر
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Statistics --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-muted">کل سفارش‌ها</h6>
                        <h3>{{ number_format($totalOrders) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-muted">سفارش‌های پرداخت شده</h6>
                        <h3>{{ number_format($paidOrders) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-muted">پرداخت‌های ناموفق</h6>
                        <h3>{{ number_format($failedOrders) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-muted">پرداخت‌های در انتظار</h6>
                        <h3>{{ number_format($pendingOrders) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- آمار فروش --}}
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-muted">فروش کل</h6>
                        <h3>{{ number_format($totalSales) }} تومان</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-muted">میانگین مبلغ سفارش</h6>
                        <h3>{{ number_format($averageOrderValue) }} تومان</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Daily Chart --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">نمودار فروش روزانه</h5>
            </div>
            <div class="card-body">
                <div style="height: 350px;">
                    <canvas id="dailySalesChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Monthly Chart --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">نمودار فروش ماهانه</h5>
            </div>
            <div class="card-body">
                <div style="height: 350px;">
                    <canvas id="monthlySalesChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Daily Sales --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">فروش روزانه</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                        <tr>
                            <th>تاریخ</th>
                            <th>تعداد سفارش</th>
                            <th>مبلغ فروش</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($dailySales as $sale)
                            <tr>
                                <td>{{ $sale->date }}</td>
                                <td>{{ number_format($sale->orders_count) }}</td>
                                <td>{{ number_format($sale->total_sales) }} تومان</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">
                                    اطلاعاتی برای نمایش وجود ندارد.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Monthly Sales --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">فروش ماهانه</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                        <tr>
                            <th>ماه</th>
                            <th>تعداد سفارش</th>
                            <th>مبلغ فروش</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($monthlySales as $sale)
                            <tr>
                                <td>{{ $sale->month }}</td>
                                <td>{{ number_format($sale->orders_count) }}</td>
                                <td>{{ number_format($sale->total_sales) }} تومان</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">
                                    اطلاعاتی برای نمایش وجود ندارد.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- محصولات پرفروش --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">پرفروش‌ترین محصولات</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>محصول</th>
                            <th>تعداد فروش</th>
                            <th>مبلغ فروش</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($bestSellingProducts as $index => $product)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ number_format($product->total_quantity) }}</td>
                                <td>{{ number_format($product->total_sales) }} تومان</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    اطلاعاتی برای نمایش وجود ندارد.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const dailyLabels = @json($dailyChartLabels);
        const dailyData = @json($dailyChartData);
        const monthlyLabels = @json($monthlyChartLabels);
        const monthlyData = @json($monthlyChartData);

        new Chart(document.getElementById('dailySalesChart'), {
            type: 'line',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'فروش روزانه',
                    data: dailyData,
                    fill: false,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        new Chart(document.getElementById('monthlySalesChart'), {
            type: 'bar',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'فروش ماهانه',
                    data: monthlyData
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
