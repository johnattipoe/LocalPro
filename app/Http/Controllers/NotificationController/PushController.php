<?php

namespace App\Http\Controllers\NotificationController;

use App\Http\Controllers\Controller\Controller;
use App\Models\PushSubscription;
use App\Services\NotificationService\PushService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PushController extends Controller
{
    public function __construct(private PushService $push)
    {
    }

    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'endpoint' => ['required', 'string', 'max:1000'],
            'p256dh_key' => ['required', 'string', 'max:100'],
            'auth_key' => ['required', 'string', 'max:50'],
        ]);

        if (! $this->push->isSafeEndpoint($data['endpoint'])) {
            throw ValidationException::withMessages([
                'endpoint' => 'The push subscription endpoint must be a public HTTPS URL.',
            ]);
        }

        $keyErrors = [];
        if (! $this->hasKeyLength($data['p256dh_key'], 65)) {
            $keyErrors['p256dh_key'] = 'The push subscription public key is invalid.';
        }

        if (! $this->hasKeyLength($data['auth_key'], 16)) {
            $keyErrors['auth_key'] = 'The push subscription authentication key is invalid.';
        }

        if ($keyErrors !== []) {
            throw ValidationException::withMessages($keyErrors);
        }

        PushSubscription::updateOrCreate(
            ['user_id' => $request->user()->id, 'endpoint' => $data['endpoint']],
            [
                'p256dh_key' => $this->normalizeKey($data['p256dh_key']),
                'auth_key' => $this->normalizeKey($data['auth_key']),
            ],
        );

        return response()->json(['success' => true]);
    }

    public function unsubscribe(Request $request)
    {
        $data = $request->validate([
            'endpoint' => ['required', 'string', 'max:1000'],
        ]);

        PushSubscription::where('user_id', $request->user()->id)
            ->where('endpoint', $data['endpoint'])
            ->delete();

        return response()->json(['success' => true]);
    }

    private function hasKeyLength(string $key, int $length): bool
    {
        if (! preg_match('~\A[A-Za-z0-9+/_-]+={0,2}\z~', $key)) {
            return false;
        }

        $base64 = strtr(rtrim($key, '='), '-_', '+/');
        $decoded = base64_decode($base64.str_repeat('=', (4 - strlen($base64) % 4) % 4), true);

        return is_string($decoded) && strlen($decoded) === $length;
    }

    private function normalizeKey(string $key): string
    {
        return rtrim(strtr($key, '+/', '-_'), '=');
    }
}
