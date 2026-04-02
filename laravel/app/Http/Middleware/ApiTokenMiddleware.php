<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ApiToken;

class ApiTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->query('key'); // получаем токен из параметра key

        if (!$token || !ApiToken::where('token', $token)->exists()) {
            return response()->json([
                'error' => 'Invalid or missing API token'
            ], 401); // 401 Unauthorized
        }

        return $next($request);
    }
}
