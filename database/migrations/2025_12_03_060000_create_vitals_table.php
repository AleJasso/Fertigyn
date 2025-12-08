<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vitals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('consultation_id')
                ->constrained('consultations')  // <-- antes decía 'consultas'
                ->cascadeOnDelete();

            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();

            $table->foreignId('measured_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('measured_at')->nullable();

            $table->decimal('weight_kg', 5, 2)->nullable();
            $table->decimal('height_m', 3, 2)->nullable();
            $table->unsignedSmallInteger('heart_rate')->nullable();
            $table->unsignedSmallInteger('resp_rate')->nullable();
            $table->unsignedSmallInteger('systolic')->nullable();
            $table->unsignedSmallInteger('diastolic')->nullable();
            $table->decimal('temperature_c', 4, 1)->nullable();
            $table->decimal('spo2', 4, 1)->nullable();
            $table->unsignedSmallInteger('glucose_mg_dl')->nullable();

            $table->enum('bp_arm', ['left','right'])->nullable();
            $table->enum('bp_posture', ['sitting','standing','supine'])->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['patient_id', 'consultation_id']);
            $table->index(['measured_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vitals');
    }
};
