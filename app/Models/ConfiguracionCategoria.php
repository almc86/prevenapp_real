<?php
// app/Models/ConfiguracionCategoria.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ConfiguracionCategoria extends Model {
  protected $table = 'configuracion_categoria';
  protected $fillable = ['configuracion_id','categoria_id','estado'];
  protected $casts = ['estado'=>'boolean'];
  public function configuracion(){ return $this->belongsTo(Configuracion::class); }
  public function categoria(){ return $this->belongsTo(Categoria::class); }
}
