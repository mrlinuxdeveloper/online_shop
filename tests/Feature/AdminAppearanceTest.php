<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAppearanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_homepage_appearance_and_slides(): void
    {
        $user = User::create([
            'name' => 'مدیر',
            'email' => 'admin@example.test',
            'password' => 'safe-password',
        ]);
        AdminUser::create(['user_id' => $user->id, 'role' => 'admin']);

        $this->actingAs($user)
            ->get(route('admin.appearance.edit'))
            ->assertOk()
            ->assertSee('اسلایدر صفحهٔ اصلی');

        $this->put(route('admin.appearance.update'), [
            'logo_text' => 'فروشگاه نمونه',
            'promo_text' => 'ارسال رایگان برای خریدهای ویژه',
            'footer_tagline' => 'انتخابی برای هر سلیقه',
            'instagram_url' => 'https://instagram.com/example-shop',
            'telegram_url' => 'https://t.me/example_shop',
            'youtube_url' => 'https://youtube.com/@example-shop',
            'primary_color' => '#661122',
            'accent_color' => '#dd2244',
            'show_categories' => '1',
            'show_latest' => '1',
            'slides' => [[
                'eyebrow' => 'پیشنهاد فصل',
                'title' => 'یک عنوان تازه',
                'description' => 'توضیح اسلاید',
                'button_text' => 'خرید کنید',
                'button_url' => '/products',
                'active' => '1',
            ]],
        ])->assertRedirect(route('admin.appearance.edit'))
            ->assertSessionHas('success');

        $appearance = SiteSetting::getValue('appearance');
        $this->assertSame('فروشگاه نمونه', $appearance['logo_text']);
        $this->assertSame('یک عنوان تازه', $appearance['slides'][0]['title']);
        $this->assertSame('/products', $appearance['slides'][0]['button_url']);
        $this->assertSame('https://instagram.com/example-shop', $appearance['instagram_url']);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('یک عنوان تازه')
            ->assertSee('ارسال رایگان برای خریدهای ویژه')
            ->assertSee('https://instagram.com/example-shop')
            ->assertSee('https://t.me/example_shop')
            ->assertSee('https://youtube.com/@example-shop');
    }
}
