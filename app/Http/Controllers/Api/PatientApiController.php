<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientApiController extends Controller
{
    /**
     * Verifica la API key enviada por el cliente.
     * Se acepta tanto en header X-Fertigyn-Key como en query ?key=...
     */
    protected function checkApiKey(Request $request): void
    {
        $clientKey = $request->header('X-Fertigyn-Key')
            ?? $request->query('key');

        $serverKey = config('services.fertigyn_api.key');

        if (!$serverKey || $clientKey !== $serverKey) {
            abort(401, 'API key inválida o no proporcionada.');
        }
    }

    /**
     * GET /api/patients
     * Regresa una lista básica de pacientes.
     */
    public function index(Request $request)
    {
        $this->checkApiKey($request);

        $patients = Patient::with('category')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get()
            ->map(function (Patient $p) {
                return [
                    'id'       => $p->id,
                    'name'     => trim($p->first_name.' '.$p->last_name),
                    'category' => $p->category->name ?? null,
                    'age'      => $p->age ?? null, // si tienes accessor getAgeAttribute()
                    'email'    => $p->email,
                    'phone'    => $p->phone,
                ];
            });

        return response()->json([
            'status'   => 'ok',
            'count'    => $patients->count(),
            'patients' => $patients,
        ]);
    }

    /**
     * GET /api/patients/{patient}
     * Regresa detalle de un paciente específico.
     */
    public function show(Request $request, Patient $patient)
    {
        $this->checkApiKey($request);

        $patient->load('category', 'consultations');

        $data = [
            'id'       => $patient->id,
            'name'     => trim($patient->first_name.' '.$patient->last_name),
            'category' => $patient->category->name ?? null,
            'age'      => $patient->age ?? null,
            'email'    => $patient->email,
            'phone'    => $patient->phone,
            'address'  => $patient->address,
            'clinical' => [
                'sex'        => $patient->sex,
                'birth_date' => $patient->birth_date,
            ],
            'consultations' => $patient->consultations
                ->sortByDesc('consulted_at')
                ->map(function ($c) {
                    return [
                        'id'          => $c->id,
                        'consulted_at'=> optional($c->consulted_at)->toDateTimeString(),
                        'reason'      => $c->reason,
                        'notes'       => $c->notes,
                        'diagnosis'   => $c->diagnosis,
                        'plan'        => $c->plan,
                    ];
                })
                ->values(),
        ];

        return response()->json([
            'status'  => 'ok',
            'patient' => $data,
        ]);
    }
}
