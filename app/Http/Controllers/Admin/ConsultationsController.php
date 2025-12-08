<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultationsController extends Controller
{
    /** Solo ADMIN y ENFERMERIA pueden crear notas */
    protected function ensureCanEdit(): void
    {
        $role = auth()->user()?->role?->name;

        if (!in_array($role, ['ADMIN', 'ENFERMERIA'], true)) {
            abort(403, 'No autorizado para registrar consultas.');
        }
    }

        public function index(Patient $patient)
    {
        $patient->load([
            'category',
            'consultations' => function ($q) {
                $q->orderByDesc('consulted_at')
                ->orderByDesc('created_at');
            },
            'consultations.author',
            'files',
            'files.uploader',
        ]);

        // 🔹 ANTES: view('admin.consultations.index', ...)
        // 🔹 AHORA: el módulo de expediente clínico
        return view('admin.expediente.index', [
            'patient' => $patient,
        ]);
    }
    /**
     * POST /admin/pacientes/{patient}/consultas
     */
    public function store(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'consulted_at'   => ['nullable', 'date'],
            'reason'         => ['nullable', 'string', 'max:180'],
            'notes'          => ['required', 'string'],

            'diagnosis'      => ['nullable', 'string'],
            'plan'           => ['nullable', 'string'],

            'weight'         => ['nullable', 'numeric', 'between:0,500'],
            'height'         => ['nullable', 'numeric', 'between:0,3'],
            'blood_pressure' => ['nullable', 'string', 'max:15'],
            'heart_rate'     => ['nullable', 'integer', 'between:0,250'],
            'resp_rate'      => ['nullable', 'integer', 'between:0,80'],
            'temperature'    => ['nullable', 'numeric', 'between:30,45'],
        ]);

        $data['patient_id'] = $patient->id;
        $data['created_by'] = Auth::id();

        Consultation::create($data);

        return redirect()
            ->route('admin.consultations.index', $patient)
            ->with('ok', 'Nota de consulta registrada correctamente.');
    }

    public function update(Request $request, Patient $patient, Consultation $consultation)
    {
        // Por seguridad: que la consulta pertenezca a ese paciente
        if ($consultation->patient_id !== $patient->id) {
            abort(404);
        }

        $data = $request->validate([
            'consulted_at'   => ['nullable', 'date'],
            'reason'         => ['nullable', 'string', 'max:180'],
            'notes'          => ['required', 'string'],
            'diagnosis'      => ['nullable', 'string'],
            'plan'           => ['nullable', 'string'],
            'weight'         => ['nullable', 'numeric', 'between:0,500'],
            'height'         => ['nullable', 'numeric', 'between:0,3'],
            'blood_pressure' => ['nullable', 'string', 'max:15'],
            'heart_rate'     => ['nullable', 'integer', 'min:0'],
            'resp_rate'      => ['nullable', 'integer', 'min:0'],
            'temperature'    => ['nullable', 'numeric', 'between:25,45'],
        ]);

        $consultation->update($data);

        return redirect()
            ->route('admin.consultations.index', $patient)
            ->with('ok', 'Nota de consulta actualizada correctamente.');
    }

    public function destroy(Patient $patient, Consultation $consultation)
    {
        if ($consultation->patient_id !== $patient->id) {
            abort(404);
        }

        $consultation->delete();

        return redirect()
            ->route('admin.consultations.index', $patient)
            ->with('ok', 'Nota de consulta eliminada correctamente.');
    }

}
