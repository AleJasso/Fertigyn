<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ActivationController;
use App\Http\Controllers\PrivacyController;

use App\Http\Controllers\Admin\PatientsController;
use App\Http\Controllers\Admin\NurseController;
use App\Http\Controllers\Admin\ConsultationsController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PatientFilesController;


Route::get('/', function () {
    return view('landing');
})->name('home');



Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/aviso-de-privacidad', [PrivacyController::class, 'show'])->name('privacy.notice');

Route::get('/password/forgot',        [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email',        [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset',        [ResetPasswordController::class, 'reset'])->name('password.update');


Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', function () {
            return redirect()->route('admin.patients.index');
        })->name('dashboard');

        Route::get('/pacientes',                 [PatientsController::class, 'index'])->name('patients.index');
        Route::post('/pacientes',                [PatientsController::class, 'store'])->name('patients.store');
        Route::get('/pacientes/by-category/{slug}', [PatientsController::class, 'byCategory'])->name('patients.byCategory');
        Route::get('/pacientes/search',          [PatientsController::class, 'search'])->name('patients.search');
        Route::get('/pacientes/{patient}', [\App\Http\Controllers\Admin\PatientsController::class,'show'])->name('patients.show');
        Route::get('/pacientes/{patient}/edit', [\App\Http\Controllers\Admin\PatientsController::class,'edit'])->name('patients.edit');

        Route::get('/pacientes/{patient}', [\App\Http\Controllers\Admin\PatientsController::class,'show'])->name('patients.show');   
        // === Expediente clinico ===
        Route::get('/pacientes/{patient}/consultas', [ConsultationsController::class, 'index'])->name('consultations.index');
        Route::post('/pacientes/{patient}/consultas', [ConsultationsController::class, 'store'])->name('consultations.store');
        Route::put('pacientes/{patient}/consultas/{consultation}', [ConsultationsController::class, 'update'])->name('consultations.update');
        Route::delete('pacientes/{patient}/consultas/{consultation}', [ConsultationsController::class, 'destroy'])->name('consultations.destroy');
        Route::put('/pacientes/{patient}', [\App\Http\Controllers\Admin\PatientsController::class,'update'])->name('patients.update');     
        Route::delete('/pacientes/{patient}', [\App\Http\Controllers\Admin\PatientsController::class,'destroy'])->name('patients.destroy');   
        
        // Archivos del paciente
        Route::post('/pacientes/{patient}/archivos', [PatientFilesController::class, 'store'])->name('patients.files.store');
        Route::delete('/pacientes/{patient}/archivos/{file}', [PatientFilesController::class, 'destroy'])->name('patients.files.destroy');

        // ==== Catálogo de Usuarios ====
        Route::get('/usuarios/crear', [AdminUserController::class, 'formCreate'])->name('users.create');
        Route::post('/usuarios', [AdminUserController::class, 'store'])->name('users.store');
        Route::get('/usuarios', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/usuarios/{user}/editar', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/usuarios/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/usuarios/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        // ==== Catálogo de Roles ====
        Route::get('/roles',            [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/crear',      [RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles',           [RoleController::class, 'store'])->name('roles.store');
        Route::get('/roles/{role}/editar', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}',     [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}',  [RoleController::class, 'destroy'])->name('roles.destroy');
    });


        Route::get('/activar-cuenta/{id}/{hash}', [ActivationController::class, 'activate'])->name('account.activate');

Route::middleware(['auth'])
    ->prefix('enfermeria')
    ->name('nurse.')
    ->group(function () {
        Route::get('/',          [NurseController::class, 'index'])->name('dashboard');
        Route::get('/pacientes', [PatientsController::class, 'index'])->name('patients.index');
    });

