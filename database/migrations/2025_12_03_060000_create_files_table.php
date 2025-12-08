<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('files', function (Blueprint $table) {
        $table->id();

        $table->foreignId('patient_id')
            ->constrained('patients')
            ->cascadeOnDelete();

        $table->string('path');
        $table->string('original_name');
        $table->string('mime', 100)->nullable();
        $table->unsignedBigInteger('size_bytes')->nullable();
        $table->string('description', 255)->nullable();
        
        $table->foreignId('created_by')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        $table->timestamps();

        $table->index(['patient_id']);
    });

    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
