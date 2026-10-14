<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsCustomer
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless($request->user() && $request->user()->role === 'customer' && $request->user()->is_active, 403);

        return $next($request);
    }
}
