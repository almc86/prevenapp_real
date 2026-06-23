<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\PerteneceACuenta;

class Documento extends Model
{
    use PerteneceACuenta;

    protected static function tenantCompartido(): bool
    {
        return true;
    }

    protected $table = 'documentos';

    protected $fillable = [
        'nombre','descripcion',
        'tipo_documento_id',
        'estado','desactivado_at',
        'created_by',
    ];

    protected $casts = [
        'estado' => 'boolean',
        'desactivado_at' => 'datetime',
    ];

    public function tipo()
    {
        return $this->belongsTo(\App\Models\TipoDocumento::class, 'tipo_documento_id');
    }

    public function creador()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}
