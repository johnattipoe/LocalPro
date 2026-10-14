<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ProviderProfile;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register', ['categories' => ServiceCategory::where('is_active', true)->orderBy('sort_order')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:32'],
            'city' => ['required', 'in:Ho,Hohoe,Keta,Aflao'],
            'role' => ['required', 'in:customer,provider'],
            'profession' => ['required_if:role,provider', 'nullable', 'string', 'max:120'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'], 'email' => $data['email'], 'phone' => $data['phone'] ?? null,
                'city' => $data['city'], 'role' => $data['role'], 'password' => Hash::make($data['password']),
            ]);

            if ($data['role'] === 'provider') {
                ProviderProfile::create([
                    'user_id' => $user->id, 'profession' => $data['profession'], 'city' => $data['city'],
                    'region' => 'Volta Region', 'verification_status' => 'pending',
                ]);
            }

            return $user;
        });

        auth()->login($user);

        $destination = $user->role === 'provider' ? route('provider.dashboard') : route('customer.dashboard');
        return redirect()->to($destination)->with('status', $user->role === 'provider'
            ? 'Your account is ready. Complete verification to appear in local searches.'
            : 'Your LocalPro account is ready.');
    }
}
