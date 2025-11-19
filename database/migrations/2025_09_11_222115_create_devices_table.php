<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $t) {
            $t->id();

            // Empresa
            $t->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            // Tipo dinámico (catálogo device_types)
            $t->foreignId('device_type_id')
                ->constrained('device_types')
                ->restrictOnDelete();

            // Estado operativo
            $t->enum('status', ['active', 'in_repair', 'lost', 'retired'])
                ->default('active');

            // Identificador interno del activo
            $t->string('asset_tag')->nullable()->unique();

            // Compra y garantía
            $t->date('purchase_date')->nullable();
            $t->unsignedInteger('warranty_months')->nullable();

            // Observaciones
            $t->text('notes')->nullable();

            $t->timestamps();
            $t->softDeletes();

            // Índices útiles
            $t->index(['company_id', 'asset_tag']);
            $t->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};

