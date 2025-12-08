<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /** Restringe el acceso solo al ADMIN. */
    protected function ensureAdmin(): void
    {
        $role = auth()->user()?->role?->name ?? null;

        if ($role !== 'ADMIN') {
            abort(403, 'Solo el administrador puede gestionar roles.');
        }
    }

    /** GET /admin/roles */
    public function index()
    {
        $this->ensureAdmin();

        $roles = Role::orderBy('name')->get();

        // Conteo de usuarios por rol (sin depender de relaciones en el modelo)
        $usersCountByRole = User::selectRaw('role_id, COUNT(*) as total')
            ->groupBy('role_id')
            ->pluck('total', 'role_id');

        return view('admin.roles.index', compact('roles', 'usersCountByRole'));
    }

    /** GET /admin/roles/crear */
    public function create()
    {
        $this->ensureAdmin();

        return view('admin.roles.create');
    }

    /** POST /admin/roles */
    public function store(Request $request)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:roles,name'],
        ]);

        $role = new Role();
        $role->name = strtoupper($data['name']); // ADMIN, ENFERMERIA, etc.
        $role->save();

        return redirect()
            ->route('admin.roles.index')
            ->with('ok', 'Rol creado correctamente.');
    }

    /** GET /admin/roles/{role}/editar */
    public function edit(Role $role)
    {
        $this->ensureAdmin();

        return view('admin.roles.edit', compact('role'));
    }

    /** PUT /admin/roles/{role} */
    public function update(Request $request, Role $role)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:50', "unique:roles,name,{$role->id}"],
        ]);

        $role->name = strtoupper($data['name']);
        $role->save();

        return redirect()
            ->route('admin.roles.index')
            ->with('ok', 'Rol actualizado correctamente.');
    }

    /** DELETE /admin/roles/{role} */
    public function destroy(Role $role)
    {
        $this->ensureAdmin();

        // Evitar eliminar rol si hay usuarios que lo usan
        $hasUsers = User::where('role_id', $role->id)->exists();
        if ($hasUsers) {
            return back()->withErrors([
                'error' => 'No puedes eliminar un rol que tiene usuarios asignados.',
            ]);
        }

        $role->delete();

        return back()->with('ok', 'Rol eliminado correctamente.');
    }
}
