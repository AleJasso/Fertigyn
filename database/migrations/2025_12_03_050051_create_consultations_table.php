<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('patient_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->dateTime('consulted_at')->nullable();
            $table->string('reason', 180)->nullable();
            $table->text('notes');   // nota clínica principal

            $table->text('diagnosis')->nullable();
            $table->text('plan')->nullable();

            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('height', 4, 2)->nullable();
            $table->string('blood_pressure', 15)->nullable();
            $table->unsignedTinyInteger('heart_rate')->nullable();
            $table->unsignedTinyInteger('resp_rate')->nullable();
            $table->decimal('temperature', 3, 1)->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
