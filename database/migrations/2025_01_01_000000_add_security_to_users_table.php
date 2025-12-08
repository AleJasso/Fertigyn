<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // si aún no existe, agrega FK a roles
            if (!Schema::hasColumn('users', 'role_id')) {
                $table->foreignId('role_id')
                    ->after('id')
                    ->constrained('roles')
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();
            }

            // columnas de seguridad usadas por el seeder y el login
            if (!Schema::hasColumn('users', 'failed_attempts')) {
                $table->unsignedTinyInteger('failed_attempts')
                      ->default(0)
                      ->after('password');
            }
            if (!Schema::hasColumn('users', 'locked_until')) {
                $table->timestamp('locked_until')->nullable()->after('failed_attempts');
            }
            if (!Schema::hasColumn('users', 'last_activity_at')) {
                $table->timestamp('last_activity_at')->nullable()->after('locked_until');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'last_activity_at')) {
                $table->dropColumn('last_activity_at');
            }
            if (Schema::hasColumn('users', 'locked_until')) {
                $table->dropColumn('locked_until');
            }
            if (Schema::hasColumn('users', 'failed_attempts')) {
                $table->dropColumn('failed_attempts');
            }
            if (Schema::hasColumn('users', 'role_id')) {
                $table->dropConstrainedForeignId('role_id');
            }
        });
    }
};
