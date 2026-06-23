<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cada empresa pertenece a una cuenta (tenant). Nullable para no romper datos
 * existentes; el backfill posterior las asigna a la cuenta default.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->unsignedBigInteger('cuenta_id')->nullable()->after('id');
            $table->foreign('cuenta_id')->references('id')->on('cuentas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropForeign(['cuenta_id']);
            $table->dropColumn('cuenta_id');
        });
    }
};
