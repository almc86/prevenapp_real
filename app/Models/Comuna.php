<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comuna extends Model
{
    protected $table = 'comunas';         // por defecto ya sería 'comunas'
    protected $fillable = ['nombre','region_id'];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function empresas()
    {
        return $this->hasMany(Empresa::class);
    }
}
