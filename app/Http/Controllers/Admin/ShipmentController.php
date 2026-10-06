<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\Order;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    /**
     * لیست ارسال‌ها
     */
    public function index()
    {
        $shipments = Shipment::with([
            'order.user',
        ])
            ->latest()
            ->paginate(15);

        return view(
            'admin.shipments.index',
            compact('shipments')
        );
    }


    /**
     * نمایش اطلاعات ارسال
     */
    public function show(Shipment $shipment)
    {
        $shipment->load([
            'order.user',
            'order.address',
            'order.items.product',
        ]);

        return view(
            'admin.shipments.show',
            compact('shipment')
        );
    }


    /**
     * فرم ایجاد ارسال
     */
    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | فقط سفارش‌های پرداخت‌شده‌ای که ارسال ندارند
        |--------------------------------------------------------------------------
        */

        $orders = Order::with('user')
            ->where('payment_status', 'paid')
            ->whereDoesntHave('shipment')
            ->latest()
            ->get();

        return view(
            'admin.shipments.create',
            compact('orders')
        );
    }


    /**
     * ذخیره ارسال
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => [
                'required',
                'integer',
                'exists:orders,id',
            ],

            'shipping_method' => [
                'required',
                'string',
                'max:255',
            ],

            'tracking_code' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                'in:pending,processing,shipped,delivered,returned',
            ],

            'shipped_at' => [
                'nullable',
                'date',
            ],

            'delivered_at' => [
                'nullable',
                'date',
                'after_or_equal:shipped_at',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | پیدا کردن سفارش
        |--------------------------------------------------------------------------
        */

        $order = Order::findOrFail(
            $validated['order_id']
        );

        /*
        |--------------------------------------------------------------------------
        | جلوگیری از ارسال تکراری
        |--------------------------------------------------------------------------
        */

        if ($order->shipment()->exists()) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'برای این سفارش قبلاً اطلاعات ارسال ثبت شده است.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | ایجاد ارسال
        |--------------------------------------------------------------------------
        */

        $shipment = Shipment::create([
            'order_id' => $order->id,

            'shipping_method' =>
                $validated['shipping_method'],

            'tracking_code' =>
                $validated['tracking_code'] ?? null,

            'status' =>
                $validated['status'],

            'shipped_at' =>
                $validated['shipped_at'] ?? null,

            'delivered_at' =>
                $validated['delivered_at'] ?? null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | هماهنگ کردن وضعیت سفارش
        |--------------------------------------------------------------------------
        */

        $this->syncOrderStatus($shipment);

        return redirect()
            ->route(
                'admin.shipments.show',
                $shipment
            )
            ->with(
                'success',
                'اطلاعات ارسال با موفقیت ثبت شد.'
            );
    }


    /**
     * فرم ویرایش ارسال
     */
    public function edit(Shipment $shipment)
    {
        $shipment->load([
            'order.user',
            'order.address',
        ]);

        return view(
            'admin.shipments.edit',
            compact('shipment')
        );
    }


    /**
     * بروزرسانی ارسال
     */
    public function update(
        Request $request,
        Shipment $shipment
    ) {
        $validated = $request->validate([
            'shipping_method' => [
                'required',
                'string',
                'max:255',
            ],

            'tracking_code' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                'in:pending,processing,shipped,delivered,returned',
            ],

            'shipped_at' => [
                'nullable',
                'date',
            ],

            'delivered_at' => [
                'nullable',
                'date',
                'after_or_equal:shipped_at',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | بروزرسانی
        |--------------------------------------------------------------------------
        */

        $shipment->update([
            'shipping_method' =>
                $validated['shipping_method'],

            'tracking_code' =>
                $validated['tracking_code'] ?? null,

            'status' =>
                $validated['status'],

            'shipped_at' =>
                $validated['shipped_at'] ?? null,

            'delivered_at' =>
                $validated['delivered_at'] ?? null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | هماهنگ کردن وضعیت سفارش
        |--------------------------------------------------------------------------
        */

        $this->syncOrderStatus($shipment);

        return redirect()
            ->route(
                'admin.shipments.show',
                $shipment
            )
            ->with(
                'success',
                'اطلاعات ارسال با موفقیت بروزرسانی شد.'
            );
    }


    /**
     * تغییر سریع وضعیت ارسال
     */
    public function updateStatus(
        Request $request,
        Shipment $shipment
    ) {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:pending,processing,shipped,delivered,returned',
            ],
        ]);

        $shipment->update([
            'status' => $validated['status'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | ثبت خودکار تاریخ ارسال
        |--------------------------------------------------------------------------
        */

        if (
            $shipment->status === 'shipped' &&
            !$shipment->shipped_at
        ) {
            $shipment->update([
                'shipped_at' => now(),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | ثبت خودکار تاریخ تحویل
        |--------------------------------------------------------------------------
        */

        if (
            $shipment->status === 'delivered' &&
            !$shipment->delivered_at
        ) {
            $shipment->update([
                'delivered_at' => now(),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | هماهنگ کردن وضعیت سفارش
        |--------------------------------------------------------------------------
        */

        $this->syncOrderStatus($shipment);

        return back()->with(
            'success',
            'وضعیت ارسال با موفقیت تغییر کرد.'
        );
    }


    /**
     * حذف ارسال
     */
    public function destroy(Shipment $shipment)
    {
        $shipment->delete();

        return redirect()
            ->route('admin.shipments.index')
            ->with(
                'success',
                'اطلاعات ارسال با موفقیت حذف شد.'
            );
    }


    /**
     * هماهنگ کردن وضعیت سفارش با وضعیت ارسال
     */
    private function syncOrderStatus(Shipment $shipment)
    {
        $order = $shipment->order;

        if (!$order) {
            return;
        }

        switch ($shipment->status) {

            case 'pending':
            case 'processing':

                $order->update([
                    'status' => 'processing',
                ]);

                break;


            case 'shipped':

                $order->update([
                    'status' => 'shipped',
                ]);

                break;


            case 'delivered':

                $order->update([
                    'status' => 'delivered',
                ]);

                break;


            case 'returned':

                $order->update([
                    'status' => 'returned',
                ]);

                break;
        }
    }
}
