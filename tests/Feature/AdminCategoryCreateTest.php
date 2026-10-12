<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCategoryCreateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_admin_can_create_a_category_through_the_store_route(): void
    {
        $user = User::create([
            'name' => 'مدیر فروشگاه',
            'email' => 'category-admin@example.test',
            'password' => 'safe-password',
        ]);
        AdminUser::create(['user_id' => $user->id, 'role' => 'admin']);

        $this->actingAs($user)
            ->get(route('admin.categories.create'))
            ->assertOk()
            ->assertSee(route('admin.categories.store'), false);

        $this->actingAs($user)
            ->post(route('admin.categories.store'), [
                'name' => 'لباس',
                'slug' => 'clothing',
                'parent_id' => '',
                'description' => 'دسته‌بندی اصلی',
                'status' => '1',
            ])
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'name' => 'لباس',
            'slug' => 'clothing',
            'status' => 1,
        ]);
        $this->assertSame(1, Category::count());
    }
}
