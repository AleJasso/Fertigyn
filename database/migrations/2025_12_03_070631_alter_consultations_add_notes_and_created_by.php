<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ya no hacemos nada aquí. La estructura final se define
        // en create_consultations_table.
    }

    public function down(): void
    {
        // Tampoco hacemos nada al revertir.
    }
};
