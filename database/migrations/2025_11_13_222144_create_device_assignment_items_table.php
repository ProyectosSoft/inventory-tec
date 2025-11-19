<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('device_assignments', function (Blueprint $t) {

            // Nuevo campo empresa
            if (!Schema::hasColumn('device_assignments', 'company_id')) {
                $t->foreignId('company_id')->after('consecutive')->constrained()->cascadeOnDelete();
            }

            // Reemplazar user_id → employee_id
            if (Schema::hasColumn('device_assignments', 'user_id')) {
                $t->renameColumn('user_id', 'employee_id');
            }

            // Quitar device_id (se moverá a tabla hija)
            if (Schema::hasColumn('device_assignments', 'device_id')) {
                $t->dropForeign(['device_id']);
                $t->dropColumn('device_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('device_assignments', function (Blueprint $t) {
            if (!Schema::hasColumn('device_assignments', 'device_id')) {
                $t->foreignId('device_id')->constrained()->cascadeOnDelete();
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

