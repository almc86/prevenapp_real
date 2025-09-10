<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_documentos_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');
            $table->string('descripcion')->nullable();

            // Relación al ámbito (tipos_documento: trabajador|empresa|flota)
            $table->foreignId('tipo_documento_id')
                  ->constrained('tipos_documento')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            // Estado + trazabilidad
            $table->boolean('estado')->default(true);     // activo/inactivo
            $table->timestamp('desactivado_at')->nullable();

            // Usuario que lo crea
            $table->foreignId('created_by')
                  ->constrained('users')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->timestamps();

            // Si quieres evitar duplicados por tipo:
            $table->unique(['nombre','tipo_documento_id'], 'documentos_nombre_tipo_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
