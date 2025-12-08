@extends('layouts.app')

@section('title', 'Editar usuario')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-6">

      <div class="text-center mb-4">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle shadow-sm"
             style="width:72px; height:72px; background:#ffe4f0;">
          <span style="font-size:32px; color:#d83d71;">👤</span>
        </div>

        <h1 class="h4 fw-semibold mt-3 mb-1">Editar usuario</h1>
        <p class="text-muted mb-0">
          Actualiza los datos de la cuenta seleccionada. Puedes cambiar el rol
          y opcionalmente asignar una nueva contraseña.
        </p>
      </div>

      <div class="card border-0 shadow-lg rounded-4">
        <div class="card-body p-4 p-md-5">

          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $e)
                  <li>{{ $e }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('admin.users.update', $user) }}" novalidate>
            @csrf
            @method('PUT')

            <div class="mb-3">
              <label class="form-label">Nombre completo</label>
              <input name="name"
                     type="text"
                     class="form-control"
                     value="{{ old('name', $user->name) }}"
                     required>
            </div>

            <div class="mb-3">
              <label class="form-label">Correo electrónico</label>
              <input name="email"
                     type="email"
                     class="form-control"
                     value="{{ old('email', $user->email) }}"
                     required>
            </div>

            <div class="mb-3">
              <label class="form-label">Rol</label>
              <select name="role" class="form-select" required>
                <option value="">Seleccione un rol…</option>

                @foreach($roles as $role)
                    <option value="{{ $role->name }}"
                            {{ old('role', $user->role->name ?? '') === $role->name ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
              </select>
            </div>


            <div class="mb-3">
              <label class="form-label">Nueva contraseña (opcional)</label>
              <input name="password"
                     type="password"
                     class="form-control"
                     placeholder="Déjalo vacío para mantener la contraseña actual">
            </div>

            <div class="mb-4">
              <small class="text-muted d-block mb-1">
                La contraseña nueva, si la defines, debe cumplir con:
              </small>
              <ul class="small text-muted mb-0">
                <li>Mínimo 8 caracteres.</li>
                <li>Al menos una mayúscula y una minúscula.</li>
                <li>Al menos un carácter especial (no letra ni número).</li>
                <li>Sin números consecutivos (123, 456, etc.).</li>
                <li>Sin letras consecutivas (abc, def, etc.).</li>
              </ul>
            </div>

            <div class="d-flex justify-content-between">
              <a href="{{ route('admin.users.index') }}" 
              class="btn btn-outline-primary d-inline-flex align-items-center gap-1"
              style="border-radius:999px;">
              <span class="fs-5">←</span>
              <span>Cancelar</span>
              </a>

              <button class="btn btn-fg">
                Guardar cambios
              </button>
            </div>
          </form>

        </div>
      </div>

    </div>
  </div>
</div>
@endsection
