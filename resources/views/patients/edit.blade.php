@extends('layouts.app')
@section('title', 'Editar paciente')

@section('content')
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-12 col-xl-9">

      <div class="card fg-modal border-0">
        <div class="card-header bg-white border-0 pb-0">
          <div class="d-flex justify-content-between align-items-start">
            <div class="d-flex align-items-center gap-3">
              <span class="fg-modal-emoji">✏️</span>
              <div>
                <h1 class="h4 mb-0">Editar paciente</h1>
                <small class="text-muted">
                  {{ $patient->full_name }}
                  @if($patient->age)
                    · {{ $patient->age }} años
                  @endif
                </small>
              </div>
            </div>

            <a href="{{ route('admin.patients.index') }}"
               class="btn btn-outline-secondary btn-sm">
              ← Volver
            </a>
          </div>
        </div>

        <form method="POST" action="{{ route('admin.patients.update', $patient) }}">
          @csrf
          @method('PUT')

          <div class="card-body pt-3">

            {{-- Sección 1: Datos generales --}}
            <div class="fg-section-label mb-3">
              <span>Datos generales</span>
            </div>

            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <label class="form-label">Nombre</label>
                <input name="first_name"
                       class="form-control form-control-lg"
                       value="{{ old('first_name', $patient->first_name) }}"
                       required>
              </div>

              <div class="col-md-6">
                <label class="form-label">Apellidos</label>
                <input name="last_name"
                       class="form-control form-control-lg"
                       value="{{ old('last_name', $patient->last_name) }}"
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
                <input type="date"
                       name="birth_date"
                       class="form-control"
                       value="{{ old('birth_date', optional($patient->birth_date)->format('Y-m-d')) }}">
              </div>

              <div class="col-md-4">
                <label class="form-label">Sexo</label>
                <select name="sex" class="form-select" required>
                  <option value="F" @selected(old('sex', $patient->sex) === 'F')>Femenino</option>
                  <option value="M" @selected(old('sex', $patient->sex) === 'M')>Masculino</option>
                  <option value="O" @selected(old('sex', $patient->sex) === 'O')>Otro</option>
                </select>
              </div>

              <div class="col-md-4">
                <label class="form-label">Categoría</label>
                <select name="category_id" class="form-select" required>
                  @foreach($categories as $c)
                    <option value="{{ $c->id }}"
                      @selected(old('category_id', $patient->category_id) == $c->id)>
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
                       value="{{ old('phone', $patient->phone) }}">
              </div>

              <div class="col-md-6">
                <label class="form-label">Correo</label>
                <input type="email"
                       name="email"
                       class="form-control"
                       value="{{ old('email', $patient->email) }}">
              </div>

              <div class="col-12">
                <label class="form-label">Dirección</label>
                <textarea name="address"
                          class="form-control"
                          rows="2">{{ old('address', $patient->address) }}</textarea>
              </div>
            </div>
          </div>

          <div class="card-footer bg-white border-0 d-flex justify-content-end gap-2">
            <a href="{{ route('admin.patients.index') }}"
               class="btn btn-outline-secondary">
              Cancelar
            </a>

            <button class="btn btn-fg px-4">
              Guardar cambios
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>
@endsection
