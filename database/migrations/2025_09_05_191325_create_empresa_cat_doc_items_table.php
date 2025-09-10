<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_empresa_cat_doc_items_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('empresa_cat_doc_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('empresa_categoria_documento_id')
            ->constrained('empresa_categoria_documento')
            ->cascadeOnUpdate()->cascadeOnDelete();
      $table->string('item')->comment('Descripción del ítem a revisar');
      $table->boolean('obligatorio')->default(true);
      $table->unsignedSmallInteger('orden')->default(1);
      $table->timestamps();
    });
  }
  public function down(): void {
    Schema::dropIfExists('empresa_cat_doc_items');
  }
};



