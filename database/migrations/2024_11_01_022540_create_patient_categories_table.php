<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('patient_categories', function (Blueprint $table) {
            $table->id();                              // unsigned BIGINT
            $table->string('name')->unique();          // Ginecología, Obstetricia, Fertilidad
            $table->string('code', 20)->unique();      // GIN / OBS / FER (opcional)
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('patient_categories');
    }
};
