<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\ProviderProfile;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_review_a_completed_request_once(): void
    {
        $customer = User::factory()->create(['role' => 'customer', 'is_active' => true]);
        $provider = User::factory()->create(['role' => 'provider', 'is_active' => true]);
        $profile = ProviderProfile::create([
            'user_id' => $provider->id,
            'profession' => 'Plumber',
            'city' => 'Ho',
            'verification_status' => 'approved',
        ]);
        $serviceRequest = ServiceRequest::create([
            'customer_id' => $customer->id,
            'provider_profile_id' => $profile->id,
            'description' => 'Repair a leaking kitchen sink and replace the worn tap.',
            'city' => 'Ho',
            'status' => 'completed',
        ]);
        Booking::create([
            'customer_id' => $customer->id,
            'provider_profile_id' => $profile->id,
            'service_request_id' => $serviceRequest->id,
            'status' => 'completed',
            'final_price' => 150,
        ]);

        $this->actingAs($customer)->post('/customer/requests/' . $serviceRequest->id . '/review', [
            'rating' => 5,
            'comment' => 'Arrived on time and fixed the leak well.',
        ])->assertRedirect();

        $this->assertDatabaseHas('reviews', [
            'customer_id' => $customer->id,
            'provider_profile_id' => $profile->id,
            'rating' => 5,
            'status' => 'published',
        ]);
        $this->assertEquals(1, $profile->fresh()->review_count);
    }
}
