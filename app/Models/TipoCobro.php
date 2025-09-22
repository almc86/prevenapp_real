<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCobro extends Model
{
    use HasFactory;

    protected $table = 'tipos_cobro';

    protected $fillable = [
        'empresa_principal_id',
        'empresa_contratista_id',
        'tipo_cobro',
        'tipo_pago',
        'activo',
        'observaciones'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function empresaPrincipal()
    {
        return $this->belongsTo(Empresa::class, 'empresa_principal_id');
    }

    public function empresaContratista()
    {
        return $this->belongsTo(Empresa::class, 'empresa_contratista_id');
    }

    public function rangos()
    {
        return $this->hasMany(TipoCobroRango::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function getTipoCobroFormattedAttribute()
    {
        return $this->tipo_cobro === 'uf' ? 'UF' : 'Pesos';
    }

    public function getTipoPagoFormattedAttribute()
    {
        return $this->tipo_pago === 'webpay' ? 'WebPay' : 'Factura';
    }
}
