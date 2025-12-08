@extends('layouts.app')

@section('title','Cuenta activada')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6 text-center">
      <div class="mb-3">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle"
             style="width:72px; height:72px; background:#eaf7f0;">
          <span style="font-size:32px;">✅</span>
        </div>
      </div>
      <h1 class="h4 mb-3">Cuenta activada</h1>
      <p class="mb-3">
        Hola <strong>{{ $user->name }}</strong>, tu correo ha sido confirmado y tu cuenta
        de FertiGyn está activa. Ya puedes iniciar sesión con tus credenciales.
      </p>
      <a href="{{ route('login') }}" class="btn btn-fg">
        Ir al inicio de sesión
      </a>
    </div>
  </div>
</div>
@endsection
