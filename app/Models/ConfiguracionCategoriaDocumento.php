<?php
// app/Models/ConfiguracionCategoriaDocumento.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ConfiguracionCategoriaDocumento extends Model {
    protected $table = 'configuracion_categoria_documento';

    protected $fillable = [
        'configuracion_id',
        'categoria_id',
        'documento_id',
        'obligatorio',
        'vencimiento_modo',
        'meses_vencimiento',
        'plantilla_path',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'obligatorio' => 'boolean',
        'meses_vencimiento' => 'integer'
    ];

    const MODOS = ['por_documento', 'por_meses', 'sin_vencimiento'];

    public function configuracion() {
        return $this->belongsTo(Configuracion::class);
    }

    public function categoria() {
        return $this->belongsTo(Categoria::class);
    }

    public function documento() {
        return $this->belongsTo(Documento::class);
    }

    public function items() {
        return $this->hasMany(ConfiguracionCatDocItem::class, 'configuracion_categoria_documento_id');
    }
}