<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('device_assignments', function (Blueprint $table) {
            // Se agrega el campo consecutivo si no existe
            if (!Schema::hasColumn('device_assignments', 'consecutive')) {
                $table->string('consecutive', 50)
                    ->nullable()
                    ->after('id')
                    ->comment('Número consecutivo de asignación por empresa');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('device_assignments', function (Blueprint $table) {
            if (Schema::hasColumn('device_assignments', 'consecutive')) {
                $table->dropColumn('consecutive');
            }
        });
    }
};
