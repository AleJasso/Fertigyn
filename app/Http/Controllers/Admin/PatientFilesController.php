<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PatientFilesController extends Controller
{
    protected function ensureAdmin(): void
    {
        $role = auth()->user()?->role?->name ?? null;

        if ($role !== 'ADMIN') {
            abort(403, 'Solo el administrador puede gestionar archivos.');
        }
    }

    public function store(Request $request, Patient $patient)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'file'        => ['required', 'file', 'max:5120'], // 5 MB
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $uploaded = $request->file('file');

        // se guarda en storage/app/public/patients/{id}
        $path = $uploaded->store("patients/{$patient->id}", 'public');

        File::create([
            'patient_id'    => $patient->id,
            'path'          => $path,
            'original_name' => $uploaded->getClientOriginalName(),
            'mime'          => $uploaded->getClientMimeType(),
            'size_bytes'    => $uploaded->getSize(),
            'description'   => $data['description'] ?? null,
            'created_by'    => Auth::id(),
        ]);

        return back()->with('ok', 'Archivo subido correctamente.');
    }

    public function destroy(Patient $patient, File $file)
    {
        $this->ensureAdmin();

        if ($file->patient_id !== $patient->id) {
            abort(404);
        }

        Storage::disk('public')->delete($file->path);
        $file->delete();

        return back()->with('ok', 'Archivo eliminado correctamente.');
    }
}
