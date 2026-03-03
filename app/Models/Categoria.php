<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';

    protected $fillable = ['nombre','descripcion','ambito','estado'];

    protected $casts = ['estado' => 'boolean'];

    // Constantes para los ámbitos
    const AMBITO_TRABAJADOR = 'trabajador';
    const AMBITO_EMPRESA = 'empresa';
    const AMBITO_FLOTA = 'flota';

    const AMBITOS = [
        self::AMBITO_TRABAJADOR => 'Trabajador',
        self::AMBITO_EMPRESA => 'Empresa (Global)',
        self::AMBITO_FLOTA => 'Flota',
    ];

    public function scopeActivas($q) { return $q->where('estado', 1); }
}
