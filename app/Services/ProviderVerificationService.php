<?php

namespace App\Services;

use App\Models\ProviderProfile;
use App\Models\ProviderVerification;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProviderVerificationService
{
    public function __construct(private NotificationService $notifications)
    {
    }

    public function submit(ProviderProfile $profile, string $documentType, UploadedFile $document): ProviderVerification
    {
        $path = $document->store('provider-verifications/' . $profile->id, 'local');
        try {
            $verification = ProviderVerification::create([
                'provider_profile_id' => $profile->id,
                'document_type' => $documentType,
                'document_path' => $path,
                'status' => 'pending',
            ]);
        } catch (\Throwable $exception) {
            Storage::disk('local')->delete($path);
            throw $exception;
        }

        User::where('role', 'admin')->where('is_active', true)->get()->each(function (User $admin) use ($profile) {
            $this->notifications->send(
                $admin,
                'Provider verification submitted',
                $profile->user->name . ' submitted documents for review.'
            );
        });

        return $verification;
    }

    public function review(ProviderProfile $profile, User $reviewer, string $status): void
    {
        DB::transaction(function () use ($profile, $reviewer, $status) {
            $lockedProfile = ProviderProfile::whereKey($profile->id)->lockForUpdate()->firstOrFail();

            if ($status === 'approved' && ! $lockedProfile->verifications()->whereIn('status', ['pending', 'approved'])->exists()) {
                throw ValidationException::withMessages([
                    'verification_status' => 'The provider must submit identity documentation before approval.',
                ]);
            }

            $lockedProfile->update(['verification_status' => $status]);
            $lockedProfile->verifications()->where('status', 'pending')->update([
                'status' => $status,
                'reviewer_id' => $reviewer->id,
                'reviewed_at' => now(),
            ]);
        });

        $profile->loadMissing('user');
        $this->notifications->send(
            $profile->user,
            'Provider verification ' . $status,
            'Your LocalPro provider verification was ' . $status . '. Sign in to review your provider dashboard for next steps.'
        );
    }
}
