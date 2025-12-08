<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title','FertiGyn')</title>
  {{-- Bootstrap CSS (CDN o tu build) --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  {{-- Tu paleta/cambios --}}
  <link rel="stylesheet" href="{{ asset('css/fertigyn.css') }}">
  @stack('head')
</head>
<body class="bg-light">

  <nav class="navbar navbar-expand-lg bg-white border-bottom py-2">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
        <img src="{{ asset('assets/img/fertigyn-logo.png') }}" alt="FertiGyn" height="36">
        <span class="fw-bold" style="color:#d83d71">FertiGyn</span>
      </a>
      @auth
        <form action="{{ route('logout') }}" method="POST" class="ms-auto">
          @csrf
          <button class="btn btn-outline-secondary btn-sm" type="submit">Salir</button>
        </form>
      @endauth
    </div>
  </nav>

  <main>@yield('content')</main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
