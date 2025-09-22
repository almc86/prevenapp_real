<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tipo_cobro_rangos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_cobro_id')->constrained('tipos_cobro')->onDelete('cascade');
            $table->integer('trabajadores_desde');
            $table->integer('trabajadores_hasta');
            $table->decimal('monto', 12, 2);
            $table->timestamps();

            $table->index(['tipo_cobro_id', 'trabajadores_desde', 'trabajadores_hasta']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_cobro_rangos');
    }
};
