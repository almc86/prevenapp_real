<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Fase 3 del onboarding: cuando vence el trial de 14 días, la suscripción (y su
 * cuenta) pasan a 'solo_lectura'. El enforcement de NestJS ya bloquea las subidas
 * en ese estado; el usuario sigue viendo/descargando sus datos.
 *
 * Programado a diario en App\Console\Kernel. Idempotente: solo toca las que están
 * en 'trialing' con trial_ends_at en el pasado.
 */
class ProcesarVencimientosSuscripciones extends Command
{
    protected $signature = 'suscripciones:procesar-vencimientos';

    protected $description = 'Pasa a solo_lectura las suscripciones cuyo período de prueba venció';

    public function handle(): int
    {
        $ahora = now();

        $vencidas = DB::table('suscripciones')
            ->where('estado', 'trialing')
            ->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '<', $ahora)
            ->get(['id', 'cuenta_id']);

        if ($vencidas->isEmpty()) {
            $this->info('No hay trials vencidos para procesar.');
            return self::SUCCESS;
        }

        $suscIds = $vencidas->pluck('id')->all();
        $cuentaIds = $vencidas->pluck('cuenta_id')->unique()->all();

        DB::transaction(function () use ($suscIds, $cuentaIds, $ahora) {
            DB::table('suscripciones')->whereIn('id', $suscIds)->update([
                'estado' => 'solo_lectura',
                'updated_at' => $ahora,
            ]);

            DB::table('cuentas')->whereIn('id', $cuentaIds)->update([
                'estado' => 'solo_lectura',
                'updated_at' => $ahora,
            ]);
        });

        $this->info('Suscripciones pasadas a solo_lectura: ' . count($suscIds));

        return self::SUCCESS;
    }
}
