<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\PerteneceACuenta;

class TipoDocumento extends Model
{
    use PerteneceACuenta;

    protected static function tenantCompartido(): bool
    {
        return true;
    }

    protected $table = 'tipos_documento';
    protected $fillable = ['nombre', 'descripcion', 'estado'];
    protected $casts = ['estado' => 'boolean'];

    // Ámbitos válidos
    public const TIPOS = ['trabajador','empresa','flota'];
}
