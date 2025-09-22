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
        Schema::create('tipos_cobro', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_principal_id')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('empresa_contratista_id')->constrained('empresas')->onDelete('cascade');
            $table->enum('tipo_cobro', ['uf', 'pesos'])->default('pesos');
            $table->enum('tipo_pago', ['webpay', 'factura'])->default('factura');
            $table->boolean('activo')->default(true);
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->unique(['empresa_principal_id', 'empresa_contratista_id'], 'uq_empresa_principal_contratista');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_cobro');
    }
};
