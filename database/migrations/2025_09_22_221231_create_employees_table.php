<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            // Relación con company
            $table->foreignId('company_id')
                  ->constrained()
                  ->cascadeOnUpdate()
                  ->restrictOnDelete(); // evita borrar company con empleados

            // Datos básicos
            $table->string('code')->nullable();       // Código interno
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('document_id')->nullable();// Cédula/NIT
            $table->string('email')->nullable()->index();
            $table->string('phone')->nullable();
            $table->string('position')->nullable();   // Cargo
            $table->string('site')->nullable();       // Sede

            $table->enum('status', ['active','inactive','suspended'])->default('active');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'status']);
            $table->index(['code']);
            $table->index(['document_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
