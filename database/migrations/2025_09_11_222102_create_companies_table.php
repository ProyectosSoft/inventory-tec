<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');                  // Nombre comercial
            $table->string('legal_name')->nullable();// Razón social
            $table->string('tax_id')->nullable();    // NIT o similar
            $table->string('code', 8)->nullable();// ej. 'EC'
            $table->unique(['code']); // opcional si quieres único global
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country', 2)->nullable();// ISO-3166 (CO, MX, etc.)
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['name']);
            $table->index(['tax_id']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
