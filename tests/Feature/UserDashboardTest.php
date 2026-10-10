<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_signed_in_user_can_view_account_dashboard_and_quick_links(): void
    {
        $user = User::create([
            'name' => 'کاربر نمونه',
            'email' => 'customer@example.test',
            'password' => 'safe-password',
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('سلام، کاربر نمونه')
            ->assertSee('آخرین سفارش‌ها')
            ->assertSee(route('orders.index'), false)
            ->assertSee(route('addresses.index'), false)
            ->assertSee(route('wishlist.index'), false)
            ->assertSee(route('cart.index'), false)
            ->assertSee('هنوز سفارشی ثبت نکرده‌ای.');
    }
}
