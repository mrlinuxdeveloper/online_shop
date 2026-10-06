<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $status = $request->get('status');

        $ordersQuery = Order::query();

        if ($fromDate) {
            $ordersQuery->whereDate('created_at', '>=', $fromDate);
        }

        if ($toDate) {
            $ordersQuery->whereDate('created_at', '<=', $toDate);
        }

        if ($status) {
            $ordersQuery->where('status', $status);
        }

        $totalOrders = (clone $ordersQuery)->count();

        $paidOrders = (clone $ordersQuery)
            ->where('payment_status', 'paid')
            ->count();

        $failedOrders = (clone $ordersQuery)
            ->where('payment_status', 'failed')
            ->count();

        $pendingOrders = (clone $ordersQuery)
            ->where('payment_status', 'pending')
            ->count();

        $totalSales = (clone $ordersQuery)
            ->where('payment_status', 'paid')
            ->sum('total_price');

        $averageOrderValue = $paidOrders > 0
            ? $totalSales / $paidOrders
            : 0;

        $dailySales = (clone $ordersQuery)
            ->where('payment_status', 'paid')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as orders_count'),
                DB::raw('SUM(total_price) as total_sales')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'asc')
            ->get();

        $monthlySales = (clone $ordersQuery)
            ->where('payment_status', 'paid')
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('COUNT(*) as orders_count'),
                DB::raw('SUM(total_price) as total_sales')
            )
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->orderBy('month', 'asc')
            ->get();

        $bestSellingProducts = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.payment_status', 'paid')
            ->when($fromDate, function ($query) use ($fromDate) {
                $query->whereDate('orders.created_at', '>=', $fromDate);
            })
            ->when($toDate, function ($query) use ($toDate) {
                $query->whereDate('orders.created_at', '<=', $toDate);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('orders.status', $status);
            })
            ->select(
                'order_items.product_id',
                'order_items.product_name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.total) as total_sales')
            )
            ->groupBy(
                'order_items.product_id',
                'order_items.product_name'
            )
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        $dailyChartLabels = $dailySales
            ->pluck('date')
            ->values();

        $dailyChartData = $dailySales
            ->pluck('total_sales')
            ->map(fn ($value) => (float) $value)
            ->values();

        $monthlyChartLabels = $monthlySales
            ->pluck('month')
            ->values();

        $monthlyChartData = $monthlySales
            ->pluck('total_sales')
            ->map(fn ($value) => (float) $value)
            ->values();

        return view(
            'admin.reports.index',
            compact(
                'fromDate',
                'toDate',
                'status',
                'totalOrders',
                'paidOrders',
                'failedOrders',
                'pendingOrders',
                'totalSales',
                'averageOrderValue',
                'dailySales',
                'monthlySales',
                'bestSellingProducts',
                'dailyChartLabels',
                'dailyChartData',
                'monthlyChartLabels',
                'monthlyChartData'
            )
        );
    }
}
