<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('configuracion_categoria_documento', function (Blueprint $t) {
      $t->id();
      $t->foreignId('configuracion_id')->constrained('configuraciones')->cascadeOnUpdate()->cascadeOnDelete();
      $t->foreignId('categoria_id')->constrained('categorias')->cascadeOnUpdate()->restrictOnDelete();
      $t->foreignId('documento_id')->constrained('documentos')->cascadeOnUpdate()->restrictOnDelete();

      $t->boolean('obligatorio')->default(false);
      $t->string('vencimiento_modo',20)->default('por_documento'); // por_documento | por_meses | sin_vencimiento
      $t->unsignedInteger('meses_vencimiento')->nullable();
      $t->string('plantilla_path')->nullable();

      $t->boolean('estado')->default(true);
      $t->timestamps();

      $t->unique(['configuracion_id','categoria_id','documento_id'], 'cfg_cat_doc_unique');
    });
  }
  public function down(): void { Schema::dropIfExists('configuracion_categoria_documento'); }
};
