<?php
use App\Http\Controllers\Api\PatientApiController;

use Illuminate\Support\Facades\Route;

Route::get('/patients', [PatientApiController::class, 'index']);
Route::get('/patients/{patient}', [PatientApiController::class, 'show']);
Route::get('/ping', function () {
    return response()->json(['ok' => true, 'api' => 'pong']);
});
