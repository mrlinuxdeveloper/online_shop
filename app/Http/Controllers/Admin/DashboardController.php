<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ProductVariant;
use App\Models\Shipment;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Basic Statistics
        |--------------------------------------------------------------------------
        */

        $usersCount = User::count();

        $productsCount = Product::count();

        $ordersCount = Order::count();


        /*
        |--------------------------------------------------------------------------
        | Order Statistics
        |--------------------------------------------------------------------------
        */

        $pendingOrders = Order::where(
            'status',
            'pending'
        )->count();

        $processingOrders = Order::where(
            'status',
            'processing'
        )->count();

        $shippedOrders = Order::where(
            'status',
            'shipped'
        )->count();

        $deliveredOrders = Order::where(
            'status',
            'delivered'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Payment Statistics
        |--------------------------------------------------------------------------
        */

        $pendingPayments = Order::where(
            'payment_status',
            'pending'
        )->count();

        $paidOrders = Order::where(
            'payment_status',
            'paid'
        )->count();

        $failedPayments = Order::where(
            'payment_status',
            'failed'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Sales Statistics
        |--------------------------------------------------------------------------
        */

        $totalSales = Order::where(
            'payment_status',
            'paid'
        )->sum('total_price');


        $monthlySales = Order::where(
            'payment_status',
            'paid'
        )
            ->whereMonth(
                'created_at',
                now()->month
            )
            ->whereYear(
                'created_at',
                now()->year
            )
            ->sum('total_price');


        /*
        |--------------------------------------------------------------------------
        | Low Stock Products
        |--------------------------------------------------------------------------
        */

        $lowStockVariants = ProductVariant::where(
            'status',
            true
        )
            ->where(
                'stock',
                '<=',
                5
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Shipment Statistics
        |--------------------------------------------------------------------------
        */

        $pendingShipments = Shipment::where(
            'status',
            'pending'
        )->count();

        $processingShipments = Shipment::where(
            'status',
            'processing'
        )->count();

        $shippedShipments = Shipment::where(
            'status',
            'shipped'
        )->count();

        $deliveredShipments = Shipment::where(
            'status',
            'delivered'
        )->count();

        $returnedShipments = Shipment::where(
            'status',
            'returned'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Recent Orders
        |--------------------------------------------------------------------------
        */

        $recentOrders = Order::with([
            'user',
        ])
            ->latest()
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Recent Payments
        |--------------------------------------------------------------------------
        */

        $recentPayments = Payment::with([
            'user',
            'order',
        ])
            ->latest()
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Recent Shipments
        |--------------------------------------------------------------------------
        */

        $recentShipments = Shipment::with([
            'order.user',
        ])
            ->latest()
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Return Dashboard
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.dashboard',
            compact(
                'usersCount',
                'productsCount',
                'ordersCount',

                'pendingOrders',
                'processingOrders',
                'shippedOrders',
                'deliveredOrders',

                'pendingPayments',
                'paidOrders',
                'failedPayments',

                'totalSales',
                'monthlySales',

                'lowStockVariants',

                'pendingShipments',
                'processingShipments',
                'shippedShipments',
                'deliveredShipments',
                'returnedShipments',

                'recentOrders',
                'recentPayments',
                'recentShipments'
            )
        );
    }
}
