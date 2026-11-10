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
        $disk = config('filesystems.default');
        Storage::fake($disk);
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
        Storage::disk($disk)->assertExists($verification->document_path);
    }

    public function test_provider_can_submit_a_professional_certificate(): void
    {
        $disk = config('filesystems.default');
        Storage::fake($disk);
        $provider = User::factory()->create(['role' => 'provider', 'is_active' => true]);
        $profile = ProviderProfile::create([
            'user_id' => $provider->id,
            'profession' => 'Electrician',
            'city' => 'Ho',
            'verification_status' => 'pending',
        ]);

        $this->actingAs($provider)->post('/provider/verification', [
            'document_type' => 'professional_certificate',
            'document' => UploadedFile::fake()->createWithContent('trade-certificate.pdf', "%PDF-1.4\nLocalPro test document\n%%EOF"),
        ])->assertRedirect();

        $verification = ProviderVerification::where('provider_profile_id', $profile->id)->firstOrFail();
        $this->assertSame('professional_certificate', $verification->document_type);
        Storage::disk($disk)->assertExists($verification->document_path);
    }

    public function test_provider_profile_stores_years_of_experience_and_service_history(): void
    {
        $provider = User::factory()->create(['role' => 'provider', 'is_active' => true]);
        $profile = ProviderProfile::create([
            'user_id' => $provider->id,
            'profession' => 'Plumber',
            'city' => 'Ho',
            'verification_status' => 'pending',
        ]);

        $profile->update([
            'years_experience' => 8,
            'experience_summary' => 'Eight years of plumbing work in Ho and nearby communities.',
        ]);

        $this->assertDatabaseHas('provider_profiles', [
            'id' => $profile->id,
            'years_experience' => 8,
            'experience_summary' => 'Eight years of plumbing work in Ho and nearby communities.',
        ]);
    }
}
