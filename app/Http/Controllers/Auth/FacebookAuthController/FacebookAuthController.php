<?php

namespace App\Http\Controllers\Auth\FacebookAuthController;

use App\Http\Controllers\Controller\Controller;
use App\Models\ProviderProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class FacebookAuthController extends Controller
{
    public function redirect(Request $request)
    {
        $role = $request->query('role', 'customer');
        $role = in_array($role, ['customer', 'provider'], true) ? $role : 'customer';
        $request->session()->put('facebook_registration_role', $role);

        if (! config('services.facebook.client_id') || ! config('services.facebook.client_secret')) {
            return back()->withErrors(['facebook' => 'Facebook sign-in is not configured yet. You can sign in with your email and password.']);
        }

        return Socialite::driver('facebook')->redirect();
    }

    public function callback(Request $request)
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
        } catch (\Throwable $exception) {
            Log::warning('Facebook sign-in could not be completed.', ['exception' => get_class($exception)]);
            $request->session()->forget('facebook_registration_role');

            return redirect()->route('login')->withErrors([
                'facebook' => 'Facebook could not sign you in. Please try again or use your email and password.',
            ]);
        }

        $email = strtolower(trim((string) $facebookUser->getEmail()));
        $facebookId = (string) $facebookUser->getId();

        if (! $facebookId) {
            $request->session()->forget('facebook_registration_role');

            return redirect()->route('login')->withErrors([
                'facebook' => 'Facebook did not provide account details. Please use your email and password.',
            ]);
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = 'fb_'.$facebookId.'@facebook.localpro.example';
        }

        $role = $request->session()->pull('facebook_registration_role', 'customer');
        $role = in_array($role, ['customer', 'provider'], true) ? $role : 'customer';

        $user = DB::transaction(function () use ($facebookUser, $facebookId, $email, $role) {
            $hasFacebookIdCol = Schema::hasColumn('users', 'facebook_id');

            $user = null;
            if ($hasFacebookIdCol) {
                $user = User::where('facebook_id', $facebookId)->lockForUpdate()->first();
            }

            if (! $user) {
                $user = User::whereRaw('LOWER(email) = ?', [$email])->lockForUpdate()->first();
            }

            if (! $user) {
                $user = User::create([
                    'name' => Str::limit(trim((string) $facebookUser->getName()) ?: Str::before($email, '@'), 120, ''),
                    'email' => $email,
                    'city' => 'Ho',
                    'role' => $role,
                    'password' => Hash::make(Str::random(64)),
                ]);

                $data = ['email_verified_at' => now()];
                if ($hasFacebookIdCol) {
                    $data['facebook_id'] = $facebookId;
                }
                $user->forceFill($data)->save();

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

            if ($hasFacebookIdCol) {
                $user->facebook_id = $facebookId;
            }
            $user->email_verified_at ??= now();
            $user->save();

            return $user;
        });

        if (! $user || ! $user->is_active) {
            return redirect()->route('login')->withErrors([
                'facebook' => 'This account cannot sign in with that Facebook account. Please use your email and password or contact support.',
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'provider') {
            return redirect()->route('provider.dashboard')->with('auth_success', 'You signed in with Facebook. Complete your professional profile and verification to appear in search.');
        }

        return redirect()->route('services.index')->with('auth_success', 'You signed in with Facebook. Welcome to LocalPro.');
    }
}
