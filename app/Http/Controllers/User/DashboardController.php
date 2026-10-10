<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $orders = $user->orders()
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'orders' => $user->orders()->count(),
            'active_orders' => $user->orders()
                ->whereIn('status', ['pending', 'processing', 'shipped'])
                ->count(),
            'addresses' => $user->addresses()->count(),
            'wishlist_items' => $user->wishlists()
                ->withCount('items')
                ->get()
                ->sum('items_count'),
        ];

        return view('dashboard', compact('user', 'orders', 'stats'));
    }
}
