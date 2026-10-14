<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ProviderProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect(Request $request)
    {
        $role = $request->query('role', 'customer');
        $role = in_array($role, ['customer', 'provider'], true) ? $role : 'customer';
        $request->session()->put('google_registration_role', $role);

        if (! config('services.google.client_id') || ! config('services.google.client_secret')) {
            return back()->withErrors(['google' => 'Google sign-in is not configured yet. You can sign in with your email and password.']);
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $exception) {
            Log::warning('Google sign-in could not be completed.', ['exception' => get_class($exception)]);
            $request->session()->forget('google_registration_role');

            return redirect()->route('login')->withErrors([
                'google' => 'Google could not sign you in. Please try again or use your email and password.',
            ]);
        }

        $raw = $googleUser->getRaw();
        $email = strtolower(trim((string) $googleUser->getEmail()));
        $googleId = (string) $googleUser->getId();
        $emailVerified = filter_var(
            $raw['email_verified'] ?? $raw['verified_email'] ?? false,
            FILTER_VALIDATE_BOOLEAN
        );

        if (! $googleId || ! filter_var($email, FILTER_VALIDATE_EMAIL) || ! $emailVerified) {
            $request->session()->forget('google_registration_role');

            return redirect()->route('login')->withErrors([
                'google' => 'Google did not provide a verified email address. Please use your email and password.',
            ]);
        }

        $role = $request->session()->pull('google_registration_role', 'customer');
        $role = in_array($role, ['customer', 'provider'], true) ? $role : 'customer';

        $user = DB::transaction(function () use ($googleUser, $googleId, $email, $role) {
            $user = User::where('google_id', $googleId)->lockForUpdate()->first();

            if (! $user) {
                $user = User::whereRaw('LOWER(email) = ?', [$email])->lockForUpdate()->first();
            }

            if ($user && $user->google_id && ! hash_equals((string) $user->google_id, $googleId)) {
                return null;
            }

            if (! $user) {
                $user = User::create([
                    'name' => Str::limit(trim((string) $googleUser->getName()) ?: Str::before($email, '@'), 120, ''),
                    'email' => $email,
                    'city' => 'Ho',
                    'role' => $role,
                    'password' => Hash::make(Str::random(64)),
                ]);
                $user->forceFill(['google_id' => $googleId, 'email_verified_at' => now()])->save();

                if ($role === 'provider') {
                    ProviderProfile::create([
                        'user_id' => $user->id,
                        'profession' => 'Other',
                        'city' => 'Ho',
                        'region' => 'Volta Region',
                        'verification_status' => 'pending',
                    ]);
                }

                return $user;
            }

            $user->google_id = $googleId;
            $user->email_verified_at ??= now();
            $user->save();

            return $user;
        });

        if (! $user || ! $user->is_active) {
            return redirect()->route('login')->withErrors([
                'google' => 'This account cannot sign in with that Google account. Please use your email and password or contact support.',
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'provider') {
            return redirect()->route('provider.dashboard')->with('status', 'You signed in with Google. Complete your professional profile and verification to appear in search.');
        }

        return redirect()->route('customer.dashboard')->with('status', 'You signed in with Google. Welcome to LocalPro.');
    }
}
