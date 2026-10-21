<?php

namespace App\Http\Controllers\Auth\PasswordResetController;

use App\Http\Controllers\Controller\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function requestForm()
    {
        return view('auth.forgot-password.forgot-password');
    }

    public function sendLink(Request $request)
    {
        $request->validate(['email' => ['required', 'email', 'max:180']]);
        Password::sendResetLink($request->only('email'));

        // Keep the response the same for known, unknown, and throttled email addresses.
        // That prevents this form from revealing which addresses have LocalPro accounts.
        $confirmation = config('mail.default') === 'log'
            ? 'If an account exists for that email, reset instructions were written to the local application log.'
            : 'If an account exists for that email, a reset link will be sent shortly.';

        return back()->with('status', $confirmation);
    }

    public function resetForm(Request $request, string $token)
    {
        return view('auth.reset-password.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function reset(Request $request)
    {
        $data = $request->validate([
            'token'=>['required'], 'email'=>['required','email'],
            'password'=>['required','string','min:8','confirmed'],
        ]);
        $status = Password::reset($data, function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ])->save();
            event(new PasswordReset($user));
        });

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}
