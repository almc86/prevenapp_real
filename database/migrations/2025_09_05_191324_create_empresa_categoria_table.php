<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_empresa_categoria_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('empresa_categoria', function (Blueprint $table) {
      $table->id();
      $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnUpdate()->restrictOnDelete();
      $table->foreignId('categoria_id')->constrained('categorias')->cascadeOnUpdate()->restrictOnDelete();
      $table->boolean('estado')->default(true);
      $table->timestamps();

      $table->unique(['empresa_id','categoria_id'], 'empresa_categoria_unique');
    });
  }
  public function down(): void {
    Schema::dropIfExists('empresa_categoria');
  }
};

