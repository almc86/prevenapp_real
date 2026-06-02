<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registros_acceso', function (Blueprint $table) {
            $table->id();

            // Trabajador registrado (si aplica)
            $table->unsignedBigInteger('trabajador_id')->nullable();
            $table->unsignedBigInteger('carpeta_id')->nullable();
            $table->unsignedBigInteger('empresa_principal_id')->nullable();

            // Visita externa (si no es trabajador del sistema)
            $table->boolean('es_visita')->default(false);
            $table->string('visita_rut', 12)->nullable();
            $table->string('visita_nombre', 255)->nullable();
            $table->string('visita_empresa', 255)->nullable();
            $table->string('visita_motivo', 500)->nullable();

            // Datos comunes
            $table->enum('tipo', ['entrada', 'salida']);
            $table->dateTime('fecha_hora');
            $table->unsignedBigInteger('registrado_por');
            $table->text('observaciones')->nullable();

            $table->timestamps();

            // Índices
            $table->index('trabajador_id');
            $table->index('empresa_principal_id');
            $table->index('fecha_hora');
            $table->index('tipo');
            $table->index('visita_rut');

            // Foreign keys
            $table->foreign('trabajador_id')->references('id')->on('trabajadores')->onDelete('cascade');
            $table->foreign('carpeta_id')->references('id')->on('carpetas')->onDelete('set null');
            $table->foreign('empresa_principal_id')->references('id')->on('empresas')->onDelete('set null');
            $table->foreign('registrado_por')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registros_acceso');
    }
};
