<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Orders List
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $orders = Order::with([
            'user',
            'address',
            'invoice',
        ])
            ->latest()
            ->paginate(15);

        return view(
            'admin.orders.index',
            compact('orders')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Order Details
    |--------------------------------------------------------------------------
    */

    public function show(Order $order)
    {
        $order->load([
            'user',
            'address',
            'items.product',
            'items.variant.size',
            'items.variant.color',
            'invoice',
            'payments',
            'shipment',
        ]);

        return view(
            'admin.orders.show',
            compact('order')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Order Status
    |--------------------------------------------------------------------------
    */

    public function updateStatus(
        Request $request,
        Order $order
    ) {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:pending,processing,shipped,delivered,cancelled'
            ],
        ]);

        $order->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'وضعیت سفارش با موفقیت به‌روزرسانی شد.'
            );
    }
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => [
                'required',
                'in:pending,paid,failed,refunded'
            ],
        ]);

        $order->update([
            'payment_status' => $validated['payment_status'],
        ]);

        return redirect()
            ->back()
            ->with('success', 'وضعیت پرداخت با موفقیت به‌روزرسانی شد.');
    }
    public function invoice(Order $order)
    {
        $order->load([
            'user',
            'address',
            'items',
            'invoice',
        ]);

        return view('admin.orders.invoice', compact('order'));
    }
    public function updateShipment(Request $request, Order $order)
    {
        $validated = $request->validate([
            'shipping_method' => [
                'nullable',
                'string',
                'max:255',
            ],

            'tracking_code' => [
                'nullable',
                'string',
                'max:255',
            ],

            'shipment_status' => [
                'required',
                'in:pending,processing,shipped,delivered,returned',
            ],
        ]);

        $shipment = $order->shipment()->updateOrCreate(
            [
                'order_id' => $order->id,
            ],
            [
                'shipping_method' => $validated['shipping_method'] ?? null,
                'tracking_code' => $validated['tracking_code'] ?? null,
                'status' => $validated['shipment_status'],
            ]
        );

        if (
            $validated['shipment_status'] === 'shipped' &&
            !$shipment->shipped_at
        ) {
            $shipment->update([
                'shipped_at' => now(),
            ]);
        }

        if (
            $validated['shipment_status'] === 'delivered' &&
            !$shipment->delivered_at
        ) {
            $shipment->update([
                'delivered_at' => now(),
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'اطلاعات ارسال با موفقیت به‌روزرسانی شد.');

    }

}
