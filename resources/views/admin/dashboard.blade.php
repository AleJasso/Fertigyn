@extends('layouts.app')

@section('title', 'Panel del Doctor')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Panel del Doctor (ADMIN)</h1>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button class="btn btn-outline-danger btn-sm">Cerrar sesión</button>
    </form>
  </div>

  <div class="row g-3">
    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">Pacientes</h5>
          <p class="card-text">Crear / editar pacientes y ver expedientes clínicos.</p>
          <a href="{{ route('admin.patients.index') }}" class="btn btn-primary btn-sm">Abrir</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">Enfermería</h5>
          <p class="card-text">Registrar signos vitales y archivos adjuntos por consulta.</p>
          <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
              Crear usuario
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
