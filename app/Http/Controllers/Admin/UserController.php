<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Mail\CredencialesEnviadas;
use App\Models\Role;
use App\Models\User;
use App\Rules\StrongPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * Solo permite acceso a usuarios con rol ADMIN.
     */
    protected function ensureAdmin(): void
    {
        $role = auth()->user()?->role?->name ?? null;

        if ($role !== 'ADMIN') {
            abort(403, 'Solo el administrador puede gestionar usuarios.');
        }
    }

    /* ================== LISTADO (CATÁLOGO) ================== */

    public function index()
    {
        $this->ensureAdmin();

        $users = User::with('role')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /* ================== CREAR ================== */

     public function formCreate()
    {
        $this->ensureAdmin();

        // Traemos todos los roles ordenados por nombre
        $roles = Role::orderBy('name')->get();

        return view('admin.users.create', compact('roles'));
    }

    public function store(RegisterUserRequest $req)
    {
        $this->ensureAdmin();

        // Rol ADMIN o ENFERMERIA
        $role = Role::firstWhere('name', $req->role);
        if (!$role) {
            return back()
                ->withErrors(['role' => 'El rol seleccionado no es válido.'])
                ->withInput();
        }

        $plainPassword = $req->password;

        // Crear usuario
        $user = User::create([
            'name'             => $req->name,
            'email'            => $req->email,
            'password'         => Hash::make($plainPassword),
            'role_id'          => $role->id,
            'failed_attempts'  => 0,
            'locked_until'     => null,
            'last_activity_at' => now(),
        ]);

        // Enviar correo de activación + credenciales
        Mail::to($user->email)->send(
            new CredencialesEnviadas($user, $plainPassword)
        );

        // ⬅️ Dejo exactamente el redirect que tú tenías
        return redirect()
            ->route('admin.patients.index')
            ->with('ok', 'Usuario creado y correo enviado correctamente.');
    }

    /* ================== EDITAR ================== */

    public function edit(User $user)
    {
        $this->ensureAdmin();

        $roles = Role::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'unique:users,email,' . $user->id],
            'role'     => ['required', 'in:ADMIN,ENFERMERIA'],
            'password' => ['nullable', new StrongPassword], // opcional, pero con mismas reglas
        ]);

        $role = Role::firstWhere('name', $data['role']);
        if (!$role) {
            return back()
                ->withErrors(['role' => 'El rol seleccionado no es válido.'])
                ->withInput();
        }

        $user->name    = $data['name'];
        $user->email   = $data['email'];
        $user->role_id = $role->id;

        // Si el admin escribió una nueva contraseña, la actualizamos
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('ok', 'Usuario actualizado correctamente.');
    }

    /* ================== ELIMINAR ================== */

    public function destroy(User $user)
    {
        $this->ensureAdmin();

        // Evitar que el admin se borre a sí mismo por accidente
        if (auth()->id() === $user->id) {
            return back()->withErrors([
                'user' => 'No puedes eliminar tu propia cuenta mientras está en uso.',
            ]);
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('ok', 'Usuario eliminado correctamente.');
    }
}
