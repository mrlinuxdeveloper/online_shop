<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminBrandCreateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_admin_can_create_a_brand_and_store_its_logo(): void
    {
        $user = User::create([
            'name' => 'مدیر فروشگاه',
            'email' => 'brand-admin@example.test',
            'password' => 'safe-password',
        ]);
        AdminUser::create(['user_id' => $user->id, 'role' => 'admin']);

        $this->actingAs($user)
            ->get(route('admin.brands.create'))
            ->assertOk()
            ->assertSee(route('admin.brands.store'), false);

        $this->actingAs($user)
            ->post(route('admin.brands.store'), [
                'name' => 'Example Brand',
                'slug' => 'example-brand',
                'description' => 'A test brand',
                'status' => '1',
                'image' => UploadedFile::fake()->image('logo.png'),
            ])
            ->assertRedirect(route('admin.brands.index'))
            ->assertSessionHas('success');

        $brand = Brand::where('slug', 'example-brand')->firstOrFail();
        $this->assertNotEmpty($brand->logo);
        Storage::disk('public')->assertExists($brand->logo);
        $this->assertDatabaseHas('brands', [
            'id' => $brand->id,
            'name' => 'Example Brand',
        ]);
    }

    public function test_brand_form_shows_validation_error_next_to_the_invalid_field(): void
    {
        $user = User::create([
            'name' => 'مدیر فروشگاه',
            'email' => 'brand-validation-admin@example.test',
            'password' => 'safe-password',
        ]);
        AdminUser::create(['user_id' => $user->id, 'role' => 'admin']);

        $response = $this->actingAs($user)
            ->from(route('admin.brands.create'))
            ->followingRedirects()
            ->post(route('admin.brands.store'), [
                'slug' => 'missing-name-brand',
                'description' => '',
                'status' => '1',
            ]);

        $response->assertOk()
            ->assertSee('نام برند')
            ->assertSee('is-invalid');

        $this->assertGreaterThanOrEqual(
            2,
            substr_count($response->getContent(), 'وارد کردن نام الزامی است.'),
            'The name validation error should appear in the summary and beside its input.'
        );
    }
}
