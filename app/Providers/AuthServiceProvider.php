<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     * Registra aquí tus policies si las usas.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // \App\Models\Patient::class => \App\Policies\PatientPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gate para ADMIN
        Gate::define('admin-only', function (User $user) {
            return optional($user->role)->name === 'ADMIN';
        });

        // Gate para ENFERMERIA
        Gate::define('nurse-only', function (User $user) {
            return optional($user->role)->name === 'ENFERMERIA';
        });

        /**
         * (Opcional) ADMIN “todo-poderoso”: si quieres que cualquier Gate/Policy
         * se considere aprobada para ADMIN, deja esto activo.
         * Si NO lo quieres, borra o comenta este bloque.
         */
        Gate::before(function (User $user, string $ability) {
            return optional($user->role)->name === 'ADMIN' ? true : null;
        });
    }
}
