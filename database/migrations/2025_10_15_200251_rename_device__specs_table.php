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
        Schema::rename('device__specs', 'device_specs');
    }

    public function down(): void
    {
        Schema::rename('device_specs', 'device__specs');
    }
};
