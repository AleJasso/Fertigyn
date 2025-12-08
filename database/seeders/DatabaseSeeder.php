<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1) Tablas base con orden correcto
        $this->call([
            RoleSeeder::class,               // roles (ADMIN, ENFERMERIA, etc.)
            PatientCategorySeeder::class,     // categorías de pacientes (Ginecología, Obstetricia, Fertilidad)
        ]);

        // 2) Semillas fijas (opcional) usando tu seeder existente
        //    Útil si quieres tener sí o sí un admin y una enfermera al hacer --seed
        //    (Si NO quieres duplicar usuarios al usar variables .env, puedes comentar esta línea)
        $this->call([
            AdminAndNurseSeeder::class,
        ]);

        // 3) Semillas condicionales por variables de entorno (opcional)
        //    Útil si deseas crear usuarios con credenciales personalizadas desde .env
        $adminEmail = env('SEED_ADMIN_EMAIL');
        $adminPass  = env('SEED_ADMIN_PASSWORD');

        if ($adminEmail && $adminPass) {
            $adminRole = Role::firstWhere('name', 'ADMIN');

            User::firstOrCreate(
                ['email' => $adminEmail],
                [
                    'name'     => 'Admin Bootstrap',
                    'password' => Hash::make($adminPass),
                    'role_id'  => $adminRole?->id,
                ]
            );
        }

        $nurseEmail = env('SEED_NURSE_EMAIL');
        $nursePass  = env('SEED_NURSE_PASSWORD');

        if ($nurseEmail && $nursePass) {
            $nurseRole = Role::firstWhere('name', 'ENFERMERIA');

            User::firstOrCreate(
                ['email' => $nurseEmail],
                [
                    'name'     => 'Enfermería',
                    'password' => Hash::make($nursePass),
                    'role_id'  => $nurseRole?->id,
                ]
            );
        }
    }
}
