<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckComingSoon
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (env('APP_COMING_SOON', false)) {
            // Exclude the coming-soon route itself and any API routes (like webhooks)
            if (!$request->is('coming-soon') && !$request->is('api/*')) {
                return redirect()->route('coming.soon');
            }
        }

        return $next($request);
    }
}
