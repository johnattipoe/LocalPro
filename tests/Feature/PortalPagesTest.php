<?php

namespace Tests\Feature;

use App\Models\ProviderProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortalPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\ServiceCategorySeeder::class);
    }

    public function test_customer_portal_pages_render(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $this->assertSame('customer', $customer->role);
        $this->assertTrue($customer->is_active);

        foreach ([
            '/customer/dashboard', '/customer/profile', '/customer/payments', '/customer/history',
            '/customer/settings', '/customer/reviews', '/customer/requests', '/customer/bookings',
        ] as $path) {
            $this->actingAs($customer)->get($path)->assertSuccessful($path);
        }
    }

    public function test_provider_portal_pages_render(): void
    {
        $provider = User::factory()->create(['role' => 'provider']);
        ProviderProfile::create([
            'user_id' => $provider->id,
            'profession' => 'Plumber',
            'city' => 'Ho',
            'verification_status' => 'pending',
        ]);

        foreach ([
            '/provider/dashboard', '/provider/bookings', '/provider/profile', '/provider/settings',
            '/provider/availability', '/provider/verification', '/provider/earnings', '/provider/reviews',
            '/provider/services', '/provider/services/create',
        ] as $path) {
            $this->actingAs($provider)->get($path)->assertSuccessful($path);
        }
    }

    public function test_admin_portal_pages_render(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        foreach ([
            '/admin/dashboard', '/admin/users', '/admin/providers', '/admin/categories', '/admin/bookings',
            '/admin/reports', '/admin/analytics', '/admin/payments', '/admin/payouts', '/admin/support', '/admin/settings',
        ] as $path) {
            $this->actingAs($admin)->get($path)->assertSuccessful($path);
        }
    }
}
