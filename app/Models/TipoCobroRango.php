<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCobroRango extends Model
{
    use HasFactory;

    protected $table = 'tipo_cobro_rangos';

    protected $fillable = [
        'tipo_cobro_id',
        'trabajadores_desde',
        'trabajadores_hasta',
        'monto'
    ];

    protected $casts = [
        'monto' => 'decimal:2',
    ];

    public function tipoCobro()
    {
        return $this->belongsTo(TipoCobro::class);
    }

    public function getRangoFormattedAttribute()
    {
        return "{$this->trabajadores_desde} - {$this->trabajadores_hasta} trabajadores";
    }

    public function getMontoFormattedAttribute()
    {
        return '$' . number_format($this->monto, 0, ',', '.');
    }
}
