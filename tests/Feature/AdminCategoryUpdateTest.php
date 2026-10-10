<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCategoryUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_admin_can_open_the_category_edit_page_from_its_edit_route(): void
    {
        $admin = $this->admin();
        $category = Category::create([
            'name' => 'پوشاک',
            'slug' => 'apparel',
            'status' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.categories.edit', $category))
            ->assertOk()
            ->assertViewIs('admin.categories.edit')
            ->assertSee(route('admin.categories.update', $category), false)
            ->assertSee('پوشاک');
    }

    public function test_admin_can_update_a_category_without_replacing_its_image(): void
    {
        $admin = $this->admin();
        $category = Category::create([
            'name' => 'پوشاک',
            'slug' => 'apparel',
            'status' => true,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.categories.update', $category), [
                'name' => 'پوشاک زنانه',
                'slug' => 'womens-apparel',
                'parent_id' => '',
                'description' => 'دسته‌بندی به‌روزشده',
                'status' => '1',
            ])
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'پوشاک زنانه',
            'slug' => 'womens-apparel',
            'description' => 'دسته‌بندی به‌روزشده',
        ]);
    }

    public function test_admin_can_replace_a_category_image(): void
    {
        $admin = $this->admin();
        $category = Category::create([
            'name' => 'پوشاک',
            'slug' => 'apparel',
            'status' => true,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.categories.update', $category), [
                'name' => 'پوشاک',
                'slug' => 'apparel',
                'parent_id' => '',
                'description' => '',
                'status' => '1',
                'image' => UploadedFile::fake()->image('category.png'),
            ])
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success');

        $category->refresh();
        $this->assertNotEmpty($category->image);
        Storage::disk('public')->assertExists($category->image);
    }

    private function admin(): User
    {
        $admin = User::create([
            'name' => 'مدیر',
            'email' => fake()->unique()->safeEmail(),
            'password' => 'safe-password',
        ]);

        AdminUser::create(['user_id' => $admin->id, 'role' => 'admin']);

        return $admin;
    }
}
