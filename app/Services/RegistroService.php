<?php

namespace App\Services;

use App\Mail\BienvenidaCuenta;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * Lógica única de alta de cuenta (tenant). La usan tanto el registro web
 * (RegisteredUserController) como la API pública del landing (Api\RegistroController),
 * para que no diverjan.
 *
 * Crea, en una transacción: la CUENTA + su usuario ADMINISTRADOR (no super-admin)
 * + la SUSCRIPCIÓN. Plan gratis (precio 0) → activa para siempre; plan pago →
 * trial de 14 días.
 *
 * @param array{name:string,empresa:string,plan:string,email:string,password:string} $data
 */
class RegistroService
{
    public function crearCuenta(array $data): User
    {
        $plan = DB::table('planes')->where('codigo', $data['plan'])->first();
        $esGratis = (int) $plan->precio_clp === 0;

        $user = DB::transaction(function () use ($data, $plan, $esGratis) {
            $rolAdminId = DB::table('roles')->where('name', 'administrador')->value('id');
            $estado = $esGratis ? 'activa' : 'trialing';

            $cuentaId = DB::table('cuentas')->insertGetId([
                'nombre' => $data['empresa'],
                'estado' => $estado,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role_id' => $rolAdminId,
                'cuenta_id' => $cuentaId,
                'activo' => true,
            ]);

            DB::table('cuentas')->where('id', $cuentaId)->update(['owner_user_id' => $user->id]);

            DB::table('suscripciones')->insert([
                'cuenta_id' => $cuentaId,
                'plan_id' => $plan->id,
                'estado' => $estado,
                'trial_ends_at' => $esGratis ? null : now()->addDays(14),
                'inicio_at' => now(),
                'storage_usado_bytes' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $user->assignRole('administrador');

            return $user;
        });

        // Mail de bienvenida (fuera de la transacción; no rompe el registro si falla).
        try {
            Mail::to($user->email)->send(new BienvenidaCuenta(
                nombre: $user->name,
                empresa: $data['empresa'],
                planNombre: $plan->nombre,
                esGratis: $esGratis,
                loginUrl: route('login'),
            ));
        } catch (\Throwable $e) {
            report($e);
        }

        return $user;
    }
}
