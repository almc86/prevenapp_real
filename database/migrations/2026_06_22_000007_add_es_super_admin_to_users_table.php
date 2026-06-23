<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Super-admin de la plataforma: ve TODAS las cuentas (bypassa el scoping de
 * tenant). Los administradores por-tenant NO lo tienen. El admin existente
 * (id 1) se marca como super-admin para conservar la vista global.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('es_super_admin')->default(false)->after('cuenta_id');
        });

        DB::table('users')->where('id', 1)->update(['es_super_admin' => true]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('es_super_admin');
        });
    }
};
