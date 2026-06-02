<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tipo de empresa (integrada vs standalone autogestionada)
        Schema::table('empresas', function (Blueprint $table) {
            if (!Schema::hasColumn('empresas', 'tipo')) {
                $table->string('tipo', 20)->default('integrada')->after('hora_cierre');
            }
        });

        // 2. Catálogo de jornadas por empresa
        Schema::create('jornadas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->string('nombre', 100);
            $table->string('hora_entrada', 5);   // HH:MM
            $table->string('hora_salida', 5);    // HH:MM
            $table->boolean('cruza_medianoche')->default(false);
            $table->unsignedSmallInteger('tolerancia_minutos')->default(15);
            $table->string('color', 7)->default('#3b82f6'); // hex
            $table->boolean('activa')->default(true);
            $table->timestamps();

            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->index('empresa_id');
        });

        // 3. Asignaciones jornada × día × trabajador (planificación)
        Schema::create('asignaciones_jornada', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trabajador_id');
            $table->unsignedBigInteger('jornada_id')->nullable();
            $table->date('fecha');
            $table->enum('tipo', ['planificada', 'permiso', 'vacaciones', 'feriado', 'falta', 'libre'])
                  ->default('planificada');
            $table->string('observacion', 500)->nullable();
            $table->unsignedBigInteger('creado_por')->nullable();
            $table->timestamps();

            $table->foreign('trabajador_id')->references('id')->on('trabajadores')->onDelete('cascade');
            $table->foreign('jornada_id')->references('id')->on('jornadas')->nullOnDelete();
            $table->foreign('creado_por')->references('id')->on('users')->nullOnDelete();
            $table->unique(['trabajador_id', 'fecha']);
            $table->index('fecha');
        });

        // 4. Mejoras a registros_acceso para marcajes manuales/auditados
        Schema::table('registros_acceso', function (Blueprint $table) {
            if (!Schema::hasColumn('registros_acceso', 'manual')) {
                $table->boolean('manual')->default(false)->after('observaciones');
            }
            if (!Schema::hasColumn('registros_acceso', 'autorizado_por')) {
                $table->unsignedBigInteger('autorizado_por')->nullable()->after('manual');
                $table->foreign('autorizado_por')->references('id')->on('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('registros_acceso', 'asignacion_id')) {
                $table->unsignedBigInteger('asignacion_id')->nullable()->after('autorizado_por');
                $table->foreign('asignacion_id')->references('id')->on('asignaciones_jornada')->nullOnDelete();
            }
            if (!Schema::hasColumn('registros_acceso', 'motivo_correccion')) {
                $table->text('motivo_correccion')->nullable()->after('asignacion_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('registros_acceso', function (Blueprint $table) {
            if (Schema::hasColumn('registros_acceso', 'asignacion_id')) {
                $table->dropForeign(['asignacion_id']);
                $table->dropColumn('asignacion_id');
            }
            if (Schema::hasColumn('registros_acceso', 'autorizado_por')) {
                $table->dropForeign(['autorizado_por']);
                $table->dropColumn('autorizado_por');
            }
            if (Schema::hasColumn('registros_acceso', 'motivo_correccion')) {
                $table->dropColumn('motivo_correccion');
            }
            if (Schema::hasColumn('registros_acceso', 'manual')) {
                $table->dropColumn('manual');
            }
        });

        Schema::dropIfExists('asignaciones_jornada');
        Schema::dropIfExists('jornadas');

        Schema::table('empresas', function (Blueprint $table) {
            if (Schema::hasColumn('empresas', 'tipo')) {
                $table->dropColumn('tipo');
            }
        });
    }
};
