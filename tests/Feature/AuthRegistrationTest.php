<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_links_to_registration_and_guest_can_register(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('حساب کاربری ندارید؟')
            ->assertSee(route('register'), false);

        $this->get(route('register'))
            ->assertOk()
            ->assertSee('ثبت‌نام');

        $this->post(route('register'), [
            'name' => 'کاربر آزمایشی',
            'email' => 'new-customer@example.test',
            'password' => 'safe-password',
            'password_confirmation' => 'safe-password',
        ])
            ->assertRedirect('/dashboard')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'name' => 'کاربر آزمایشی',
            'email' => 'new-customer@example.test',
        ]);
        $this->assertAuthenticated();
    }
}
