<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;

class AuthMiddleware
{
    public function handle($request, Closure $next)
    {
        $userKey = $request->header('Authorization');

            if ($userKey !== 'Basic ' . base64_encode(config('app.api_key'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
