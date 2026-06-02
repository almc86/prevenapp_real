<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lista_negra', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('empresa_principal_id');
            $table->enum('tipo', ['persona', 'vehiculo']);

            // Identificadores: solo uno aplica según `tipo`
            $table->string('rut', 12)->nullable();
            $table->string('patente', 10)->nullable();

            $table->string('nombre', 255)->nullable();
            $table->text('motivo');

            // Array JSON con keys S3 de evidencia
            $table->json('evidencia_keys')->nullable();

            $table->unsignedBigInteger('bloqueado_por');
            $table->dateTime('fecha_bloqueo');

            $table->boolean('activo')->default(true);
            $table->unsignedBigInteger('desbloqueado_por')->nullable();
            $table->dateTime('fecha_desbloqueo')->nullable();
            $table->text('motivo_desbloqueo')->nullable();

            $table->timestamps();

            // Índices para lookups frecuentes (al registrar entrada)
            $table->index(['empresa_principal_id', 'tipo', 'activo'], 'idx_lista_negra_lookup');
            $table->index('rut', 'idx_lista_negra_rut');
            $table->index('patente', 'idx_lista_negra_patente');

            $table->foreign('empresa_principal_id')
                ->references('id')->on('empresas')
                ->onDelete('cascade');

            $table->foreign('bloqueado_por')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('desbloqueado_por')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lista_negra');
    }
};
