<?php

namespace App\Http\Middleware\EnsureUserIsProvider;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsProvider
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless($request->user() && $request->user()->role === 'provider' && $request->user()->is_active, 403);
        return $next($request);
    }
}

