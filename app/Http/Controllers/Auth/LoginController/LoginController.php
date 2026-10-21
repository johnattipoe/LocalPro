<?php

namespace App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\Controller\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate(['email' => ['required', 'email'], 'password' => ['required', 'string']]);
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Those details didn’t match an account.'])->onlyInput('email');
        }

        if (! $request->user()->is_active) {
            Auth::logout();
            return back()->withErrors(['email' => 'This account is currently suspended.'])->onlyInput('email');
        }

        $request->session()->regenerate();
        $destination = match ($request->user()->role) {
            'admin' => route('admin.dashboard'),
            'provider' => route('provider.dashboard'),
            'customer' => route('services.index'),
            default => route('home'),
        };

        return redirect()->intended($destination)->with('auth_success', 'Welcome back, ' . $request->user()->name . '.');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
