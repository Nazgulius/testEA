<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TokenAuthMiddleware
{
   public function handle(Request $request, Closure $next)
    {
        if (Session::get('authenticated')) {
            return $next($request);
        }

        return redirect('/login')->withErrors(['token' => 'Требуется авторизация']);
    }
}
