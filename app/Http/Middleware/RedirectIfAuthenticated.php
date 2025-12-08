<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                $role = $user?->role?->name;

                return match ($role) {
                    'ADMIN'      => to_route('admin.dashboard'),
                    'ENFERMERIA' => to_route('nurse.dashboard'),
                    default      => to_route('home'),
                };
            }
        }

        return $next($request);
    }
}
