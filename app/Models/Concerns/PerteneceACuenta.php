<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Aislamiento multi-tenant centralizado.
 *
 * - Auto-estampa cuenta_id al crear (con la cuenta del usuario logueado), salvo
 *   que sea super-admin (en ese caso el registro queda global / null).
 * - Filtra automáticamente todas las consultas por la cuenta del usuario.
 *
 * El SUPER-ADMIN (es_super_admin) ve TODO (no se aplica el filtro). Sin usuario
 * autenticado (login, consola) tampoco se filtra.
 *
 * Modelos "catálogo compartido" (cargos, categorías, etc.) sobreescriben
 * tenantCompartido() => true, para que el tenant vea los registros GLOBALES
 * (cuenta_id null) ADEMÁS de los suyos.
 *
 * Importante: este trait sólo afecta a Laravel (Eloquent). La app React/NestJS
 * lee las mismas tablas por TypeORM sin este scope — su aislamiento es aparte.
 */
trait PerteneceACuenta
{
    public static function bootPerteneceACuenta(): void
    {
        static::creating(function ($model) {
            $u = auth()->user();
            if (empty($model->cuenta_id) && $u && ! $u->es_super_admin) {
                $model->cuenta_id = $u->cuenta_id;
            }
        });

        static::addGlobalScope('cuenta', function (Builder $builder) {
            $u = auth()->user();
            if (! $u || $u->es_super_admin) {
                return; // super-admin o sin sesión → ve todo
            }

            $tabla = $builder->getModel()->getTable();
            $cuentaId = $u->cuenta_id;

            if (static::tenantCompartido()) {
                $builder->where(function ($q) use ($cuentaId, $tabla) {
                    $q->whereNull("$tabla.cuenta_id")
                      ->orWhere("$tabla.cuenta_id", $cuentaId);
                });
            } else {
                $builder->where("$tabla.cuenta_id", $cuentaId);
            }
        });
    }

    /** Catálogo compartido (incluye registros globales null). Override a true en catálogos. */
    protected static function tenantCompartido(): bool
    {
        return false;
    }
}
