<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('configuraciones', function (Blueprint $t) {
      $t->id();
      $t->foreignId('empresa_id')->constrained('empresas')->cascadeOnUpdate()->restrictOnDelete();
      $t->string('nombre'); // p.ej. "Configuración 2025", "Contrato X", etc.
      $t->string('descripcion')->nullable();
      $t->boolean('estado')->default(true);
      $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
      $t->timestamps();
      $t->unique(['empresa_id','nombre']);
    });
  }
  public function down(): void { Schema::dropIfExists('configuraciones'); }
};
