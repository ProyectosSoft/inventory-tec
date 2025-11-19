<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1️⃣ Asegurar estructura correcta en device_assignments
        Schema::table('device_assignments', function (Blueprint $t) {

            // AGREGAR company_id si no existe
            if (!Schema::hasColumn('device_assignments', 'company_id')) {
                $t->foreignId('company_id')
                    ->after('consecutive')
                    ->constrained()
                    ->cascadeOnDelete();
            }

            // RENOMBRAR user_id → employee_id
            if (Schema::hasColumn('device_assignments', 'user_id')) {
                $t->renameColumn('user_id', 'employee_id');
            }

            // QUITAR device_id (ahora estará en device_assignment_items)
            if (Schema::hasColumn('device_assignments', 'device_id')) {
                $t->dropForeign(['device_id']);
                $t->dropColumn('device_id');
            }
        });

        // 2️⃣ Crear tabla hija: device_assignment_items
        if (!Schema::hasTable('device_assignment_items')) {
            Schema::create('device_assignment_items', function (Blueprint $t) {
                $t->id();

                // FK a CABECERA
                $t->foreignId('assignment_id')
                    ->constrained('device_assignments')
                    ->cascadeOnDelete();

                // FK a device real
                $t->foreignId('device_id')
                    ->constrained()
                    ->cascadeOnDelete();

                // Campos opcionales
                $t->json('specs')->nullable(); // si deseas guardar specs del equipo
                $t->text('notes')->nullable();

                $t->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Borrar tabla hija
        if (Schema::hasTable('device_assignment_items')) {
            Schema::dropIfExists('device_assignment_items');
        }

        // Restaurar estructura original (solo si hace falta)
        Schema::table('device_assignments', function (Blueprint $t) {

            if (!Schema::hasColumn('device_assignments', 'device_id')) {
                $t->foreignId('device_id')->nullable()
                    ->constrained()
                    ->cascadeOnDelete();
            }

            if (Schema::hasColumn('device_assignments', 'employee_id')) {
                $t->renameColumn('employee_id', 'user_id');
            }

            if (Schema::hasColumn('device_assignments', 'company_id')) {
                $t->dropForeign(['company_id']);
                $t->dropColumn('company_id');
            }
        });
    }
};
