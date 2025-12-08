<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateLastActivity
{
    // Cada cuánto segundos actualizamos last_activity_at para evitar escribir en cada request
    private int $writeIntervalSeconds = 60;

    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = $request->user();

            // Minutos máximos de inactividad (usa SESSION_LIFETIME del .env)
            $maxIdleMinutes = (int) config('session.lifetime', 15);
            $idleLimit      = now()->subMinutes($maxIdleMinutes);

            // Si la última actividad es anterior al límite → caducó la sesión
            if ($user->last_activity_at && $user->last_activity_at < $idleLimit) {
                Auth::logout();
                // invalida y regenera para máxima seguridad
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'email' => "Sesión cerrada por inactividad ({$maxIdleMinutes} min).",
                ]);
            }

            // Evita escribir en cada request: solo si pasó writeIntervalSeconds
            $shouldWrite = ! $user->last_activity_at
                || now()->diffInSeconds($user->last_activity_at) >= $this->writeIntervalSeconds;

            if ($shouldWrite) {
                // saveQuietly para no disparar eventos de modelo
                $user->forceFill(['last_activity_at' => now()])->saveQuietly();
            }
        }

        return $next($request);
    }
}
