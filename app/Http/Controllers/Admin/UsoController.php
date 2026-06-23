<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * "Uso y almacenamiento": muestra al admin del tenant cuánto storage consume su
 * cuenta vs el tope de su plan, el estado de la suscripción y los días de prueba.
 */
class UsoController extends Controller
{
    public function index()
    {
        $cuentaId = auth()->user()->cuenta_id;

        $suscripcion = DB::table('suscripciones')
            ->join('planes', 'suscripciones.plan_id', '=', 'planes.id')
            ->where('suscripciones.cuenta_id', $cuentaId)
            ->orderByDesc('suscripciones.id')
            ->select(
                'suscripciones.estado',
                'suscripciones.trial_ends_at',
                'suscripciones.storage_usado_bytes',
                'planes.nombre as plan_nombre',
                'planes.storage_gb',
                'planes.precio_clp',
                'planes.max_carpetas',
                'planes.max_trabajadores'
            )
            ->first();

        $usado = $suscripcion ? (int) $suscripcion->storage_usado_bytes : 0;
        $topeBytes = $suscripcion ? $suscripcion->storage_gb * (1024 ** 3) : 0;
        $pct = $topeBytes > 0 ? min(100, round($usado / $topeBytes * 100, 1)) : 0;

        $diasTrial = null;
        if ($suscripcion && $suscripcion->estado === 'trialing' && $suscripcion->trial_ends_at) {
            $diasTrial = max(0, (int) round(now()->floatDiffInDays(Carbon::parse($suscripcion->trial_ends_at), false)));
        }

        return view('admin.uso.index', compact('suscripcion', 'usado', 'topeBytes', 'pct', 'diasTrial'));
    }
}
