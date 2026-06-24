<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Marca cuándo el usuario completó (o cerró) el recorrido guiado del dashboard.
     * NULL = nunca lo vio → se le muestra automáticamente en su próximo ingreso.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('tour_dashboard_visto_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('tour_dashboard_visto_at');
        });
    }
};
