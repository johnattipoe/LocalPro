<?php

namespace Tests\Feature;

use App\Models\PushSubscription;
use App\Models\User;
use App\Services\NotificationService\PushService;
use GuzzleHttp\Psr7\HttpFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\VAPID;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Tests\TestCase;

class PushSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_private_or_non_https_endpoints_are_rejected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('notifications.push.subscribe'), [
                'endpoint' => 'http://127.0.0.1:8080/push',
                'p256dh_key' => 'public-key',
                'auth_key' => 'auth-key',
            ])
            ->assertSessionHasErrors('endpoint');

        $this->assertDatabaseCount('push_subscriptions', 0);
    }

    public function test_push_delivery_skips_unsafe_subscription_endpoints(): void
    {
        $user = User::factory()->create();
        PushSubscription::create([
            'user_id' => $user->id,
            'endpoint' => 'http://127.0.0.1:8080/push',
            'p256dh_key' => 'public-key',
            'auth_key' => 'auth-key',
        ]);

        $vapid = VAPID::createVapidKeys();
        Config::set('services.push.vapid_public', $vapid['publicKey']);
        Config::set('services.push.vapid_private', $vapid['privateKey']);
        Config::set('services.push.vapid_subject', 'mailto:test@example.com');
        Log::spy();

        $result = (new PushService())->send('Test', 'Test message', $user->id);

        $this->assertSame(['success' => true, 'sent' => 0], $result);
        Log::shouldHaveReceived('warning')->once();
    }

    public function test_push_delivery_sends_an_encrypted_vapid_request(): void
    {
        $user = User::factory()->create();
        $subscriberKeys = $this->createSubscriberKeys();
        PushSubscription::create([
            'user_id' => $user->id,
            'endpoint' => 'https://8.8.8.8/wpush/v2/subscriber',
            'p256dh_key' => $subscriberKeys['public'],
            'auth_key' => $subscriberKeys['auth'],
        ]);
        $vapid = VAPID::createVapidKeys();
        Config::set('services.push.vapid_public', $vapid['publicKey']);
        Config::set('services.push.vapid_private', $vapid['privateKey']);
        Config::set('services.push.vapid_subject', 'mailto:test@example.com');

        $factory = new HttpFactory;
        $client = \Mockery::mock(ClientInterface::class);
        $client->shouldReceive('sendRequest')
            ->once()
            ->withArgs(function (RequestInterface $request): bool {
                $this->assertSame('POST', $request->getMethod());
                $this->assertSame('aes128gcm', $request->getHeaderLine('Content-Encoding'));
                $this->assertStringStartsWith('vapid t=', $request->getHeaderLine('Authorization'));
                $this->assertSame('86400', $request->getHeaderLine('TTL'));
                $this->assertStringNotContainsString('"title"', (string) $request->getBody());

                return true;
            })
            ->andReturn($factory->createResponse(201));

        $result = (new PushService($client, $factory, $factory))->send('Test', 'Test message', $user->id);

        $this->assertSame(['success' => true, 'sent' => 1], $result);
    }

    public function test_subscriptions_require_valid_web_push_key_lengths(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson(route('notifications.push.subscribe'), [
                'endpoint' => 'https://8.8.8.8/wpush/v2/subscriber',
                'p256dh_key' => 'invalid',
                'auth_key' => 'invalid',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['p256dh_key', 'auth_key']);

        $this->assertDatabaseCount('push_subscriptions', 0);
    }

    private function createSubscriberKeys(): array
    {
        $key = openssl_pkey_new([
            'curve_name' => 'prime256v1',
            'private_key_type' => OPENSSL_KEYTYPE_EC,
        ]);
        $details = openssl_pkey_get_details($key);
        $public = "\x04".str_pad($details['ec']['x'], 32, "\0", STR_PAD_LEFT).str_pad($details['ec']['y'], 32, "\0", STR_PAD_LEFT);
        $encode = static fn (string $value): string => rtrim(strtr(base64_encode($value), '+/', '-_'), '=');

        return [
            'public' => $encode($public),
            'auth' => $encode(random_bytes(16)),
        ];
    }
}
