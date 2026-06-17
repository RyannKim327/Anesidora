<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class Throttle
{
    public function handle($request, Closure $next)
    {
        $key = 'throttle:'.$request->ip();

        $maxAttempts = 60;
        $decaySeconds = 60;

        $attempts = Cache::get($key, 0);

        if ($attempts >= $maxAttempts) {
            return response()->json([
                'message' => 'Too many requests',
            ], 429);
        }

        Cache::put($key, $attempts + 1, $decaySeconds);

        return $next($request);
    }
}
