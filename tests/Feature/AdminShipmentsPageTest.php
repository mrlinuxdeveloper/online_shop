<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminShipmentsPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_shipments_list(): void
    {
        $user = User::create([
            'name' => 'مدیر فروشگاه',
            'email' => 'shipments-admin@example.test',
            'password' => 'safe-password',
        ]);
        AdminUser::create(['user_id' => $user->id, 'role' => 'admin']);

        $this->actingAs($user)
            ->get(route('admin.shipments.index'))
            ->assertOk()
            ->assertViewIs('admin.shipments.index');
    }
}
