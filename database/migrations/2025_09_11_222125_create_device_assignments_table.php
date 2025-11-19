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
        Schema::create('device_assignments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('device_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->timestamp('assigned_at');
            $t->timestamp('returned_at')->nullable(); // null = asignación vigente
            $t->text('notes')->nullable();
            $t->timestamps();
            $t->index(['device_id', 'assigned_at']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('device_assignments');
    }
};
