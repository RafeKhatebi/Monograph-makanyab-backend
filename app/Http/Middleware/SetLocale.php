<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supported = array_keys(config('locales', ['en' => []]));
        $locale = $request->session()->get('locale', config('app.locale', 'en'));

        if (! in_array($locale, $supported, true)) {
            $locale = config('app.fallback_locale', 'en');
            $request->session()->put('locale', $locale);
        }

        app()->setLocale($locale);

        return $next($request);
    }
}