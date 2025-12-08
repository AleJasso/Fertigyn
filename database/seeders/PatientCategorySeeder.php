<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PatientCategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('patient_categories')->upsert([
            ['id'=>1,'name'=>'Ginecología','code'=>'GIN','created_at'=>now(),'updated_at'=>now()],
            ['id'=>2,'name'=>'Obstetricia','code'=>'OBS','created_at'=>now(),'updated_at'=>now()],
            ['id'=>3,'name'=>'Fertilidad','code'=>'FER','created_at'=>now(),'updated_at'=>now()],
        ], ['id'], ['name','code','updated_at']);
    }
}
