<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PaymentService
{
    public function startCheckout(Payment $payment, string $email, string $callbackUrl, array $metadata): ?string
    {
        $secret = config('services.paystack.secret_key');
        if (! $secret) {
            return null;
        }

        $amount = (int) round(((float) $payment->amount) * 100);
        try {
            $response = Http::withToken($secret)->acceptJson()->timeout(20)
                ->post(rtrim(config('services.paystack.base_url'), '/') . '/transaction/initialize', [
                    'email' => $email,
                    'amount' => $amount,
                    'currency' => $payment->currency,
                    'reference' => $payment->provider_reference,
                    'callback_url' => $callbackUrl,
                    'metadata' => $metadata,
                ]);
        } catch (\Throwable $exception) {
            report($exception);
            $payment->update(['status' => 'failed']);
            return null;
        }

        $url = $response->json('data.authorization_url');
        $host = is_string($url) ? parse_url($url, PHP_URL_HOST) : null;
        if (! $response->successful() || ! $response->json('status') || $host !== 'checkout.paystack.com') {
            $payment->update(['status' => 'failed']);
            return null;
        }

        return $url;
    }

    public function confirm(Payment $payment): bool
    {
        if ($payment->status === 'paid') {
            return true;
        }

        $secret = config('services.paystack.secret_key');
        if (! $secret || ! $payment->provider_reference) {
            return false;
        }

        try {
            $response = Http::withToken($secret)->acceptJson()->timeout(20)
                ->get(rtrim(config('services.paystack.base_url'), '/') . '/transaction/verify/' . urlencode($payment->provider_reference));
        } catch (\Throwable $exception) {
            report($exception);
            return false;
        }

        $data = $response->json('data');
        $expectedAmount = (int) round(((float) $payment->amount) * 100);
        if (! $response->successful() || ! $response->json('status')
            || data_get($data, 'status') !== 'success'
            || (int) data_get($data, 'amount') !== $expectedAmount
            || data_get($data, 'currency') !== $payment->currency) {
            return false;
        }

        DB::transaction(function () use ($payment) {
            $lockedPayment = Payment::whereKey($payment->id)->lockForUpdate()->first();
            if ($lockedPayment && $lockedPayment->status !== 'paid') {
                $lockedPayment->update(['status' => 'paid', 'paid_at' => now()]);
            }
        });

        return true;
    }
}
