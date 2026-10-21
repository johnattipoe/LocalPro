<?php

namespace Tests\Feature;

use App\Models\ProviderProfile;
use App\Models\ServiceCategory;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_request_a_verified_provider_and_provider_can_accept(): void
    {
        $this->seed(\Database\Seeders\ServiceCategorySeeder::class);
        $category = ServiceCategory::where('slug', 'plumber')->firstOrFail();
        $customer = User::factory()->create(['role' => 'customer', 'is_active' => true]);
        $provider = User::factory()->create(['role' => 'provider', 'is_active' => true]);
        $profile = ProviderProfile::create([
            'user_id' => $provider->id,
            'profession' => 'Plumber',
            'city' => 'Ho',
            'verification_status' => 'approved',
        ]);

        $this->actingAs($customer)->post('/customer/requests', [
            'provider_profile_id' => $profile->id,
            'service_category_id' => $category->id,
            'description' => 'Repair a leaking kitchen sink and replace the worn tap.',
            'city' => 'Ho',
            'address' => 'Market Road, Ho',
        ])->assertRedirect();

        $serviceRequest = ServiceRequest::firstOrFail();
        $this->assertDatabaseHas('bookings', ['service_request_id' => $serviceRequest->id, 'status' => 'pending']);

        $this->actingAs($provider)->patch('/provider/bookings/' . $serviceRequest->id, [
            'status' => 'accepted',
            'quoted_price' => 150,
        ])->assertRedirect();

        $this->assertDatabaseHas('service_requests', ['id' => $serviceRequest->id, 'status' => 'accepted']);
        $this->assertDatabaseHas('bookings', ['service_request_id' => $serviceRequest->id, 'status' => 'accepted', 'quoted_price' => 150]);
    }
}
