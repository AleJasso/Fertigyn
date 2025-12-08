<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LockoutAndActivity
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();

            // si está bloqueado
            if ($user->locked_until && now()->lessThan($user->locked_until)) {
                auth()->logout();
                return redirect()->route('login')->withErrors([
                    'email' => 'Cuenta bloqueada hasta: '.$user->locked_until->format('H:i d/m/Y'),
                ]);
            }

            // sliding expiration (última actividad)
            $user->last_activity_at = now();
            $user->saveQuietly();
        }
        return $next($request);
    }
}
