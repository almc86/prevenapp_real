<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model {
  protected $table = 'configuraciones';
  protected $fillable = ['empresa_id','nombre','descripcion','estado','created_by'];
  protected $casts = ['estado'=>'boolean'];
  public function empresa(){ return $this->belongsTo(Empresa::class); }
  public function creator(){ return $this->belongsTo(User::class, 'created_by'); }
  public function categorias(){ return $this->hasMany(ConfiguracionCategoria::class); }
  public function documentos(){ return $this->hasMany(ConfiguracionCategoriaDocumento::class); }
}
