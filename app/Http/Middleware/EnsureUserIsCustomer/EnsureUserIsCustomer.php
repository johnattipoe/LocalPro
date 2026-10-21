<?php

namespace App\Http\Middleware\EnsureUserIsCustomer;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsCustomer
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        abort_unless($user && $user->is_active, 403);

        if ($user->role !== 'customer') {
            $destination = match ($user->role) {
                'admin' => 'admin.dashboard',
                'provider' => 'provider.dashboard',
                default => 'home',
            };

            return redirect()->route($destination)->with(
                'status',
                'Service requests can only be created from a customer account. Please sign in with a customer account to continue.'
            );
        }

        return $next($request);
    }
}
