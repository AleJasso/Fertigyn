@extends('layouts.app')
@section('title','Pacientes')

@section('content')
<div class="container py-4">

    {{-- ENCABEZADO --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
        <div class="mb-2 mb-md-0">
            <h1 class="h3 fw-semibold mb-1">Pacientes</h1>
            <p class="text-muted mb-0">
                Gestión de expedientes de ginecología, obstetricia y fertilidad.
            </p>
        </div>

        <div class="d-flex align-items-center gap-2">
            @can('admin-only')
                {{-- Botón para ver catálogo de usuarios --}}
                <a href="{{ route('admin.users.index') }}"
                class="btn btn-light border-0 shadow-sm d-flex align-items-center gap-2">
                    <span class="fs-5">📋</span>
                    <span>Catálogo de Usuarios</span>
                </a>
            

            {{-- Botón existente de nuevo paciente --}}
            <button class="btn btn-fg btn-lg d-flex align-items-center gap-2"
                    data-bs-toggle="modal" data-bs-target="#newPatientModal">
                <span class="fs-5">+</span>
                <span>Nuevo paciente</span>
            </button>
            @endcan
        </div>
    </div>



    {{-- TARJETA: BUSCADOR + CATEGORÍAS --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">

            {{-- BUSCADOR --}}
            <form id="patients-search-form" class="mb-3" autocomplete="off">
                <div class="input-group input-group-lg fg-search-box">
                    <span class="input-group-text bg-white border-0">
                        <span class="text-muted">🔍</span>
                    </span>
                    <input type="text"
                           id="q"
                           name="q"
                           class="form-control border-0 shadow-none"
                           placeholder="Buscar por nombre o apellidos..."
                           value="{{ $q ?? '' }}">
                </div>
            </form>

            {{-- CHIPS POR CATEGORÍA --}}
            @php
                // Totales por nombre de categoría
                $countsByName = $categories->pluck('patients_count', 'name');
                $totalAll     = $categories->sum('patients_count');

                $buttons = [
                    [
                        'slug'  => '',
                        'label' => 'Todas',
                        'code'  => null,
                        'emoji' => '🌐',
                    ],
                    [
                        'slug'  => 'fertilidad',
                        'label' => 'Fertilidad',
                        'code'  => 'Fertilidad',
                        'emoji' => '🍼',
                    ],
                    [
                        'slug'  => 'ginecologia',
                        'label' => 'Ginecología',
                        'code'  => 'Ginecología',
                        'emoji' => '🩺',
                    ],
                    [
                        'slug'  => 'obstetricia',
                        'label' => 'Obstetricia',
                        'code'  => 'Obstetricia',
                        'emoji' => '🤰',
                    ],
                ];
            @endphp

            <div class="d-flex flex-wrap justify-content-center gap-3 fg-category-chips">
                @foreach($buttons as $btn)
                    @php
                        $slug   = $btn['slug'];
                        $emoji  = $btn['emoji'];
                        $label  = $btn['label'];
                        $active = ($cat === $slug) || ($slug === '' && $cat === '');

                        if ($slug === '') {
                            $count = $totalAll;
                        } else {
                            $count = $countsByName[$btn['code']] ?? 0;
                        }
                    @endphp

                    <a href="{{ route('admin.patients.index', [
                            'cat' => $slug ?: null,
                            'q'   => $q ?: null,
                        ]) }}"
                       class="btn btn-lg rounded-pill px-4 py-2 fg-chip
                              {{ $active ? 'fg-chip-active' : 'fg-chip-inactive' }}">
                        <span class="me-2 fs-5">{{ $emoji }}</span>
                        <span class="fw-semibold">{{ $label }}</span>
                        <span class="badge bg-light text-dark ms-2">{{ $count }}</span>
                    </a>
                @endforeach
            </div>

        </div>
    </div>

    {{-- MENSAJE FLASH --}}
    @if (session('ok'))
        <div id="fg-flash" class="alert alert-fg-success mb-4 d-flex align-items-center" role="alert">
            <div class="alert-fg-icon me-3 fs-3">✅</div>
            <div>
                <div class="alert-fg-title fw-semibold mb-1">Operación exitosa</div>
                <div class="alert-fg-text">{{ session('ok') }}</div>
            </div>
        </div>
    @endif

    {{-- TABLA DE PACIENTES --}}
    <div id="patients-list">
        {{-- IMPORTANTE: en _table el <tbody> debe tener id="patients-tbody"
             y cada <tr> un data-search con nombre, apellidos, correo, teléfono --}}
        @include('patients._table', ['patients' => $patients, 'q' => $q, 'cat' => $cat])
    </div>

</div>

{{-- MODAL: NUEVO PACIENTE --}}
<div class="modal fade" id="newPatientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <form class="modal-content fg-modal" method="POST" action="{{ route('admin.patients.store') }}">
            @csrf

            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-3">
                    <span class="fg-modal-emoji">🩺</span>
                    <div>
                        <h5 class="modal-title mb-0">Nuevo paciente</h5>
                        <small class="text-muted">
                            Completa los datos básicos para crear el expediente clínico.
                        </small>
                    </div>
                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body pt-3">

                {{-- Sección 1: Datos generales --}}
                <div class="fg-section-label mb-3">
                    <span>Datos generales</span>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input name="first_name"
                               class="form-control form-control-lg"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Apellidos</label>
                        <input name="last_name"
                               class="form-control form-control-lg"
                               required>
                    </div>
                </div>

                {{-- Sección 2: Información clínica básica --}}
                <div class="fg-section-label mb-3">
                    <span>Información clínica básica</span>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Fecha de nacimiento</label>
                        <input name="birth_date"
                               type="date"
                               class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Sexo</label>
                        <select name="sex" class="form-select">
                            <option value="F" selected>Femenino</option>
                            <option value="M">Masculino</option>
                            <option value="O">Otro</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Categoría</label>
                        <select name="category_id" class="form-select" required>
                            @foreach($categories as $c)
                                @php
                                    $slug = strtolower($c->slug ?? \Illuminate\Support\Str::slug($c->name));
                                @endphp
                                <option value="{{ $c->id }}" @selected(strtolower($cat ?? '') === $slug)>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Sección 3: Contacto --}}
                <div class="fg-section-label mb-3">
                    <span>Contacto</span>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input name="phone"
                               class="form-control"
                               placeholder="XXX-XXX-XXXX">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Correo</label>
                        <input name="email"
                               type="email"
                               class="form-control"
                               placeholder="nombre@correo.com">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Dirección</label>
                        <textarea name="address"
                                  class="form-control"
                                  rows="2"
                                  placeholder="Calle, número, colonia…"></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button class="btn btn-fg px-4">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL GLOBAL: EDITAR PACIENTE --}}
<div class="modal fade" id="editPatientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <form id="editPatientForm" class="modal-content fg-modal" method="POST">
            @csrf
            @method('PUT')

            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-3">
                    <span class="fg-modal-emoji">✏️</span>
                    <div>
                        <h5 class="modal-title mb-0">Editar paciente</h5>
                        <small class="text-muted">
                            Actualiza los datos del expediente clínico.
                        </small>
                    </div>
                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body pt-3">

                {{-- Sección 1: Datos generales --}}
                <div class="fg-section-label mb-3">
                    <span>Datos generales</span>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input id="edit_first_name"
                               name="first_name"
                               class="form-control form-control-lg"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Apellidos</label>
                        <input id="edit_last_name"
                               name="last_name"
                               class="form-control form-control-lg"
                               required>
                    </div>
                </div>

                {{-- Sección 2: Información clínica básica --}}
                <div class="fg-section-label mb-3">
                    <span>Información clínica básica</span>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Fecha de nacimiento</label>
                        <input id="edit_birth_date"
                               name="birth_date"
                               type="date"
                               class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Sexo</label>
                        <select id="edit_sex" name="sex" class="form-select">
                            <option value="F">Femenino</option>
                            <option value="M">Masculino</option>
                            <option value="O">Otro</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Categoría</label>
                        <select id="edit_category_id" name="category_id" class="form-select" required>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Sección 3: Contacto --}}
                <div class="fg-section-label mb-3">
                    <span>Contacto</span>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input id="edit_phone"
                               name="phone"
                               class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Correo</label>
                        <input id="edit_email"
                               name="email"
                               type="email"
                               class="form-control">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Dirección</label>
                        <textarea id="edit_address"
                                  name="address"
                                  class="form-control"
                                  rows="2"></textarea>
                    </div>
                </div>
            </div>

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

{{-- SCRIPTS: flash, búsqueda y edición --}}
<script>
(() => {
    // ===== 1) Ocultar mensaje flash después de 2s con animación =====
    const flash = document.getElementById('fg-flash');
    if (flash) {
        setTimeout(() => {
            flash.classList.add('is-fading');
            // asegurar que se quite aunque no hubiera transición
            setTimeout(() => flash.remove(), 300);
        }, 2000);
    }

    // ===== 2) Búsqueda local por nombre/apellidos =====
    const inputQ = document.getElementById('q');
    const tbody  = document.getElementById('patients-tbody');
    const form   = document.getElementById('patients-search-form');

    if (inputQ && tbody && form) {
        const rows = Array.from(tbody.querySelectorAll('tr[data-search]'));

        const applyFilter = () => {
            const term = inputQ.value.trim().toLowerCase();
            rows.forEach(row => {
                const text = (row.dataset.search || '').toLowerCase();
                const show = !term || text.includes(term);
                row.classList.toggle('d-none', !show);
            });
        };

        // mientras escribe
        inputQ.addEventListener('input', applyFilter);

        // al presionar Enter
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            applyFilter();
        });
    }

    // ===== 3) Rellenar modal de edición cuando se hace clic en ✏️ =====
    document.addEventListener('click', (ev) => {
        const btn = ev.target.closest('.btn-edit-patient');
        if (!btn) return;

        const id         = btn.dataset.id;
        const firstName  = btn.dataset.firstName || '';
        const lastName   = btn.dataset.lastName  || '';
        const email      = btn.dataset.email     || '';
        const phone      = btn.dataset.phone     || '';
        const birth      = btn.dataset.birth     || '';
        const sex        = btn.dataset.sex       || 'F';
        const categoryId = btn.dataset.categoryId || '';
        const address    = btn.dataset.address   || '';

        const formEdit = document.getElementById('editPatientForm');
        if (!formEdit) return;

        // URL para actualizar: /admin/pacientes/{id}
        formEdit.action = `{{ url('admin/pacientes') }}/${id}`;

        document.getElementById('edit_first_name').value  = firstName;
        document.getElementById('edit_last_name').value   = lastName;
        document.getElementById('edit_email').value       = email;
        document.getElementById('edit_phone').value       = phone;
        document.getElementById('edit_birth_date').value  = birth;
        document.getElementById('edit_address').value     = address;

        const sexSelect = document.getElementById('edit_sex');
        if (sexSelect) sexSelect.value = sex;

        const catSelect = document.getElementById('edit_category_id');
        if (catSelect && categoryId) catSelect.value = categoryId;
    });
})();
</script>
@endsection
