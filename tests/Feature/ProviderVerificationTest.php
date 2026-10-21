<?php

namespace Tests\Feature;

use App\Models\ProviderProfile;
use App\Models\ProviderVerification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProviderVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_provider_can_submit_an_identity_document_for_review(): void
    {
        Storage::fake('local');
        $provider = User::factory()->create(['role' => 'provider', 'is_active' => true]);
        $profile = ProviderProfile::create([
            'user_id' => $provider->id,
            'profession' => 'Plumber',
            'city' => 'Ho',
            'verification_status' => 'pending',
        ]);

        $this->actingAs($provider)->post('/provider/verification', [
            'document_type' => 'ghana_card',
            'document' => UploadedFile::fake()->createWithContent('ghana-card.pdf', "%PDF-1.4\nLocalPro test document\n%%EOF"),
        ])->assertRedirect();

        $verification = ProviderVerification::where('provider_profile_id', $profile->id)->firstOrFail();
        $this->assertSame('pending', $verification->status);
        Storage::disk('local')->assertExists($verification->document_path);
    }
}
