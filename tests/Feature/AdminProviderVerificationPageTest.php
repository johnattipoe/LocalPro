<?php

namespace Tests\Feature;

use App\Models\ProviderProfile;
use App\Models\ProviderVerification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProviderVerificationPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_review_provider_experience_and_uploaded_documents(): void
    {
        $disk = config('filesystems.default');
        Storage::fake($disk);
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $provider = User::factory()->create([
            'role' => 'provider',
            'name' => 'Ama Provider',
            'email' => 'ama@example.test',
            'phone' => '0240000000',
            'is_active' => true,
        ]);
        $profile = ProviderProfile::create([
            'user_id' => $provider->id,
            'profession' => 'Electrician',
            'city' => 'Ho',
            'years_experience' => 8,
            'experience_summary' => 'Eight years of electrical work in Ho.',
            'service_area' => 'Ho and nearby communities',
            'verification_status' => 'pending',
        ]);
        $path = 'provider-verifications/'.$profile->id.'/certificate.pdf';
        Storage::disk($disk)->put($path, '%PDF test');
        $document = ProviderVerification::create([
            'provider_profile_id' => $profile->id,
            'document_type' => 'professional_certificate',
            'document_path' => $path,
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.providers.index'))
            ->assertOk()
            ->assertSee('Ama Provider')
            ->assertSee('8', false)
            ->assertSee('Eight years of electrical work in Ho.')
            ->assertSee('Ho and nearby communities')
            ->assertSee('Professional Certificate')
            ->assertSee(route('admin.verification.download', $document))
            ->assertSee('Approve')
            ->assertSee('Reject');
    }
}
