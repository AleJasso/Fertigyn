@extends('layouts.app')
@section('content')
<div class="card shadow auth-card">
  <div class="card-body">
    <h5 class="card-title mb-3">Crear usuario de Enfermería</h5>

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.nurse.store') }}" novalidate>
      @csrf
      <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input name="name" type="text" class="form-control" value="{{ old('name') }}" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Correo</label>
        <input name="email" type="email" class="form-control" value="{{ old('email') }}" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Contraseña</label>
        <input name="password" type="password" class="form-control" required>
        <small class="text-muted">
          Mín. 8, mayúscula, minúscula, especial, sin secuencias 123 / abc.
        </small>
      </div>
      <div class="d-grid">
        <button class="btn btn-primary">Crear</button>
      </div>
    </form>
  </div>
</div>
@endsection
