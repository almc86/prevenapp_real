<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\PerteneceACuenta;

class Configuracion extends Model {
  use PerteneceACuenta;

  protected $table = 'configuraciones';
  protected $fillable = ['empresa_id','nombre','descripcion','estado','created_by','modo_trabajador'];
  protected $casts = ['estado'=>'boolean'];

  const MODOS_TRABAJADOR = ['por_categoria', 'por_cargo'];
  public function empresa(){ return $this->belongsTo(Empresa::class); }
  public function creator(){ return $this->belongsTo(User::class, 'created_by'); }
  public function categorias(){ return $this->hasMany(ConfiguracionCategoria::class); }
  public function documentos(){ return $this->hasMany(ConfiguracionCategoriaDocumento::class); }
}
