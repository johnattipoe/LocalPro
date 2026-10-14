<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\ProviderPayout;
use App\Models\ProviderProfile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PayoutService
{
    public function __construct(private NotificationService $notifications)
    {
    }

    public function availableAmount(ProviderProfile $provider): float
    {
        $collected = Payment::where('status', 'paid')
            ->whereHas('booking', fn ($query) => $query->where('provider_profile_id', $provider->id))
            ->sum('amount');

        $allocated = ProviderPayout::where('provider_profile_id', $provider->id)
            ->whereIn('status', ['requested', 'approved', 'paid'])
            ->sum('amount');

        return max(0, round((float) $collected - (float) $allocated, 2));
    }

    public function request(ProviderProfile $provider, User $user, float $amount): ProviderPayout
    {
        if ($provider->verification_status !== 'approved') {
            throw ValidationException::withMessages(['amount' => 'Your provider profile must be approved before requesting a payout.']);
        }

        return DB::transaction(function () use ($provider, $user, $amount) {
            $lockedProvider = ProviderProfile::whereKey($provider->id)->lockForUpdate()->firstOrFail();
            $available = $this->availableAmount($lockedProvider);
            if ($amount > $available) {
                throw ValidationException::withMessages(['amount' => 'The requested amount is above your currently available payment balance.']);
            }

            $payout = ProviderPayout::create([
                'provider_profile_id' => $lockedProvider->id,
                'requested_by' => $user->id,
                'amount' => number_format($amount, 2, '.', ''),
                'currency' => 'GHS',
                'status' => 'requested',
            ]);

            User::where('role', 'admin')->where('is_active', true)->get()->each(function (User $admin) use ($user, $payout) {
                $this->notifications->send(
                    $admin,
                    'Provider payout requested',
                    $user->name . ' requested GHS ' . number_format((float) $payout->amount, 2) . '.'
                );
            });

            return $payout;
        });
    }

    public function review(ProviderPayout $payout, User $admin, string $status, ?string $reference, ?string $note): void
    {
        DB::transaction(function () use ($payout, $admin, $status, $reference, $note) {
            $lockedPayout = ProviderPayout::whereKey($payout->id)->lockForUpdate()->firstOrFail();
            $allowed = [
                'requested' => ['approved', 'rejected'],
                'approved' => ['paid', 'rejected'],
            ];

            if (! in_array($status, $allowed[$lockedPayout->status] ?? [], true)) {
                throw ValidationException::withMessages(['status' => 'This payout has already been processed or cannot move to that status.']);
            }
            if ($status === 'paid' && ! $reference) {
                throw ValidationException::withMessages(['transfer_reference' => 'Add the external transfer reference before marking a payout as paid.']);
            }

            $lockedPayout->update([
                'status' => $status,
                'transfer_reference' => $status === 'paid' ? $reference : $lockedPayout->transfer_reference,
                'admin_note' => $note,
                'processed_at' => in_array($status, ['paid', 'rejected'], true) ? now() : null,
            ]);

            $lockedPayout->loadMissing('requester');
            $this->notifications->send(
                $lockedPayout->requester,
                'Payout request ' . $status,
                $status === 'paid'
                    ? 'Your payout was recorded as paid. Transfer reference: ' . $reference . '.'
                    : 'Your payout request was ' . $status . ($note ? ': ' . $note : '.')
            );
        });
    }
}
