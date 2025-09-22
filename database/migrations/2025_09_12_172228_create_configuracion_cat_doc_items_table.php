<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('configuracion_cat_doc_items', function (Blueprint $t) {
      $t->id();
      $t->foreignId('configuracion_categoria_documento_id')
        ->constrained('configuracion_categoria_documento')
        ->cascadeOnUpdate()->cascadeOnDelete();
      $t->string('item');
      $t->boolean('obligatorio')->default(true);
      $t->unsignedSmallInteger('orden')->default(1);
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('configuracion_cat_doc_items'); }
};
