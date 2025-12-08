@extends('layouts.app')

@section('title', 'Restablecer contraseña')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="text-center mb-4">
        <img src="{{ asset('assets/img/fertigyn-logo.png') }}" alt="FertiGyn" style="height:56px;">
        <h1 class="h4 fw-semibold mt-3 mb-1">Restablecer contraseña</h1>
        <p class="text-muted mb-0">
          Define una nueva contraseña para tu cuenta.
        </p>
      </div>

      <div class="card border-0 shadow-lg rounded-4">
        <div class="card-body p-4">

          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $e)
                  <li>{{ $e }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="mb-3">
              <label class="form-label">Nueva contraseña</label>
              <input type="password"
                     name="password"
                     class="form-control"
                     required>
            </div>

            <div class="mb-3">
              <label class="form-label">Confirmar contraseña</label>
              <input type="password"
                     name="password_confirmation"
                     class="form-control"
                     required>
            </div>

            <div class="mb-4">
              <small class="text-muted d-block mb-1">
                La contraseña debe cumplir con:
              </small>
              <ul class="small text-muted mb-0">
                <li>Mínimo 8 caracteres.</li>
                <li>Al menos una mayúscula y una minúscula.</li>
                <li>Al menos un carácter especial (no letra ni número).</li>
                <li>Sin números consecutivos (123, 456, etc.).</li>
                <li>Sin letras consecutivas (abc, def, etc.).</li>
              </ul>
            </div>

            <div class="d-grid mb-3">
              <button class="btn btn-fg btn-lg">
                Guardar nueva contraseña
              </button>
            </div>

            <div class="text-center">
              <a href="{{ route('login') }}" class="small">
                Volver al inicio de sesión
              </a>
            </div>

          </form>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
