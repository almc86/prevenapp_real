<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';

    protected $fillable = ['nombre','descripcion','estado'];

    protected $casts = ['estado' => 'boolean'];

    public function scopeActivas($q) { return $q->where('estado', 1); }
}
