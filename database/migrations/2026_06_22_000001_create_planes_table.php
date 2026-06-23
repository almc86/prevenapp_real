<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Catálogo de planes del SaaS. Única fuente de verdad de precios y límites
 * (antes solo vivían como texto en el landing pricing.ts). El enforcement de
 * NestJS lee storage_gb / max_* / flags desde acá.
 *
 * null en max_carpetas / max_trabajadores = ilimitado.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();      // trial | profesional | consultora | empresa
            $table->string('nombre');
            $table->string('tagline')->nullable();
            $table->integer('precio_clp')->default(0);
            $table->integer('precio_usd')->default(0);
            $table->integer('storage_gb');
            $table->integer('max_carpetas')->nullable();
            $table->integer('max_trabajadores')->nullable();
            $table->boolean('flota')->default(false);
            $table->boolean('portal_empresas')->default(false);
            $table->boolean('auditoria')->default(false);
            $table->boolean('api')->default(false);
            $table->boolean('multiusuario')->default(false);
            $table->string('soporte')->default('Comunidad');
            $table->boolean('es_trial')->default(false);
            $table->integer('trial_dias')->default(0);
            $table->integer('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Seed del catálogo, espejo de prevenapp-landing/src/lib/pricing.ts
        $now = now();
        DB::table('planes')->insert([
            [
                'codigo' => 'trial', 'nombre' => 'Prueba gratis', 'tagline' => '14 días, sin tarjeta',
                'precio_clp' => 0, 'precio_usd' => 0, 'storage_gb' => 1,
                'max_carpetas' => 1, 'max_trabajadores' => 15,
                'flota' => true, 'portal_empresas' => false, 'auditoria' => false, 'api' => false,
                'multiusuario' => false, 'soporte' => 'Comunidad',
                'es_trial' => true, 'trial_dias' => 14, 'orden' => 1, 'activo' => true,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'codigo' => 'profesional', 'nombre' => 'Profesional', 'tagline' => 'Para el prevencionista independiente',
                'precio_clp' => 19990, 'precio_usd' => 21, 'storage_gb' => 10,
                'max_carpetas' => 3, 'max_trabajadores' => 50,
                'flota' => true, 'portal_empresas' => false, 'auditoria' => true, 'api' => false,
                'multiusuario' => false, 'soporte' => 'Email',
                'es_trial' => false, 'trial_dias' => 0, 'orden' => 2, 'activo' => true,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'codigo' => 'consultora', 'nombre' => 'Consultora', 'tagline' => 'Para carteras de clientes',
                'precio_clp' => 49990, 'precio_usd' => 54, 'storage_gb' => 50,
                'max_carpetas' => null, 'max_trabajadores' => 250,
                'flota' => true, 'portal_empresas' => true, 'auditoria' => true, 'api' => false,
                'multiusuario' => true, 'soporte' => 'Prioritario',
                'es_trial' => false, 'trial_dias' => 0, 'orden' => 3, 'activo' => true,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'codigo' => 'empresa', 'nombre' => 'Empresa', 'tagline' => 'Para mandantes y grandes operaciones',
                'precio_clp' => 99990, 'precio_usd' => 109, 'storage_gb' => 200,
                'max_carpetas' => null, 'max_trabajadores' => null,
                'flota' => true, 'portal_empresas' => true, 'auditoria' => true, 'api' => true,
                'multiusuario' => true, 'soporte' => 'Dedicado',
                'es_trial' => false, 'trial_dias' => 0, 'orden' => 4, 'activo' => true,
                'created_at' => $now, 'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('planes');
    }
};
