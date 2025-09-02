<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('empresa_user', function (Blueprint $table) {
      $table->id();
      $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
      $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
      $table->string('relacion', 20); // principal | contratista | subcontratista
      $table->timestamps();

      $table->unique(['empresa_id','user_id','relacion']); // evita duplicados por tipo
      $table->index(['user_id','relacion']);
    });
  }
  public function down(): void {
    Schema::dropIfExists('empresa_user');
  }
};
