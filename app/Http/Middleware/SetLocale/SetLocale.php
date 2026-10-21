<?php

namespace App\Http\Middleware\SetLocale;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supported = array_keys(config('locales.locales', []));
        $locale = $request->user()?->preferred_language
            ?? $request->session()->get('locale', config('app.locale', 'en'));

        if (! in_array($locale, $supported, true)) {
            $locale = 'en';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
