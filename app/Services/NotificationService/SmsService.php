<?php

namespace App\Services\NotificationService;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function send(string $to, string $body): array
    {
        $apiKey = config('services.sms.api_key');
        $sender = config('services.sms.sender');

        if (! $apiKey || ! $sender) {
            Log::warning('SMS credentials not configured; SMS skipped.');

            return ['success' => false, 'error' => 'SMS not configured.'];
        }

        try {
            $response = Http::acceptJson()
                ->timeout(15)
                ->post('https://api.mnotify.com/api/sms/quick?'.http_build_query(['key' => $apiKey]), [
                    'recipient' => [$to],
                    'sender' => $sender,
                    'message' => $body,
                    'is_schedule' => false,
                    'schedule_date' => '',
                ]);

            if (! $response->successful()) {
                Log::error('BMS SMS API returned an unsuccessful HTTP status.', [
                    'status' => $response->status(),
                ]);

                return ['success' => false, 'error' => 'SMS API error.'];
            }

            $data = $response->json();
            if (($data['status'] ?? null) === 'success' && (string) ($data['code'] ?? '') === '2000') {
                return ['success' => true, 'message_id' => $data['summary']['_id'] ?? null];
            }

            Log::error('BMS SMS API rejected the message.', [
                'code' => $data['code'] ?? null,
            ]);

            return ['success' => false, 'error' => 'SMS send failed.'];
        } catch (ConnectionException $exception) {
            Log::error('BMS SMS connection failed.', ['exception' => get_class($exception)]);

            return ['success' => false, 'error' => 'SMS request failed.'];
        }
    }
}
