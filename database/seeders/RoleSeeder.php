<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder {
    public function run(): void {
        DB::table('roles')->upsert([
            ['name' => 'ADMIN',      'display_name' => 'Doctor',     'created_at'=>now(),'updated_at'=>now()],
            ['name' => 'ENFERMERIA', 'display_name' => 'Enfermería', 'created_at'=>now(),'updated_at'=>now()],
        ], ['name'], ['display_name','updated_at']);
    }
}
