<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega cuenta_id a las tablas multi-tenant.
 *
 * Catálogos (cargos, categorias, tipos_documento, feriados, documentos):
 *   cuenta_id null = plantilla GLOBAL sembrada (la ven todos los tenants).
 *   cuenta_id con valor = creado por un tenant (solo lo ve ese tenant).
 *   → Los datos sembrados existentes quedan null = globales. Es lo deseado.
 *
 * Estrictas (configuraciones): pertenecen a un tenant puntual. Se rellenan con
 * la cuenta default (1), ya que todos los datos actuales son de esa cuenta.
 *
 * Idempotente: sólo agrega cuenta_id donde falta (no usa after() porque algunas
 * tablas, p.ej. feriados, no tienen columna 'id').
 */
return new class extends Migration
{
    private array $catalogos = ['cargos', 'categorias', 'tipos_documento', 'feriados', 'documentos'];

    public function up(): void
    {
        foreach ([...$this->catalogos, 'configuraciones'] as $tabla) {
            if (Schema::hasColumn($tabla, 'cuenta_id')) {
                continue;
            }
            Schema::table($tabla, function (Blueprint $table) {
                $table->unsignedBigInteger('cuenta_id')->nullable();
                $table->foreign('cuenta_id')->references('id')->on('cuentas')->nullOnDelete();
            });
        }

        // Las configuraciones existentes pertenecen a la cuenta default.
        DB::table('configuraciones')->whereNull('cuenta_id')->update(['cuenta_id' => 1]);
    }

    public function down(): void
    {
        foreach ([...$this->catalogos, 'configuraciones'] as $tabla) {
            if (! Schema::hasColumn($tabla, 'cuenta_id')) {
                continue;
            }
            Schema::table($tabla, function (Blueprint $table) {
                $table->dropForeign(['cuenta_id']);
                $table->dropColumn('cuenta_id');
            });
        }
    }
};
