@php
    use Illuminate\Support\Str;
@endphp

@extends('layouts.app')
@section('title', 'Expediente · ' . $patient->full_name)

@section('content')
<div class="container py-4">

    {{-- Encabezado + botón regresar --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 fw-semibold mb-1">
                Expediente clínico
            </h1>
            <p class="text-muted mb-0">
                {{ $patient->full_name }} ·
                @if($patient->age) {{ $patient->age }} años · @endif
                {{ $patient->category?->name }}
            </p>
        </div>

        <div class="d-flex align-items-center gap-2">
            {{-- 🔙 Botón de volver a pacientes --}}
            <a href="{{ route('admin.patients.index') }}"
               class="btn btn-outline-secondary d-inline-flex align-items-center gap-1"
               style="border-radius:999px;">
                <span class="fs-5">←</span>
                <span>Volver a pacientes</span>
            </a>
        </div>
    </div>

    {{-- Mensaje flash --}}
    @if (session('ok'))
        <div id="fg-flash" class="alert alert-fg-success mb-4 d-flex align-items-center" role="alert">
            <div class="alert-fg-icon me-3 fs-3">✅</div>
            <div>
                <div class="alert-fg-title fw-semibold mb-1">Operación exitosa</div>
                <div class="alert-fg-text">{{ session('ok') }}</div>
            </div>
        </div>
    @endif

    {{-- Tarjeta con datos del paciente --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="mb-0">{{ $patient->full_name }}</h5>
                            <small class="text-muted">
                                @if($patient->age) {{ $patient->age }} años · @endif
                                {{ $patient->sex === 'F' ? 'Femenino' : ($patient->sex === 'M' ? 'Masculino' : 'Otro') }}
                            </small>
                        </div>
                        <span class="badge rounded-pill bg-light text-dark">
                            {{ $patient->category?->name ?? 'Sin categoría' }}
                        </span>
                    </div>

                    <div class="row g-2 small text-muted">
                        <div class="col-md-6">
                            <div><strong>Teléfono:</strong> {{ $patient->phone ?: '—' }}</div>
                            <div><strong>Correo:</strong> {{ $patient->email ?: '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div><strong>Dirección:</strong> {{ $patient->address ?: '—' }}</div>
                            <div><strong>Creado:</strong> {{ $patient->created_at?->format('d/m/Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Resumen rápido --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="fg-section-label mb-3">
                        <span>Resumen</span>
                    </div>

                    <p class="mb-1 small text-muted">Consultas registradas</p>
                    <h4 class="mb-3">{{ $patient->consultations->count() }}</h4>

                    @if($patient->consultations->first())
                        <p class="mb-1 small text-muted">Última consulta</p>
                        <p class="mb-0">
                            {{ $patient->consultations->first()->consulted_at?->format('d/m/Y H:i') ?? 'Sin fecha' }}
                        </p>
                    @else
                        <p class="text-muted small mb-0">Aún no hay notas de consulta.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- NAV --}}
    <ul class="nav nav-pills mb-3">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-consultas" type="button">
                🩺 Consultas
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-archivos" type="button">
                📂 Archivos
            </button>
        </li>
    </ul>

    <div class="tab-content">

        {{-- TAB CONSULTAS --}}
        <div class="tab-pane fade show active" id="tab-consultas">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Notas de consulta</span>
                    @can('admin-only')
                        <button class="btn btn-fg btn-sm d-flex align-items-center gap-1"
                                data-bs-toggle="modal"
                                data-bs-target="#newConsultationModal">
                            <span>+ Nueva nota</span>
                        </button>
                    @endcan
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 160px;">Fecha</th>
                                    <th>Motivo / Nota</th>
                                    <th style="width: 180px;">Registrado por</th>
                                    <th style="width: 160px;" class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($patient->consultations as $c)
                                    <tr>
                                        <td>
                                            {{ $c->consulted_at?->format('d/m/Y H:i') ?? $c->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td>
                                            @if($c->reason)
                                                <div class="fw-semibold">{{ $c->reason }}</div>
                                            @endif
                                            <div class="text-muted small">
                                                {{ Str::limit($c->notes, 120) }}
                                            </div>
                                        </td>
                                        <td class="small text-muted">
                                            {{ $c->author?->name ?? '—' }}
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm" role="group">

                                                {{-- Ver --}}
                                                <button type="button"
                                                        class="btn btn-outline-secondary btn-consult-view"
                                                        title="Ver nota completa"
                                                        data-date="{{ $c->consulted_at?->format('d/m/Y H:i') ?? $c->created_at->format('d/m/Y H:i') }}"
                                                        data-reason="{{ $c->reason }}"
                                                        data-notes="{{ $c->notes }}"
                                                        data-diagnosis="{{ $c->diagnosis }}"
                                                        data-plan="{{ $c->plan }}"
                                                        data-weight="{{ $c->weight }}"
                                                        data-height="{{ $c->height }}"
                                                        data-bp="{{ $c->blood_pressure }}"
                                                        data-hr="{{ $c->heart_rate }}"
                                                        data-rr="{{ $c->resp_rate }}"
                                                        data-temp="{{ $c->temperature }}"
                                                        data-author="{{ $c->author?->name ?? '—' }}">
                                                    👁
                                                </button>

                                                @can('admin-only')
                                                    {{-- Editar --}}
                                                    <button type="button"
                                                            class="btn btn-outline-primary btn-consult-edit"
                                                            title="Editar nota"
                                                            data-id="{{ $c->id }}"
                                                            data-consulted_at="{{ $c->consulted_at?->format('Y-m-d\TH:i') }}"
                                                            data-reason="{{ $c->reason }}"
                                                            data-notes="{{ $c->notes }}"
                                                            data-diagnosis="{{ $c->diagnosis }}"
                                                            data-plan="{{ $c->plan }}"
                                                            data-weight="{{ $c->weight }}"
                                                            data-height="{{ $c->height }}"
                                                            data-bp="{{ $c->blood_pressure }}"
                                                            data-hr="{{ $c->heart_rate }}"
                                                            data-rr="{{ $c->resp_rate }}"
                                                            data-temp="{{ $c->temperature }}">
                                                        ✏️
                                                    </button>

                                                    {{-- Eliminar --}}
                                                    <form action="{{ route('admin.consultations.destroy', [$patient, $c]) }}"
                                                          method="POST"
                                                          class="d-inline"
                                                          onsubmit="return confirm('¿Eliminar esta nota de consulta?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-outline-danger" title="Eliminar nota">
                                                            🗑
                                                        </button>
                                                    </form>
                                                @endcan

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Aún no hay notas de consulta para este paciente.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB ARCHIVOS --}}
        <div class="tab-pane fade" id="tab-archivos">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Archivos del paciente</span>

                    @can('admin-only')
                        <button class="btn btn-fg btn-sm d-flex align-items-center gap-1"
                                data-bs-toggle="modal"
                                data-bs-target="#uploadFileModal">
                            <span>+ Subir archivo</span>
                        </button>
                    @endcan
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 180px;">Fecha</th>
                                    <th>Nombre / descripción</th>
                                    <th style="width: 160px;">Tamaño</th>
                                    <th style="width: 180px;">Subido por</th>
                                    <th style="width: 120px;" class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($patient->files as $f)
                                    <tr>
                                        <td>{{ $f->created_at?->format('d/m/Y H:i') ?? '—' }}</td>
                                        <td>
                                            <div class="fw-semibold">
                                                <a href="{{ asset('storage/'.$f->path) }}" target="_blank">
                                                    {{ $f->original_name }}
                                                </a>
                                            </div>
                                            @if($f->description)
                                                <div class="text-muted small">
                                                    {{ $f->description }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="small text-muted">
                                            @if($f->size_bytes)
                                                {{ number_format($f->size_bytes / 1024, 1) }} KB
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="small text-muted">
                                            {{ $f->uploader?->name ?? '—' }}
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ asset('storage/'.$f->path) }}"
                                                   class="btn btn-outline-secondary"
                                                   target="_blank"
                                                   title="Ver / descargar">
                                                    ⬇️
                                                </a>

                                                @can('admin-only')
                                                    <form action="{{ route('admin.patients.files.destroy', [$patient, $f]) }}"
                                                          method="POST"
                                                          class="d-inline"
                                                          onsubmit="return confirm('¿Eliminar este archivo?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-outline-danger" title="Eliminar archivo">
                                                            🗑
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            Aún no hay archivos cargados para este paciente.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div> {{-- /.tab-content --}}

</div> {{-- /.container --}}

{{-- MODAL: Nueva nota de consulta --}}
<div class="modal fade" id="newConsultationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form class="modal-content fg-modal"
              method="POST"
              action="{{ route('admin.consultations.store', $patient) }}">
            @csrf

            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-3">
                    <span class="fg-modal-emoji">🩺</span>
                    <div>
                        <h5 class="modal-title mb-0">Nueva nota de consulta</h5>
                        <small class="text-muted">
                            Registra brevemente el motivo, signos vitales y la nota clínica.
                        </small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- Campos reutilizables de la consulta --}}
            @include('admin.expediente._consulta_form')

            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button class="btn btn-fg px-4">
                    Guardar nota
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL: Ver nota de consulta --}}
<div class="modal fade" id="viewConsultationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content fg-modal">

            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-3">
                    <span class="fg-modal-emoji">👁</span>
                    <div>
                        <h5 class="modal-title mb-0">Detalle de consulta</h5>
                        <small class="text-muted">
                            Información registrada en la nota seleccionada.
                        </small>
                    </div>
                </div>
            </div>

            <div class="modal-body pt-3">
                <dl class="row mb-0 small">
                    <dt class="col-sm-3">Fecha y hora</dt>
                    <dd class="col-sm-9" id="view-date">—</dd>

                    <dt class="col-sm-3">Motivo</dt>
                    <dd class="col-sm-9" id="view-reason">—</dd>

                    <dt class="col-sm-3">Nota clínica</dt>
                    <dd class="col-sm-9" id="view-notes">—</dd>

                    <dt class="col-sm-3">Diagnóstico</dt>
                    <dd class="col-sm-9" id="view-diagnosis">—</dd>

                    <dt class="col-sm-3">Plan / tratamiento</dt>
                    <dd class="col-sm-9" id="view-plan">—</dd>

                    <dt class="col-sm-3">Signos vitales</dt>
                    <dd class="col-sm-9" id="view-vitals">—</dd>

                    <dt class="col-sm-3">Registrado por</dt>
                    <dd class="col-sm-9" id="view-author">—</dd>
                </dl>
            </div>

        </div>
    </div>
</div>

{{-- MODAL: Editar nota de consulta --}}
<div class="modal fade" id="editConsultationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form class="modal-content fg-modal" method="POST" id="editConsultationForm">
            @csrf
            @method('PUT')

            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-3">
                    <span class="fg-modal-emoji">✏️</span>
                    <div>
                        <h5 class="modal-title mb-0">Editar nota de consulta</h5>
                        <small class="text-muted">
                            Actualiza los datos registrados de la consulta.
                        </small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- Reutilizamos el mismo parcial --}}
            @include('admin.expediente._consulta_form')

            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button class="btn btn-fg px-4">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL: Subir archivo --}}
<div class="modal fade" id="uploadFileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <form class="modal-content fg-modal"
              method="POST"
              action="{{ route('admin.patients.files.store', $patient) }}"
              enctype="multipart/form-data">
            @csrf

            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-3">
                    <span class="fg-modal-emoji">📂</span>
                    <div>
                        <h5 class="modal-title mb-0">Subir archivo</h5>
                        <small class="text-muted">
                            Adjunta estudios, documentos o imágenes relacionadas con la paciente.
                        </small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body pt-3">
                <div class="mb-3">
                    <label class="form-label">Archivo</label>
                    <input type="file" name="file" class="form-control" required>
                    <small class="text-muted">Tamaño máximo 5 MB. Se recomienda PDF o imagen.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción (opcional)</label>
                    <input type="text"
                           name="description"
                           class="form-control"
                           maxlength="255"
                           placeholder="Ej. Resultado de ultrasonido, laboratorio, etc.">
                </div>
            </div>

            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button class="btn btn-fg px-4">
                    Subir archivo
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Script: flash + ver/editar consulta --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const flash = document.getElementById('fg-flash');
    if (flash) {
        setTimeout(() => {
            flash.classList.add('is-fading');
            flash.addEventListener('transitionend', () => flash.remove());
        }, 2000);
    }

    // ===== VER CONSULTA =====
    document.addEventListener('click', (ev) => {
        const btnView = ev.target.closest('.btn-consult-view');
        if (!btnView) return;

        const modalEl = document.getElementById('viewConsultationModal');
        if (!modalEl) return;

        modalEl.querySelector('#view-date').textContent      = btnView.dataset.date || '—';
        modalEl.querySelector('#view-reason').textContent    = btnView.dataset.reason || '—';
        modalEl.querySelector('#view-notes').textContent     = btnView.dataset.notes || '—';
        modalEl.querySelector('#view-diagnosis').textContent = btnView.dataset.diagnosis || '—';
        modalEl.querySelector('#view-plan').textContent      = btnView.dataset.plan || '—';

        const vitals = [];
        if (btnView.dataset.weight || btnView.dataset.height) {
            vitals.push(`Peso: ${btnView.dataset.weight || '—'} kg, Talla: ${btnView.dataset.height || '—'} m`);
        }
        if (btnView.dataset.bp)   vitals.push(`TA: ${btnView.dataset.bp}`);
        if (btnView.dataset.hr)   vitals.push(`FC: ${btnView.dataset.hr} lpm`);
        if (btnView.dataset.rr)   vitals.push(`FR: ${btnView.dataset.rr} rpm`);
        if (btnView.dataset.temp) vitals.push(`Temp: ${btnView.dataset.temp} °C`);

        modalEl.querySelector('#view-vitals').textContent = vitals.join(' · ') || '—';
        modalEl.querySelector('#view-author').textContent = btnView.dataset.author || '—';

        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    });

    // ===== EDITAR CONSULTA =====
    document.addEventListener('click', (ev) => {
        const btnEdit = ev.target.closest('.btn-consult-edit');
        if (!btnEdit) return;

        const form = document.getElementById('editConsultationForm');
        const modalEl = document.getElementById('editConsultationModal');
        if (!form || !modalEl) return;

        // URL: /admin/pacientes/{patient}/consultas/{id}
        form.action = "{{ url('admin/pacientes/'.$patient->id.'/consultas') }}/" + btnEdit.dataset.id;

        modalEl.querySelector('[name="consulted_at"]').value   = btnEdit.dataset.consulted_at || '';
        modalEl.querySelector('[name="reason"]').value         = btnEdit.dataset.reason || '';
        modalEl.querySelector('[name="notes"]').value          = btnEdit.dataset.notes || '';
        modalEl.querySelector('[name="diagnosis"]').value      = btnEdit.dataset.diagnosis || '';
        modalEl.querySelector('[name="plan"]').value           = btnEdit.dataset.plan || '';
        modalEl.querySelector('[name="weight"]').value         = btnEdit.dataset.weight || '';
        modalEl.querySelector('[name="height"]').value         = btnEdit.dataset.height || '';
        modalEl.querySelector('[name="blood_pressure"]').value = btnEdit.dataset.bp || '';
        modalEl.querySelector('[name="heart_rate"]').value     = btnEdit.dataset.hr || '';
        modalEl.querySelector('[name="resp_rate"]').value      = btnEdit.dataset.rr || '';
        modalEl.querySelector('[name="temperature"]').value    = btnEdit.dataset.temp || '';

        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    });
});
</script>
@endsection
