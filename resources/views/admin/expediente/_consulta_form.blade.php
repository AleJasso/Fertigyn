{{-- resources/views/admin/expediente/_consulta_form.blade.php --}}
{{-- Parcial para el cuerpo del modal "Nueva nota de consulta" --}}

<div class="modal-body pt-3">

    {{-- 🔹 Sección: Datos de la consulta --}}
    <div class="fg-section-label mb-3">
        <span>Datos de la consulta</span>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <label class="form-label">Fecha y hora</label>
            <input type="datetime-local"
                   name="consulted_at"
                   class="form-control"
                   value="{{ old('consulted_at', now()->format('Y-m-d\TH:i')) }}">
        </div>

        <div class="col-md-8">
            <label class="form-label">Motivo (opcional)</label>
            <input type="text"
                   name="reason"
                   class="form-control"
                   value="{{ old('reason') }}"
                   placeholder="Control, dolor pélvico, revisión, etc.">
        </div>
    </div>

    {{-- 🔹 Sección: Nota clínica --}}
    <div class="fg-section-label mb-3">
        <span>Nota clínica</span>
    </div>

    <div class="mb-3">
        <label class="form-label">Descripción / hallazgos</label>
        <textarea name="notes"
                  class="form-control"
                  rows="4"
                  required
                  placeholder="Hallazgos, impresión clínica, indicaciones, etc.">{{ old('notes') }}</textarea>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <label class="form-label">Diagnóstico (opcional)</label>
            <textarea name="diagnosis"
                      class="form-control"
                      rows="2"
                      placeholder="Diagnóstico principal o diferenciales">{{ old('diagnosis') }}</textarea>
        </div>

        <div class="col-md-6">
            <label class="form-label">Plan / tratamiento</label>
            <textarea name="plan"
                      class="form-control"
                      rows="2"
                      placeholder="Tratamiento, estudios, seguimiento">{{ old('plan') }}</textarea>
        </div>
    </div>

    {{-- 🔹 Sección: Signos vitales (opcionales) --}}
    <div class="fg-section-label mb-3">
        <span>Signos vitales</span>
    </div>

    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Peso (kg)</label>
            <input type="number"
                   step="0.1"
                   min="0"
                   name="weight"
                   class="form-control"
                   value="{{ old('weight') }}">
        </div>

        <div class="col-md-3">
            <label class="form-label">Talla (m)</label>
            <input type="number"
                   step="0.01"
                   min="0"
                   name="height"
                   class="form-control"
                   value="{{ old('height') }}">
        </div>

        <div class="col-md-3">
            <label class="form-label">TA (ej. 120/80)</label>
            <input type="text"
                   name="blood_pressure"
                   class="form-control"
                   value="{{ old('blood_pressure') }}">
        </div>

        <div class="col-md-3">
            <label class="form-label">Temperatura (°C)</label>
            <input type="number"
                   step="0.1"
                   min="30"
                   max="45"
                   name="temperature"
                   class="form-control"
                   value="{{ old('temperature') }}">
        </div>
    </div>

    <div class="row g-3 mt-2">
        <div class="col-md-4">
            <label class="form-label">FC (lpm)</label>
            <input type="number"
                   min="0"
                   name="heart_rate"
                   class="form-control"
                   value="{{ old('heart_rate') }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">FR (rpm)</label>
            <input type="number"
                   min="0"
                   name="resp_rate"
                   class="form-control"
                   value="{{ old('resp_rate') }}">
        </div>
    </div>

</div>
