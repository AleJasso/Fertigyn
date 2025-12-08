@extends('layouts.app')
@section('title','Iniciar sesión')
@section('body_class','bg-fg-gradient')

@section('content')
<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-12 col-md-7 col-lg-5">
      <div class="card auth-card p-4">
        <div class="text-center mb-3">
          <img src="{{ asset('assets/img/fertigyn-logo.png') }}" alt="FertiGyn" height="56">
          <h4 class="mt-2 fw-bold" style="color:#d83d71">FertiGyn</h4>
          <small class="text-muted">Acceso administrativo</small>
        </div>

        @if ($errors->any())
          <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
          @csrf

          {{-- Correo --}}
          <div class="mb-3">
            <label class="form-label">Correo</label>
            <input name="email"
                   type="email"
                   value="{{ old('email') }}"
                   class="form-control"
                   required
                   autofocus>
          </div>

          {{-- Contraseña + ojo --}}
          <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <div class="input-group">
              <input name="password"
                     id="password"
                     type="password"
                     class="form-control"
                     required
                     autocomplete="current-password">

              <button class="btn btn-outline-secondary btn-eye"
                      type="button"
                      id="togglePwd"
                      aria-label="Mostrar contraseña">
                {{-- ojo --}}
                <svg id="icon-eye" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                     fill="currentColor" viewBox="0 0 16 16">
                  <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/>
                  <path fill="#ffffffff" d="M8 5a3 3 0 1 0 0 6 3 3 0 0 0 0-6"/>
                </svg>
                {{-- ojo tachado --}}
                <svg id="icon-eye-slash" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                     fill="currentColor" viewBox="0 0 16 16" style="display:none">
                  <path d="M13.359 11.238C15.06 9.965 16 8 16 8s-3-5.5-8-5.5c-1.27 0-2.447.318-3.48.845l.72.72C6.1 3.684 7.015 3.5 8 3.5 12 3.5 15 8 15 8s-.6 1.076-1.76 2.167l.119.07zM2.354 1.646l12 12-.708.708-2.093-2.093A8.377 8.377 0 0 1 8 13.5C4 13.5 1 9 1 9s.94-1.965 2.641-3.238L1.646 2.354l.708-.708z"/>
                  <path d="M11.297 9.889l-1.17-1.17A3 3 0 0 0 6.83 5.423l-1.17-1.17A3.999 3.999 0 0 1 12 8c0 .673-.168 1.307-.462 1.889zM5.5 8a2.5 2.5 0 0 0 3.59 2.237l-.774-.774A1.5 1.5 0 0 1 6.537 6.684l-.774-.774A2.48 2.48 0 0 0 5.5 8z"/>
                </svg>
              </button>
            </div>
          </div>

          {{-- reCAPTCHA --}}
          @if (class_exists('\Anhskohbo\NoCaptcha\Facades\NoCaptcha'))
            <div class="mb-3">
              {!! NoCaptcha::display() !!}
              {!! NoCaptcha::renderJs(app()->getLocale()) !!}
            </div>
          @endif

          {{-- Aviso de privacidad y cookies --}}
          <div class="mb-3 form-check text-start">
            <input class="form-check-input"
                   type="checkbox"
                   id="accept_privacy"
                   name="accept_privacy"
                   required>
            <label class="form-check-label" for="accept_privacy">
                He leído y acepto el
                <a href="{{ route('privacy.notice') }}" target="_blank">
                    Aviso de privacidad
                </a>
                Y el uso de cookies
            </label>

          </div>

          <div class="d-grid">
            <button class="btn btn-fg btn-pill" type="submit">Entrar</button>
          </div>

          <div class="text-center mt-3">
            <a href="{{ route('password.request') }}" style="color:#d83d71">
              ¿Olvidaste tu contraseña?
            </a>
          </div>
        </form>
      </div>

      <p class="text-center text-muted small mt-3">
        © {{ date('Y') }} FertiGyn
      </p>
    </div>
  </div>
</div>

{{-- Modal: Aviso de privacidad y uso de cookies --}}
<div class="modal fade" id="privacyModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title">Aviso de privacidad y uso de cookies</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <p class="small text-muted">
          FertiGyn, como responsable del tratamiento de los datos personales, utiliza la
          información proporcionada únicamente para la gestión de citas y expedientes clínicos
          del consultorio.
        </p>

        <ul class="small text-muted">
          <li>Se recaban datos como nombre, correo electrónico, teléfono y credenciales de acceso.</li>
          <li>La finalidad es administrar el acceso del personal autorizado al sistema FertiGyn.</li>
          <li>Los datos no se comparten con terceros, salvo obligación legal.</li>
          <li>Puedes ejercer tus derechos de acceso, rectificación o cancelación directamente con el consultorio.</li>
        </ul>

        <p class="small text-muted mb-0">
          Este sistema también utiliza cookies técnicas para mantener tu sesión iniciada y
          mejorar tu experiencia de uso. No se emplean cookies con fines publicitarios.
        </p>
      </div>

      <div class="modal-footer border-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cerrar
        </button>
      </div>
    </div>
  </div>
</div>

{{-- Banner de cookies --}}
<div id="cookie-banner"
     class="position-fixed bottom-0 start-50 translate-middle-x mb-3 px-3 py-2
            d-flex align-items-center gap-3 bg-dark text-white rounded-pill shadow"
     style="z-index:1080;">
  <p class="mb-0 small">
    Usamos cookies técnicas para mantener tu sesión activa y mejorar tu experiencia en FertiGyn.
    Al continuar, aceptas su uso.
  </p>
  <button type="button" class="btn btn-sm btn-fg" data-cookie-accept>
    Aceptar
  </button>
</div>

{{-- Scripts: ojo de contraseña + cookies --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Toggle contraseña
  const input = document.getElementById('password');
  const btn   = document.getElementById('togglePwd');
  const eye   = document.getElementById('icon-eye');
  const slash = document.getElementById('icon-eye-slash');

  if (btn && input) {
    btn.addEventListener('click', function () {
      const show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      btn.setAttribute('aria-label', show ? 'Ocultar contraseña' : 'Mostrar contraseña');
      eye.style.display   = show ? 'none' : '';
      slash.style.display = show ? '' : 'none';
    });
  }

  // Banner de cookies
  const banner = document.getElementById('cookie-banner');
  if (banner) {
    // Si ya aceptó antes, no mostrar
    if (localStorage.getItem('fg_cookie_consent') === '1') {
      banner.classList.add('d-none');
    } else {
      const acceptBtn = banner.querySelector('[data-cookie-accept]');
      if (acceptBtn) {
        acceptBtn.addEventListener('click', () => {
          localStorage.setItem('fg_cookie_consent', '1');
          banner.classList.add('d-none');
        });
      }
    }
  }
});
</script>
@endsection
