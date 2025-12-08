<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1) Validación básica + reCAPTCHA + aviso de privacidad
        $request->validate([
            'email'    => ['required','email'],
            'password' => ['required','string'],
            'g-recaptcha-response' => ['required','captcha'],
            'accept_privacy'       => 'accepted',
        ], [
            'g-recaptcha-response.required' => 'Confirma que no eres un robot.',
            'g-recaptcha-response.captcha'  => 'Captcha inválido.',
        ]);

        // 2) Bloqueo por intentos (DB + RateLimiter IP/email)
        $email = strtolower($request->input('email'));
        $ip    = $request->ip();
        $key   = "login:{$email}|{$ip}";

        $user = User::where('email', $email)->first();

        // Si el usuario existe y está bloqueado por tiempo
        if ($user && $user->locked_until && now()->lt($user->locked_until)) {
            $seconds = now()->diffInSeconds($user->locked_until);
            $mmss    = gmdate('i\m s\s', $seconds);
            throw ValidationException::withMessages([
                'email' => "Cuenta bloqueada por intentos fallidos. Intenta en {$mmss}.",
            ]);
        }

        // Límite por IP/email (defensa adicional)
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $mmss    = gmdate('i\m s\s', $seconds);
            throw ValidationException::withMessages([
                'email' => "Cuenta/IP bloqueada temporalmente. Intenta en {$mmss}.",
            ]);
        }

        // 3) Intento de login
        $credentials = [
            'email'    => $email,
            'password' => $request->input('password'),
        ];

        if (!Auth::attempt($credentials, true)) {
            RateLimiter::hit($key, 60); // 60s por intento

            // Persistimos intentos fallidos en DB y bloqueamos a los 3
            if ($user) {
                $user->failed_attempts = ($user->failed_attempts ?? 0) + 1;

                if ($user->failed_attempts >= 3) {
                    $minutes = (int) env('LOGIN_LOCK_MINUTES', 15); // .env configurable
                    $user->locked_until    = now()->addMinutes($minutes);
                    $user->failed_attempts = 0; // resetea el contador al activar bloqueo
                }

                $user->save();
            }

            throw ValidationException::withMessages([
                'email' => 'Credenciales inválidas.',
            ]);
        }

        // ================================
        // 3.1 Verificar que la cuenta esté ACTIVADA
        // ================================
        $user = Auth::user(); // usuario autenticado

        if (is_null($user->email_verified_at)) {
            // No está activado → cerrar sesión y avisar
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'Tu cuenta aún no ha sido activada. Revisa el enlace que enviamos a tu correo.',
            ]);
        }

        // 4) Éxito → limpiamos bloqueos y marcamos actividad
        RateLimiter::clear($key);

        $user->failed_attempts  = 0;
        $user->locked_until     = null;
        $user->last_activity_at = now();
        if (is_null($user->privacy_accepted_at)) {
            $user->privacy_accepted_at = now();
        }
        $user->save();

        // Fijamos nueva sesión
        $request->session()->regenerate();

        // 5) Redirección por rol
        $roleName = auth()->user()?->role?->name ?? '';

        return $roleName === 'ADMIN'
            ? to_route('admin.dashboard')   // Panel completo
            : to_route('nurse.dashboard');  // Panel de solo lectura

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return to_route('home');
    }
}
