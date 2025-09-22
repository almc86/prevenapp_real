<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('configuracion_categoria', function (Blueprint $t) {
      $t->id();
      $t->foreignId('configuracion_id')->constrained('configuraciones')->cascadeOnUpdate()->cascadeOnDelete();
      $t->foreignId('categoria_id')->constrained('categorias')->cascadeOnUpdate()->restrictOnDelete();
      $t->boolean('estado')->default(true);
      $t->timestamps();
      $t->unique(['configuracion_id','categoria_id']);
    });
  }
  public function down(): void { Schema::dropIfExists('configuracion_categoria'); }
};
