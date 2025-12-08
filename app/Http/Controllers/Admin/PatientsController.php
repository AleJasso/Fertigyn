<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PatientsController extends Controller
{
    /* =========================================================
     *  Helpers de autorización
     * ========================================================= */

    protected function ensureCanView(): void
    {
        $role = auth()->user()?->role?->name;

        if (! in_array($role, ['ADMIN', 'ENFERMERIA'], true)) {
            abort(403, 'No autorizado para ver pacientes.');
        }
    }

    protected function ensureAdmin(): void
    {
        $role = auth()->user()?->role?->name;

        if ($role !== 'ADMIN') {
            abort(403, 'Solo el administrador puede realizar esta acción.');
        }
    }

    /* =========================================================
     *  Listado / búsqueda
     * ========================================================= */

    // GET /admin/pacientes  y /enfermeria/pacientes
    public function index(Request $request)
        {
            $this->ensureCanView();

            // Slug que viene del chip: "fertilidad", "ginecologia", "obstetricia" o vacío
            $catSlug = strtolower($request->query('cat', ''));
            $q       = trim((string) $request->query('q', ''));

            $query = Patient::query()->with('category');

            // Mapeo slug -> nombre real en BD (tabla patient_categories)
            $catMap = [
                'fertilidad'  => 'Fertilidad',
                'ginecologia' => 'Ginecología',
                'obstetricia' => 'Obstetricia',
            ];

            // Filtro por categoría (si se seleccionó una)
            if ($catSlug !== '' && isset($catMap[$catSlug])) {
                $categoryName = $catMap[$catSlug];

                $query->whereHas('category', function ($q2) use ($categoryName) {
                    $q2->where('name', $categoryName);
                });
            }

            // Filtro de búsqueda rápida: nombre, apellidos, correo o teléfono
            if ($q !== '') {
                $cleanPhone = preg_replace('/\D+/', '', $q);

                $query->where(function ($qq) use ($q, $cleanPhone) {
                    $qq->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name',  'like', "%{$q}%")
                    ->orWhere('email',      'like', "%{$q}%")
                    ->orWhere('phone',      'like', "%{$cleanPhone}%");
                });
            }

            // Paginación
            $patients = $query->orderBy('created_at', 'desc')->paginate(10);

            // Categorías con conteo total (no filtrado, solo para los chips)
            $categories = PatientCategory::withCount('patients')
                ->orderBy('name')
                ->get();

            // Para la vista
            $cat = $catSlug;

            return view('patients.index', compact('patients', 'categories', 'cat', 'q'));
        }

    // GET /admin/pacientes/search
    public function search(Request $request)
    {
        // Reutilizamos la lógica de index
        return $this->index($request);
    }

    // GET /admin/pacientes/by-category/{slug}
    // (por si en algún punto se llega a usar esta ruta directamente)
    public function byCategory(Request $request, string $slug)
    {
        // Simplemente “inyectamos” el cat en el request y reutilizamos index
        $request->merge(['cat' => $slug]);
        return $this->index($request);
    }

    /* =========================================================
     *  Crear paciente
     * ========================================================= */

    // POST /admin/pacientes  (solo ADMIN)
    public function store(Request $request)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'first_name'   => ['required', 'string', 'max:120'],
            'last_name'    => ['required', 'string', 'max:120'],
            'birth_date'   => ['nullable', 'date'],
            'sex'          => ['required', Rule::in(['F', 'M', 'O'])],
            'phone'        => ['nullable', 'string', 'max:30'],
            'email'        => ['nullable', 'email', 'max:150'],
            'address'      => ['nullable', 'string', 'max:500'],
            'category_id'  => ['required', 'exists:patient_categories,id'],
        ]);

        Patient::create($data);

        return redirect()
            ->route('admin.patients.index')
            ->with('ok', 'Paciente creado correctamente.');
    }

    /* =========================================================
     *  Ver / editar / borrar paciente
     * ========================================================= */

    // GET /admin/pacientes/{patient}
    public function show(Patient $patient)
    {
        $this->ensureCanView();

        $patient->load([
            'category',
            'consultations' => function ($q) {
                $q->latest()->with('author'); // últimas primero
            },
        ]);

        return view('admin.consultations.index', compact('patient'));
    }


    
    // GET /admin/pacientes/{patient}/edit
    public function edit(Patient $patient)
    {
        $this->ensureAdmin();

        $categories = PatientCategory::orderBy('name')->get();

        return view('patients.edit', compact('patient', 'categories'));
    }

    // PUT /admin/pacientes/{patient}
    public function update(Request $request, Patient $patient)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'first_name'   => ['required', 'string', 'max:120'],
            'last_name'    => ['required', 'string', 'max:120'],
            'birth_date'   => ['nullable', 'date'],
            'sex'          => ['required', Rule::in(['F', 'M', 'O'])],
            'phone'        => ['nullable', 'string', 'max:30'],
            'email'        => ['nullable', 'email', 'max:150'],
            'address'      => ['nullable', 'string', 'max:500'],
            'category_id'  => ['required', 'exists:patient_categories,id'],
        ]);

        $patient->update($data);

        return redirect()
            ->route('admin.patients.index')
            ->with('ok', 'Paciente actualizado correctamente.');
    }

    // DELETE /admin/pacientes/{patient}
    public function destroy(Patient $patient)
    {
        $this->ensureAdmin();

        $name = $patient->full_name;

        $patient->delete();

        return redirect()
            ->route('admin.patients.index')
            ->with('ok', "Paciente «{$name}» eliminado correctamente.");
    }
}
