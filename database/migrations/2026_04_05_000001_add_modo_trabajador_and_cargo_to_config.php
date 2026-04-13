<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Agregar modo_trabajador a configuraciones
        Schema::table('configuraciones', function (Blueprint $table) {
            $table->enum('modo_trabajador', ['por_categoria', 'por_cargo'])
                  ->default('por_categoria')
                  ->after('estado');
        });

        // Agregar cargo_id nullable a configuracion_categoria_documento
        Schema::table('configuracion_categoria_documento', function (Blueprint $table) {
            $table->unsignedBigInteger('cargo_id')->nullable()->after('documento_id');
            $table->foreign('cargo_id')->references('id')->on('cargos')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('configuracion_categoria_documento', function (Blueprint $table) {
            $table->dropForeign(['cargo_id']);
            $table->dropColumn('cargo_id');
        });

        Schema::table('configuraciones', function (Blueprint $table) {
            $table->dropColumn('modo_trabajador');
        });
    }
};
