<?php

namespace App\Http\Middleware;

use Closure;

class APILocalization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $currentLocale = app()->getLocale();

        $incomingLocale = ($request->hasHeader('X-localization')) ? $request->header('X-localization') : 'en';

        if ($currentLocale != $incomingLocale) {
            app()->setLocale($incomingLocale);
        }

        return $next($request);
    }
}
