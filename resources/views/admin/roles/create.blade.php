@extends('layouts.app')
@section('title','Crear rol')

@section('content')
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="card border-0 shadow-lg rounded-4">
        <div class="card-body p-4 p-md-5">

          <div class="mb-3 d-flex justify-content-between">
            <a href="{{ route('admin.roles.index') }}"
               class="btn btn-outline-secondary d-inline-flex align-items-center gap-1"
               style="border-radius:999px;">
              <span class="fs-5">←</span>
              <span>Volver a roles</span>
            </a>
          </div>

          <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle shadow-sm"
                 style="width:72px; height:72px; background:#e7f2ff;">
              <span style="font-size:32px; color:#0d6efd;">🛡️</span>
            </div>
            <h1 class="h5 fw-semibold mt-3 mb-1">Crear rol</h1>
            <p class="text-muted mb-0">
              Define un nuevo rol que podrás asignar a los usuarios del sistema.
            </p>
          </div>

          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $e)
                  <li>{{ $e }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('admin.roles.store') }}">
            @csrf

            <div class="mb-3">
              <label class="form-label">Nombre del rol</label>
              <input type="text"
                     name="name"
                     class="form-control"
                     value="{{ old('name') }}"
                     placeholder="Ej. ADMIN, ENFERMERIA"
                     required>
              <small class="text-muted">
                Se recomienda usar mayúsculas sin espacios (ejemplo: ADMIN, ENFERMERIA).
              </small>
            </div>

            <div class="d-grid mt-4">
              <button class="btn btn-fg btn-lg">
                Guardar rol
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
