<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('device_assignments', function (Blueprint $t) {
            // 1️⃣ Elimina la relación anterior si existe
            $t->dropForeign(['user_id']);
            $t->dropColumn('user_id');

            // 2️⃣ Crea la nueva relación con empleados
            $t->foreignId('employee_id')->after('device_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('device_assignments', function (Blueprint $t) {
            $t->dropForeign(['employee_id']);
            $t->dropColumn('employee_id');

            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
        });
    }
};

