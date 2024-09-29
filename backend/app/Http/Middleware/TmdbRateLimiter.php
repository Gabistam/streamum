<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class TmdbRateLimiter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $executed = RateLimiter::attempt(
            'tmdb-api',
            40, // Maximum attempts
            function() {
                // This closure is executed if the rate limit is not exceeded
            },
            60 // Time window in seconds
        );

        if (! $executed) {
            return response()->json(['error' => 'Too Many Requests'], 429);
        }

        return $next($request);
    }
}