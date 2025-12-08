@extends('layouts.app')

@section('title','FertiGyn · Inicio')

@section('content')
<section class="fg-hero">
  <div class="container py-5">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <h1 class="display-4 fw-bold">Cuidado ginecológico, obstetrico y de fertilidad</h1>
        <p class="lead text-secondary mt-3">
          Sistema de gestión para consulta, expedientes y seguimiento clínico.
        </p>
        <a href="{{ route('login') }}" class="btn btn-fg btn-pill mt-3">Entrar</a>
      </div>
      <div class="col-lg-6 text-center">
        <img src="{{ asset('assets/img/fertigyn-logo.png') }}" alt="FertiGyn" class="img-fluid" style="max-height:360px">
      </div>
    </div>
  </div>
</section>
@endsection


