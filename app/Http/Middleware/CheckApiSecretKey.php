<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class CheckApiSecretKey
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-SECRET'); // fakeKey = 477de5e2aa64f92faa2d971500b03b09

        if ($apiKey !== config('api.secret_key')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $next($request);
    }
}

