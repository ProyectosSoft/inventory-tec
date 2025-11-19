<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('device_types', function (Blueprint $t) {
            $t->id();
            $t->string('key')->unique();      // ej: pc, laptop, phone...
            $t->string('name');              // ej: "PC", "Celular"
            $t->string('code', 8)->nullable(); // ej. 'LP'
            $t->json('schema');               // definición de grupos/campos
            $t->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('device_types');
    }
};
