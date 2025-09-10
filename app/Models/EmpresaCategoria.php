<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpresaCategoria extends Model
{
    protected $table = 'empresa_categoria';
    protected $fillable = ['empresa_id','categoria_id','estado'];
    protected $casts = ['estado' => 'boolean'];

    public function empresa()  { return $this->belongsTo(Empresa::class); }
    public function categoria(){ return $this->belongsTo(Categoria::class); }
    public function documentos(){ return $this->hasMany(EmpresaCategoriaDocumento::class, 'categoria_id', 'categoria_id')
                                     ->where('empresa_id', $this->empresa_id); }
}
