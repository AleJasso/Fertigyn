@extends('layouts.app')

@section('title','Aviso de privacidad – FertiGyn')

@section('content')
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-lg-8">

      <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 p-md-5">

          <h1 class="h4 fw-semibold mb-3">Aviso de privacidad de FertiGyn</h1>

          <p class="text-muted">
            Este aviso de privacidad aplica al sistema web <strong>FertiGyn</strong>,
            utilizado para la gestión de expedientes clínicos en el consultorio de
            ginecología y fertilidad.
          </p>

          <h2 class="h6 fw-semibold mt-4">1. Responsable del tratamiento</h2>
          <p class="mb-2">
            El responsable del tratamiento de sus datos personales es el consultorio
            de ginecología <strong>FertiGyn</strong>, a cargo del Dr. Mauro de Luna Valadez.
          </p>

          <h2 class="h6 fw-semibold mt-4">2. Datos personales que se recaban</h2>
          <p class="mb-2">
            A través de este sistema se tratan, entre otros, los siguientes datos:
          </p>
          <ul>
            <li>Datos de identificación: nombre completo.</li>
            <li>Datos de contacto: teléfono, correo electrónico, domicilio.</li>
            <li>Datos clínicos y antecedentes médicos relacionados con la atención ginecológica, obstétrica y de fertilidad.</li>
          </ul>

          <h2 class="h6 fw-semibold mt-4">3. Finalidades del tratamiento</h2>
          <p class="mb-2">
            Los datos personales se utilizan para:
          </p>
          <ul>
            <li>Crear y administrar expedientes clínicos electrónicos.</li>
            <li>Registrar consultas, notas médicas y seguimiento de tratamientos.</li>
            <li>Contactarle para recordatorios o aclaraciones relacionadas con su atención.</li>
            <li>Cumplir obligaciones legales y de resguardo de información clínica.</li>
          </ul>

          <h2 class="h6 fw-semibold mt-4">4. Medidas de seguridad</h2>
          <p>
            El sistema FertiGyn implementa medidas técnicas y administrativas para proteger
            sus datos personales, tales como:
          </p>
          <ul>
            <li>Acceso restringido mediante usuario y contraseña.</li>
            <li>Roles diferenciados (Administrador y Enfermería – solo lectura).</li>
            <li>Sesiones autenticadas y bloqueo por intentos fallidos.</li>
            <li>Cifrado de contraseñas y uso de conexión segura (HTTPS) en el servidor de producción.</li>
          </ul>

          <h2 class="h6 fw-semibold mt-4">5. Derechos ARCO</h2>
          <p>
            Usted tiene derecho a <strong>Acceder</strong> a sus datos personales,
            <strong>Rectificarlos</strong> si son inexactos o incompletos,
            <strong>Cancelarlos</strong> cuando considere que no se requieren para las finalidades señaladas,
            u <strong>Oponerse</strong> a su tratamiento, de conformidad con la Ley Federal de Protección de Datos Personales en Posesión de los Particulares.
          </p>
          <p>
            Para ejercer cualquiera de estos derechos puede ponerse en contacto al correo:
            <strong>fertigyn.consultorio@gmail.com</strong> 
          </p>

          <h2 class="h6 fw-semibold mt-4">6. Uso de cookies y tecnologías similares</h2>
          <p>
            El sistema puede utilizar cookies de sesión estrictamente necesarias para mantener
            su sesión autenticada mientras navega en el panel administrativo. No se utilizan
            cookies con fines de publicidad o perfiles de comportamiento.
          </p>

          <h2 class="h6 fw-semibold mt-4">7. Cambios al aviso de privacidad</h2>
          <p class="mb-4">
            Cualquier modificación a este aviso de privacidad será publicada dentro del propio
            sistema FertiGyn. El uso continuo de la plataforma implica la aceptación de dichos cambios.
          </p>

          <div class="text-end">
            <a href="{{ route('login') }}" class="btn btn-outline-primary">
              Volver al acceso
            </a>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>
@endsection
