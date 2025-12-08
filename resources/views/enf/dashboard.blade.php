@extends('layouts.app')

@section('title', 'Panel de Enfermería')

@section('content')
<div class="container py-4">

    {{-- ENCABEZADO --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-semibold mb-1">Panel de Enfermería (solo lectura)</h1>
            <p class="text-muted mb-0">
                Acceso de lectura a pacientes, expedientes clínicos, consultas y signos vitales.
            </p>
        </div>
    </div>

    <div class="row g-3">

        {{-- TARJETA PRINCIPAL: pacientes / expedientes --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                    <div class="mb-3 mb-md-0">
                        <h5 class="mb-1">Pacientes y expedientes</h5>
                        <p class="text-muted small mb-0">
                            Desde este panel puedes consultar la lista de pacientes y revisar su expediente
                            clínico (notas de consulta y signos vitales) sin realizar cambios.
                        </p>
                    </div>

                    <a href="{{ route('nurse.patients.index') }}"
                       class="btn btn-fg mt-2 mt-md-0">
                        Ver lista de pacientes
                    </a>
                </div>
            </div>
        </div>

        {{-- TARJETA: alcances del rol --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="fg-section-label mb-3">
                        <span>Alcances del perfil</span>
                    </div>
                    <ul class="small text-muted mb-0">
                        <li>Puede ver datos básicos de los pacientes.</li>
                        <li>Puede consultar notas de consulta y signos vitales.</li>
                        <li>No puede crear, editar ni eliminar pacientes.</li>
                        <li>No puede crear, editar ni eliminar usuarios ni roles.</li>
                        <li>No puede modificar notas de consulta.</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
