<?php

namespace App\Services\NotificationService;

use App\Models\PushSubscription;
use GuzzleHttp\Psr7\HttpFactory;
use Http\Adapter\Guzzle7\Client as Guzzle7Client;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class PushService
{
    public function __construct(
        private ?ClientInterface $httpClient = null,
        private ?RequestFactoryInterface $requestFactory = null,
        private ?StreamFactoryInterface $streamFactory = null,
    ) {}

    public function send(string $title, string $body, ?int $userId = null): array
    {
        $vapidPublic = config('services.push.vapid_public');
        $vapidPrivate = config('services.push.vapid_private');
        $vapidSubject = config('services.push.vapid_subject');

        if (! $vapidPublic || ! $vapidPrivate || ! $vapidSubject) {
            Log::warning('VAPID keys not configured; push notifications skipped.');

            return ['success' => false, 'error' => 'Push not configured.'];
        }

        $query = PushSubscription::query();
        if ($userId) {
            $query->where('user_id', $userId);
        }
        $subscriptions = $query->get();

        try {
            $factory = new HttpFactory;
            $httpClient = $this->httpClient ?? Guzzle7Client::createWithConfig([
                'allow_redirects' => false,
                'connect_timeout' => 5,
                'timeout' => 10,
                'verify' => true,
            ]);
            $webPush = new WebPush(
                [
                    'VAPID' => [
                        'subject' => $vapidSubject,
                        'publicKey' => $vapidPublic,
                        'privateKey' => $vapidPrivate,
                    ],
                ],
                ['TTL' => 86400, 'urgency' => 'normal'],
                $httpClient,
                $this->requestFactory ?? $factory,
                $this->streamFactory ?? $factory,
                null,
                Log::channel(),
            );
        } catch (\Throwable $e) {
            Log::error('Push notification configuration is invalid.', [
                'exception' => $e::class,
            ]);

            return ['success' => false, 'sent' => 0];
        }

        $sent = 0;
        $failed = 0;
        foreach ($subscriptions as $sub) {
            if (! $this->isSafeEndpoint($sub->endpoint)) {
                Log::warning('Push notification skipped for an unsafe endpoint.', [
                    'subscription_id' => $sub->id,
                ]);
                continue;
            }

            try {
                $subscription = Subscription::create([
                    'endpoint' => $sub->endpoint,
                    'keys' => [
                        'p256dh' => $this->normalizeKey($sub->p256dh_key),
                        'auth' => $this->normalizeKey($sub->auth_key),
                    ],
                    'contentEncoding' => 'aes128gcm',
                ]);
                $payload = json_encode([
                    'title' => $title,
                    'body' => $body,
                    'icon' => '/logo/localpro-mark.png',
                    'badge' => '/logo/localpro-mark.png',
                    'url' => '/notifications',
                ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);
                $report = $webPush->sendOneNotification($subscription, $payload);

                if ($report->isSuccess()) {
                    $sent++;
                } elseif ($report->isSubscriptionExpired()) {
                    $sub->delete();
                } else {
                    $failed++;
                    Log::warning('Push provider rejected a notification.', [
                        'subscription_id' => $sub->id,
                        'status' => $report->getResponse()?->getStatusCode(),
                    ]);
                }
            } catch (\Throwable $e) {
                $failed++;
                Log::error('Push notification delivery failed.', [
                    'subscription_id' => $sub->id,
                    'exception' => $e::class,
                ]);
            }
        }

        return ['success' => $failed === 0, 'sent' => $sent];
    }

    public function isSafeEndpoint(string $endpoint): bool
    {
        $parts = parse_url($endpoint);
        if (! is_array($parts)
            || strtolower($parts['scheme'] ?? '') !== 'https'
            || empty($parts['host'])
            || isset($parts['user'])
            || isset($parts['pass'])
            || (isset($parts['port']) && $parts['port'] !== 443)) {
            return false;
        }

        $host = strtolower(rtrim($parts['host'], '.'));
        if ($host === 'localhost' || preg_match('/\.(?:localhost|local|internal|test|invalid|lan|home)$/', $host)) {
            return false;
        }

        $ipFlags = FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE;
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return filter_var($host, FILTER_VALIDATE_IP, $ipFlags) !== false;
        }

        try {
            $records = dns_get_record($host, DNS_A | DNS_AAAA);
        } catch (\Throwable) {
            return false;
        }
        if (! is_array($records) || $records === []) {
            return false;
        }

        $hasAddress = false;
        foreach ($records as $record) {
            $address = $record['ip'] ?? $record['ipv6'] ?? null;
            if ($address === null) {
                continue;
            }

            $hasAddress = true;
            if (filter_var($address, FILTER_VALIDATE_IP, $ipFlags) === false) {
                return false;
            }
        }

        return $hasAddress;
    }

    private function normalizeKey(string $key): string
    {
        return rtrim(strtr($key, '+/', '-_'), '=');
    }
}
