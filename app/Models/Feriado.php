<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feriado extends Model
{
    use HasFactory;

    // Nombre de la tabla (si no sigue la convención plural de Laravel)
    protected $table = 'feriados';

    // Nombre de la primary key
    protected $primaryKey = 'id_feriado';

    // Como tu PK no es auto_increment, avísale a Laravel
    public $incrementing = false;

    // El tipo de la PK
    protected $keyType = 'int';

    // Si no tienes timestamps (created_at, updated_at)
    public $timestamps = false;

    // Campos que puedes asignar masivamente
    protected $fillable = [
        'id_feriado',
        'fecha_feriado_date',
        'descripcion_feriado',
        'es_empresarial',
    ];

    // Opcional: casts para que Laravel convierta automáticamente tipos
    protected $casts = [
        'fecha_feriado_date' => 'date',
        'es_empresarial'     => 'boolean',
    ];
}