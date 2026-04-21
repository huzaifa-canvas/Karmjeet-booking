<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyWpApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-Key');
        $validKey = env('WP_API_KEY');

        if (!$validKey) {
            // If no API key is configured in .env, allow requests for backward compatibility or dev
            return $next($request);
        }

        if ($apiKey !== $validKey) {
            return response()->json(['success' => false, 'message' => 'Unauthorized: Invalid API Key'], 401);
        }

        return $next($request);
    }
}
