<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_empresa_categoria_documento_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('empresa_categoria_documento', function (Blueprint $table) {
      $table->id();

      $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('categoria_id')->constrained('categorias')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('documento_id')->constrained('documentos')->cascadeOnUpdate()->restrictOnDelete();

      $table->boolean('obligatorio')->default(false);

      // vencimiento: por_documento | por_meses | sin_vencimiento
      $table->string('vencimiento_modo', 20)->default('por_documento');
      $table->unsignedInteger('meses_vencimiento')->nullable(); // requerido si modo=por_meses

      // plantilla modelo (se usa luego para IA)
      $table->string('plantilla_path')->nullable();

      $table->boolean('estado')->default(true);
      $table->timestamps();

      $table->unique(['empresa_id','categoria_id','documento_id'], 'empresa_cat_doc_unique');
    });
  }
  public function down(): void {
    Schema::dropIfExists('empresa_categoria_documento');
  }
};

