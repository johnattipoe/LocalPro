<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_registration_creates_an_active_account_and_opens_dashboard(): void
    {
        $response = $this->post('/register', [
            'name' => 'Ama Kofi',
            'email' => 'ama@example.test',
            'phone' => '0240000000',
            'city' => 'Ho',
            'role' => 'customer',
            'password' => 'StrongPass123',
            'password_confirmation' => 'StrongPass123',
        ]);

        $response->assertRedirect(route('customer.dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'ama@example.test', 'role' => 'customer', 'is_active' => true]);
        $this->get(route('customer.dashboard'))->assertSuccessful()->assertSee('Welcome to LocalPro');
    }

    public function test_provider_registration_creates_a_pending_provider_profile(): void
    {
        $response = $this->post('/register', [
            'name' => 'Kofi Mensah',
            'email' => 'kofi@example.test',
            'phone' => '0240000001',
            'city' => 'Ho',
            'role' => 'provider',
            'profession' => 'Plumber',
            'password' => 'StrongPass123',
            'password_confirmation' => 'StrongPass123',
        ]);

        $response->assertRedirect(route('provider.dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('provider_profiles', [
            'profession' => 'Plumber',
            'verification_status' => 'pending',
        ]);
        $this->get(route('provider.dashboard'))->assertSuccessful()->assertSee('Welcome to LocalPro');
    }

    public function test_customer_login_redirects_to_dashboard_with_a_success_toast(): void
    {
        User::factory()->create([
            'name' => 'John Mensah',
            'email' => 'john@example.test',
            'role' => 'customer',
            'is_active' => true,
            'password' => Hash::make('StrongPass123'),
        ]);

        $this->followingRedirects()->post('/login', [
            'email' => 'john@example.test',
            'password' => 'StrongPass123',
        ])->assertSuccessful()->assertSee('Welcome back')->assertSee('John Mensah');
    }
}
