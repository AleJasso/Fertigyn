<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminAndNurseSeeder extends Seeder
{
    public function run(): void
    {
        // Si ya ejecutas RolesSeeder antes, recupera los roles existentes:
        $adminRole = Role::firstWhere('name', 'ADMIN');
        $nurseRole = Role::firstWhere('name', 'ENFERMERIA');

        if (!$adminRole || !$nurseRole) {
            // Evita crear usuarios sin rol válido (proteger FKs).
            $this->command->warn('⚠️ Roles no encontrados. Ejecuta primero RolesSeeder.');
            return;
        }

        // Doctor ADMIN
        $this->createUserIfMissing(
            name: 'Doctor Admin',
            email: 'mauritzio_1113@hotmail.com',
            plainPassword: 'Mauritxi0%20',
            roleId: $adminRole->id
        );

        // Enfermería (solo lectura)
        $this->createUserIfMissing(
            name: 'Enfermera',
            email: 'enf@fertigyn.local',
            plainPassword: 'Punketa24#',
            roleId: $nurseRole->id
        );
    }

    private function createUserIfMissing(string $name, string $email, string $plainPassword, int $roleId): void
    {
        User::firstOrCreate(
            ['email' => $email],
            [
                'name'             => $name,
                'password'         => Hash::make($plainPassword),
                'role_id'          => $roleId,
                'failed_attempts'  => 0,
                'locked_until'     => null,
                'last_activity_at' => now(),
                'remember_token'   => Str::random(10),
            ]
        );
    }
}
