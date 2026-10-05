<?php



namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * لیست سفارش‌های کاربر
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
     * جزئیات سفارش
     */
    public function show(Order $order)
    {
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


    /**
     * فاکتور سفارش
     */
    public function invoice(Order $order)
    {
        /*
         * فقط صاحب سفارش اجازه مشاهده فاکتور را دارد.
         */
        abort_unless(
            (int) $order->user_id === (int) auth()->id(),
            403
        );

        /*
         * اطلاعات مورد نیاز فاکتور
         */
        $order->load([
            'items',
            'address',
            'invoice',
            'payments',
            'user',
        ]);

        /*
         * آخرین پرداخت موفق
         */
        $payment = $order->payments()
            ->where('status', 'paid')
            ->latest()
            ->first();

        return view(
            'orders.invoice',
            compact('order', 'payment')
        );
    }
}



//
//namespace App\Http\Controllers\User;
//
//use App\Http\Controllers\Controller;
//use App\Models\Order;
//
//class OrderController extends Controller
//{
//    /**
//     * لیست سفارش‌های کاربر واردشده
//     */
//    public function index()
//    {
//        $orders = Order::where('user_id', auth()->id())
//            ->with([
//                'items',
//                'payments',
//                'invoice',
//            ])
//            ->latest()
//            ->paginate(10);
//
//        return view('orders.index', compact('orders'));
//    }
//
//    /**
//     * جزئیات یک سفارش
//     */
//    public function show(Order $order)
//    {
//        // جلوگیری از مشاهده سفارش کاربران دیگر
//        abort_unless(
//            (int) $order->user_id === (int) auth()->id(),
//            403
//        );
//
//        $order->load([
//            'items.product.images',
//            'items.variant.size',
//            'items.variant.color',
//            'address',
//            'invoice',
//            'payments',
//        ]);
//
//        return view('orders.show', compact('order'));
//    }
//}
