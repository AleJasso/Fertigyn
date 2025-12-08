@extends('layouts.app')
@section('title','Roles del sistema')

@section('content')
<div class="container py-4">
  <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
    <div class="mb-2 mb-md-0">
      <h1 class="h4 fw-semibold mb-1">Roles del sistema</h1>
      <p class="text-muted mb-0">
        Administración de roles utilizados en FertiGyn (por ejemplo: ADMIN, ENFERMERIA).
      </p>
    </div>

    <div class="d-flex align-items-center gap-2">
            {{-- 🔙 Botón de volver a usuarios --}}
      <a href="{{ route('admin.users.index') }}"
         class="btn btn-outline-primary d-flex align-items-center gap-1"
         style="border-radius:999px;">
        <span class="fs-5">←</span>
        <span>Volver a usuarios</span>
      </a>

      <a href="{{ route('admin.roles.create') }}"
         class="btn btn-fg d-flex align-items-center gap-2">
        <span class="fs-5">＋</span>
        <span>Nuevo rol</span>
      </a>
    </div>
  </div>

  @if (session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif

  @if ($errors->has('error'))
    <div class="alert alert-danger">{{ $errors->first('error') }}</div>
  @endif

  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
      <table class="table mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th>Nombre</th>
            <th style="width: 160px;">Usuarios asignados</th>
            <th style="width: 210px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($roles as $role)
            <tr>
              <td class="fw-semibold">
                {{ $role->name }}
              </td>
              <td>
                {{ $usersCountByRole[$role->id] ?? 0 }}
              </td>
              <td>
                <div class="d-flex gap-2">
                  <a href="{{ route('admin.roles.edit', $role) }}"
                     class="btn btn-outline-warning btn-sm d-flex align-items-center gap-1">
                    <span>✏️</span><span>Editar</span>
                  </a>

                  <form method="POST"
                        action="{{ route('admin.roles.destroy', $role) }}"
                        onsubmit="return confirm('¿Seguro que deseas eliminar este rol?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1">
                      <span>🗑️</span><span>Eliminar</span>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="3" class="text-center text-muted py-4">
                No hay roles registrados.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
