@extends('layouts.app')
@section('title','Editar rol')

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
            <h1 class="h5 fw-semibold mt-3 mb-1">Editar rol</h1>
            <p class="text-muted mb-0">
              Modifica el nombre del rol seleccionado.
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

          <form method="POST" action="{{ route('admin.roles.update', $role) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
              <label class="form-label">Nombre del rol</label>
              <input type="text"
                     name="name"
                     class="form-control"
                     value="{{ old('name', $role->name) }}"
                     required>
            </div>

            <div class="d-grid mt-4">
              <button class="btn btn-fg btn-lg">
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
