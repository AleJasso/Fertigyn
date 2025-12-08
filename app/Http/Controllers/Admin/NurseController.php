<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Rules\StrongPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class NurseController extends Controller
{

    protected function ensureAdmin(): void
    {
        $role = auth()->user()?->role?->name;

        if ($role !== 'ADMIN') {
            abort(403, 'Solo el administrador puede gestionar usuarios de enfermería.');
        }
    }

    protected function ensureNurse(): void
    {
        $role = auth()->user()?->role?->name;

        if ($role !== 'ENFERMERIA') {
            abort(403, 'Solo usuarios de enfermería pueden acceder a este panel.');
        }
    }

    // Dashboard de enfermería: /enfermeria
    public function index()
    {
        $this->ensureNurse();

        return view('enf.dashboard');
    }

    // Formulario para crear usuario de enfermería (solo ADMIN): /admin/enfermeria/crear
    public function create()
    {
        $this->ensureAdmin();

        return view('admin.nurse-create');
    }

    // Guardar usuario de enfermería (solo ADMIN): POST /admin/enfermeria
    public function store(Request $request)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'name'     => ['required','string','max:100'],
            'email'    => ['required','email','max:150','unique:users,email'],
            'password' => ['required', new StrongPassword],
            'g-recaptcha-response' => ['required','captcha'],
        ]);

        $role = Role::firstWhere('name', 'ENFERMERIA');
        if (!$role) {
            return back()
                ->withErrors(['email' => 'No existe el rol ENFERMERIA.'])
                ->withInput();
        }

        User::create([
            'name'             => $data['name'],
            'email'            => $data['email'],
            'password'         => Hash::make($data['password']),
            'role_id'          => $role->id,
            'last_activity_at' => now(),
            'failed_attempts'  => 0,
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('ok', 'Usuario de enfermería creado correctamente.');
    }
}
