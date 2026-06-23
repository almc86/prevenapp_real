<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Suscripción de una cuenta a un plan, con el estado de cobro y el contador de
 * almacenamiento usado (para enforcement del tope del plan).
 *
 * Los campos flow_* quedan listos para la integración de cobro con Flow.cl
 * (fase posterior), por ahora nullable.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suscripciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cuenta_id');
            $table->unsignedBigInteger('plan_id');
            $table->enum('estado', ['trialing', 'activa', 'morosa', 'cancelada', 'solo_lectura'])
                  ->default('trialing');
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('periodo_actual_fin')->nullable();
            $table->timestamp('inicio_at')->nullable();
            $table->timestamp('cancelada_at')->nullable();
            $table->bigInteger('storage_usado_bytes')->default(0);
            $table->string('flow_customer_id')->nullable();
            $table->string('flow_subscription_token')->nullable();
            $table->timestamps();

            $table->foreign('cuenta_id')->references('id')->on('cuentas')->cascadeOnDelete();
            $table->foreign('plan_id')->references('id')->on('planes');
            $table->index('cuenta_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suscripciones');
    }
};
