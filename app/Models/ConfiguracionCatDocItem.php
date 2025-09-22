<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ConfiguracionCatDocItem extends Model {
  protected $table = 'configuracion_cat_doc_items';
  protected $fillable = ['configuracion_categoria_documento_id','item','obligatorio','orden'];
  protected $casts = ['obligatorio'=>'boolean'];
  public function configDocumento(){ return $this->belongsTo(ConfiguracionCategoriaDocumento::class, 'configuracion_categoria_documento_id'); }
}
