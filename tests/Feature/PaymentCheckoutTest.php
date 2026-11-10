<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\ProviderProfile;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PaymentCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_repeated_checkout_reuses_the_pending_payment(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $providerUser = User::factory()->create(['role' => 'provider']);
        $provider = ProviderProfile::create([
            'user_id' => $providerUser->id,
            'profession' => 'Plumber',
            'city' => 'Ho',
            'verification_status' => 'approved',
        ]);
        $serviceRequest = ServiceRequest::create([
            'customer_id' => $customer->id,
            'provider_profile_id' => $provider->id,
            'description' => 'Repair a leaking kitchen sink.',
            'city' => 'Ho',
            'status' => 'completed',
        ]);
        Booking::create([
            'customer_id' => $customer->id,
            'provider_profile_id' => $provider->id,
            'service_request_id' => $serviceRequest->id,
            'final_price' => '125.00',
            'status' => 'completed',
        ]);

        Config::set('services.paystack.secret_key', 'test-secret');
        Config::set('services.paystack.base_url', 'https://api.paystack.co');
        $references = [];
        Http::fake(function (ClientRequest $request) use (&$references) {
            $references[] = $request->data()['reference'];

            return Http::response([
                'status' => true,
                'data' => ['authorization_url' => 'https://checkout.paystack.com/test'],
            ]);
        });

        $checkoutUrl = route('customer.payments.initialize', $serviceRequest);
        $this->actingAs($customer)->post($checkoutUrl)->assertRedirect('https://checkout.paystack.com/test');
        $this->post($checkoutUrl)->assertRedirect('https://checkout.paystack.com/test');

        $this->assertSame(1, Payment::where('booking_id', $serviceRequest->booking->id)->count());
        $this->assertCount(2, $references);
        $this->assertSame($references[0], $references[1]);
    }
}
