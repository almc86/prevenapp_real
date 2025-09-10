<?php
// app/Models/EmpresaCategoriaDocumento.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpresaCategoriaDocumento extends Model
{
    protected $table = 'empresa_categoria_documento';
    protected $fillable = [
        'empresa_id','categoria_id','documento_id',
        'obligatorio','vencimiento_modo','meses_vencimiento',
        'plantilla_path','estado'
    ];
    protected $casts = [
        'obligatorio' => 'boolean',
        'estado' => 'boolean',
    ];

    public const MODOS = ['por_documento','por_meses','sin_vencimiento'];

    public function empresa()   { return $this->belongsTo(Empresa::class); }
    public function categoria() { return $this->belongsTo(Categoria::class); }
    public function documento() { return $this->belongsTo(Documento::class); }
    public function items()     { return $this->hasMany(EmpresaCatDocItem::class, 'empresa_categoria_documento_id'); }
}
