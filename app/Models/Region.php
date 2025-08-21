<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'regiones';        // <-- importante
    protected $fillable = ['nombre'];

    public function comunas()
    {
        return $this->hasMany(Comuna::class);
    }

    public function empresas()
    {
        return $this->hasMany(Empresa::class);
    }
}

