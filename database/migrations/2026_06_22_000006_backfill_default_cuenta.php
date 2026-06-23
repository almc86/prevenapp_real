<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Retrofit de datos existentes: crea una cuenta "default" y le asigna todos los
 * usuarios y empresas que ya existían (cuenta_id = null), para que ningún dato
 * quede huérfano al introducir el modelo multi-tenant. La cuenta arranca con una
 * suscripción 'activa' en el plan más generoso (empresa) para no auto-limitar al
 * dueño actual del sistema.
 */
return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $cuentaId = DB::table('cuentas')->insertGetId([
            'nombre' => 'Cuenta principal',
            'estado' => 'activa',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Asignar usuarios y empresas existentes a la cuenta default.
        DB::table('users')->whereNull('cuenta_id')->update(['cuenta_id' => $cuentaId]);
        DB::table('empresas')->whereNull('cuenta_id')->update(['cuenta_id' => $cuentaId]);

        // Owner = primer administrador (role_id 1) si existe.
        $adminId = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.name', 'administrador')
            ->value('users.id');

        if ($adminId) {
            DB::table('cuentas')->where('id', $cuentaId)->update(['owner_user_id' => $adminId]);
        }

        // Suscripción default: plan 'empresa', activa, sin trial.
        $planId = DB::table('planes')->where('codigo', 'empresa')->value('id');

        DB::table('suscripciones')->insert([
            'cuenta_id' => $cuentaId,
            'plan_id' => $planId,
            'estado' => 'activa',
            'inicio_at' => $now,
            'storage_usado_bytes' => 0,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    public function down(): void
    {
        // Reversible: limpiar lo sembrado. users/empresas.cuenta_id se anula solo
        // por el nullOnDelete de sus FKs al borrar la cuenta.
        DB::table('suscripciones')->delete();
        DB::table('cuentas')->delete();
    }
};
