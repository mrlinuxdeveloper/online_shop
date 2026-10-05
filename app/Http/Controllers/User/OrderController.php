<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * لیست سفارش‌های کاربر واردشده
     */
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with([
                'items',
                'payments',
                'invoice',
            ])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * جزئیات یک سفارش
     */
    public function show(Order $order)
    {
        // جلوگیری از مشاهده سفارش کاربران دیگر
        abort_unless(
            (int) $order->user_id === (int) auth()->id(),
            403
        );

        $order->load([
            'items.product.images',
            'items.variant.size',
            'items.variant.color',
            'address',
            'invoice',
            'payments',
        ]);

        return view('orders.show', compact('order'));
    }
}
