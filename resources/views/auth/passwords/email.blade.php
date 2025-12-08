@extends('layouts.app')

@section('title', 'Recuperar contraseña')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="text-center mb-4">
        <img src="{{ asset('assets/img/fertigyn-logo.png') }}" alt="FertiGyn" style="height:56px;">
        <h1 class="h4 fw-semibold mt-3 mb-1">Recuperar contraseña</h1>
        <p class="text-muted mb-0">
          Escribe el correo con el que estás registrado y te enviaremos un enlace
          para restablecer tu contraseña.
        </p>
      </div>

      <div class="card border-0 shadow-lg rounded-4">
        <div class="card-body p-4">

          @if (session('status'))
            <div class="alert alert-success">
              {{ session('status') }}
            </div>
          @endif

          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $e)
                  <li>{{ $e }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-3">
              <label class="form-label">Correo electrónico</label>
              <input type="email"
                     name="email"
                     class="form-control"
                     value="{{ old('email') }}"
                     required
                     autofocus>
            </div>
            {{-- reCAPTCHA --}}
                      @if (class_exists('\Anhskohbo\NoCaptcha\Facades\NoCaptcha'))
                        <div class="mb-3">
                          {!! NoCaptcha::display() !!}
                          {!! NoCaptcha::renderJs(app()->getLocale()) !!}
                        </div>
                      @endif
            <div class="d-grid mb-3">
              <button class="btn btn-fg btn-lg">
                Enviar enlace de recuperación
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
