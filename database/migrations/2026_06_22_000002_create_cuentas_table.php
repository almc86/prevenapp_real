<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cuenta = el "tenant" que paga PrevenApp. Una cuenta tiene una suscripción
 * (un plan) y abajo maneja N empresas + N usuarios. El owner es el usuario
 * admin que la creó al registrarse.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuentas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->unsignedBigInteger('owner_user_id')->nullable();
            $table->enum('estado', ['trialing', 'activa', 'morosa', 'cancelada', 'solo_lectura'])
                  ->default('trialing');
            $table->timestamps();

            $table->foreign('owner_user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuentas');
    }
};
