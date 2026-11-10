<?php

namespace Tests\Feature;

use App\Models\FavoriteProvider;
use App\Models\ProviderProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteProviderTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_view_favorite_providers(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $providerUser = User::factory()->create(['role' => 'provider']);
        $provider = ProviderProfile::create([
            'user_id' => $providerUser->id,
            'profession' => 'Plumber',
            'city' => 'Ho',
            'verification_status' => 'approved',
        ]);
        FavoriteProvider::create([
            'user_id' => $customer->id,
            'provider_profile_id' => $provider->id,
        ]);

        $this->assertSame(
            [$provider->id],
            $customer->favoriteProviders()->pluck('provider_profiles.id')->all()
        );
        $this->assertTrue($provider->favoritedBy()->whereKey($customer->id)->exists());

        $this->actingAs($customer)
            ->get(route('favorites.index'))
            ->assertSuccessful()
            ->assertSee($providerUser->name);
    }
}
