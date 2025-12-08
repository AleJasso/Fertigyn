@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="h4 mb-1">Usuarios del sistema</h1>
      <p class="text-muted mb-0">
        Administración de cuentas de <strong>Administrador</strong> y <strong>Enfermería</strong>.
      </p>
    </div>

    <div class="d-flex align-items-center gap-2">
      {{-- 🔙 Botón de volver a pacientes --}}
      <a href="{{ route('admin.patients.index') }}"
         class="btn btn-outline-primary d-flex align-items-center gap-1"
         style="border-radius: 999px;">
        <span class="fs-5">←</span>
        <span>Volver a pacientes</span>
      </a>

      <a href="{{ route('admin.roles.index') }}"
        class="btn btn-outline-secondary d-flex align-items-center gap-1"
        style="border-radius:999px;">
        <span>🛡️</span>
        <span>Roles</span>
      </a>

      {{-- ➕ Botón de nuevo usuario --}}
      <a href="{{ route('admin.users.create') }}"
         class="btn btn-fg d-flex align-items-center gap-2"
         style="border-radius: 999px;">
        <span class="fs-5">👤</span>
        <span>Nuevo usuario</span>
      </a>
    </div>
  </div>

  @if (session('ok'))
    <div class="alert alert-success">
      {{ session('ok') }}
    </div>
  @endif

  @if ($errors->has('user'))
    <div class="alert alert-danger">
      {{ $errors->first('user') }}
    </div>
  @endif

  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table mb-0 align-middle">
          <thead class="table-light">
          <tr>
            <th>NOMBRE</th>
            <th>CORREO</th>
            <th>ROL</th>
            <th>ESTADO</th>
            <th class="text-end">ACCIONES</th>
          </tr>
          </thead>
          <tbody>
          @forelse ($users as $user)
            <tr>
              <td>{{ $user->name }}</td>
              <td>{{ $user->email }}</td>
              <td>
                <span class="badge rounded-pill
                  {{ ($user->role->name ?? '') === 'ADMIN'
                        ? 'bg-danger-subtle text-danger'
                        : 'bg-info-subtle text-info' }}">
                  {{ $user->role->name ?? 'Sin rol' }}
                </span>
              </td>
              <td>
                @if ($user->email_verified_at)
                  <span class="badge bg-success-subtle text-success">Activado</span>
                @else
                  <span class="badge bg-warning-subtle text-warning">Pendiente de activación</span>
                @endif
              </td>
              <td class="text-end">
                <a href="{{ route('admin.users.edit', $user) }}"
                   class="btn btn-sm btn-outline-secondary me-1">
                  ✏️ Editar
                </a>

                <form action="{{ route('admin.users.destroy', $user) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?');">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">
                    🗑 Eliminar
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center text-muted py-4">
                No hay usuarios registrados aún.
              </td>
            </tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </div>

    @if ($users->hasPages())
      <div class="card-footer border-0">
        {{ $users->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
