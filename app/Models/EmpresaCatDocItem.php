<?php

// app/Models/EmpresaCatDocItem.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpresaCatDocItem extends Model
{
    protected $table = 'empresa_cat_doc_items';
    protected $fillable = ['empresa_categoria_documento_id','item','obligatorio','orden'];
    protected $casts = ['obligatorio' => 'boolean'];

    public function configDocumento()
    {
        return $this->belongsTo(EmpresaCategoriaDocumento::class, 'empresa_categoria_documento_id');
    }
}
