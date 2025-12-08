<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date')->nullable();
            $table->enum('sex', ['F','M','O'])->default('F');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained('patient_categories')
                ->nullOnDelete();

            // antecedentes generales
            $table->text('allergies')->nullable();
            $table->text('medical_history')->nullable();   // crónicos, quirúrgicos
            $table->text('gyneco_obst_history')->nullable(); // menarca, FUM, G,P,C,A, etc.

            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('patients');
    }
};
