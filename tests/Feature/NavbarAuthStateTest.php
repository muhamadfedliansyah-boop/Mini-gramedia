<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavbarAuthStateTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_sees_login_button_and_not_logout_button(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Login');
        $response->assertDontSee('Logout');
    }

    public function test_authenticated_user_sees_logout_button_and_not_login_button(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertOk();
        $response->assertSee('Logout');
        $response->assertDontSee('Login');
    }
}
