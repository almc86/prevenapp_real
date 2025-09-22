<?php
// app/Http/Controllers/Admin/ConfigEmpresaController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Empresa, Categoria, Documento, Configuracion, ConfiguracionCategoria, ConfiguracionCategoriaDocumento, ConfiguracionCatDocItem};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ConfigEmpresaController extends Controller
{
  public function index() {
    $empresas = \App\Models\Empresa::orderBy('nombre_empresa')->get(['id','nombre_empresa']);
    return view('admin.config_empresas.index', compact('empresas'));
  }

  public function configsIndex(Empresa $empresa) {
    $configs = Configuracion::where('empresa_id',$empresa->id)->orderBy('nombre')->get();
    return view('admin.config_empresas.configs_index', compact('empresa','configs'));
  }

  public function configsStore(Request $r, Empresa $empresa) {
    $r->validate([
      'nombre' => ['required','string','max:255', Rule::unique('configuraciones','nombre')->where(fn($q)=>$q->where('empresa_id',$empresa->id))],
      'descripcion' => 'nullable|string|max:255',
    ]);
    Configuracion::create([
      'empresa_id' => $empresa->id,
      'nombre'     => trim($r->nombre),
      'descripcion'=> trim((string)$r->descripcion),
      'estado'     => true,
      'created_by' => auth()->id(),
    ]);
    return back()->with('success','Configuración creada');
  }

  public function show(Empresa $empresa, Configuracion $config) {
    // Asegurar que la configuración pertenece a la empresa
    if ($config->empresa_id !== $empresa->id) {
        abort(404);
    }

    // categorías ya asociadas
    $catsSel = Categoria::join('configuracion_categoria as cc','cc.categoria_id','=','categorias.id')
              ->where('cc.configuracion_id',$config->id)
              ->select('categorias.*','cc.estado as estado_enlace')
              ->orderBy('categorias.nombre')->get();

    $catsDisp = Categoria::whereNotIn('id', function($q) use ($config) {
                  $q->select('categoria_id')->from('configuracion_categoria')->where('configuracion_id',$config->id);
                })->orderBy('nombre')->get();

    $documentos = Documento::with('tipo')->where('estado',1)->orderBy('nombre')->get();

    return view('admin.config_empresas.show', compact('empresa','config','catsSel','catsDisp','documentos'));
  }

  public function storeCategoria(Request $r, Empresa $empresa, Configuracion $config) {
    $r->validate([
      'categoria_id' => ['required','exists:categorias,id',
        Rule::unique('configuracion_categoria','categoria_id')->where(fn($q)=>$q->where('configuracion_id',$config->id))
      ]
    ]);
    ConfiguracionCategoria::create([
      'configuracion_id' => $config->id,
      'categoria_id'     => $r->categoria_id,
      'estado'           => true,
    ]);
    return back()->with('success','Categoría agregada');
  }

  public function destroyCategoria(Empresa $empresa, Configuracion $config, Categoria $categoria) {
    ConfiguracionCategoria::where('configuracion_id',$config->id)->where('categoria_id',$categoria->id)->delete();
    // podrías también borrar config de docs bajo esa categoría
    return back()->with('success','Categoría quitada');
  }

  public function storeDocumento(Request $r, Empresa $empresa, Configuracion $config, Categoria $categoria) {
    $r->validate([
      'documento_id' => ['required','exists:documentos,id',
        Rule::unique('configuracion_categoria_documento','documento_id')
          ->where(fn($q)=>$q->where('configuracion_id',$config->id)->where('categoria_id',$categoria->id))
      ],
      'obligatorio'       => 'nullable|boolean',
      'vencimiento_modo'  => ['required', Rule::in(ConfiguracionCategoriaDocumento::MODOS)],
      'meses_vencimiento' => 'nullable|integer|min:1|max:120',
      'plantilla'         => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg,webp|max:8192',
    ]);

    $path = null;
    if ($r->hasFile('plantilla')) $path = $r->file('plantilla')->store('plantillas','public');

    if ($r->vencimiento_modo==='por_meses' && empty($r->meses_vencimiento)) {
      return back()->withErrors(['meses_vencimiento'=>'Indica meses para vencimiento'])->withInput();
    }

    ConfiguracionCategoriaDocumento::create([
      'configuracion_id'  => $config->id,
      'categoria_id'      => $categoria->id,
      'documento_id'      => $r->documento_id,
      'obligatorio'       => (bool)$r->obligatorio,
      'vencimiento_modo'  => $r->vencimiento_modo,
      'meses_vencimiento' => $r->vencimiento_modo==='por_meses' ? (int)$r->meses_vencimiento : null,
      'plantilla_path'    => $path,
      'estado'            => true,
    ]);

    return back()->with('success','Documento agregado');
  }

  public function updateDocumento(Request $r, Empresa $empresa, Configuracion $config, Categoria $categoria, ConfiguracionCategoriaDocumento $cfgdoc) {
    $r->validate([
      'obligatorio'       => 'nullable|boolean',
      'vencimiento_modo'  => ['required', Rule::in(ConfiguracionCategoriaDocumento::MODOS)],
      'meses_vencimiento' => 'nullable|integer|min:1|max:120',
      'plantilla'         => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg,webp|max:8192',
      'estado'            => 'nullable|boolean',
    ]);

    if ($r->hasFile('plantilla')) {
      if ($cfgdoc->plantilla_path) Storage::disk('public')->delete($cfgdoc->plantilla_path);
      $cfgdoc->plantilla_path = $r->file('plantilla')->store('plantillas','public');
    }

    $cfgdoc->obligatorio       = (bool)$r->obligatorio;
    $cfgdoc->vencimiento_modo  = $r->vencimiento_modo;
    $cfgdoc->meses_vencimiento = $r->vencimiento_modo==='por_meses' ? (int)$r->meses_vencimiento : null;
    if ($r->has('estado')) $cfgdoc->estado = (bool)$r->estado;
    $cfgdoc->save();

    return back()->with('success','Documento actualizado');
  }

  public function destroyDocumento(Empresa $empresa, Configuracion $config, Categoria $categoria, ConfiguracionCategoriaDocumento $cfgdoc) {
    $cfgdoc->estado = ! $cfgdoc->estado;
    $cfgdoc->save();
    return back()->with('success', $cfgdoc->estado ? 'Documento reactivado' : 'Documento desactivado');
  }

  public function storeItem(Request $r, ConfiguracionCategoriaDocumento $cfgdoc) {
    $r->validate([
      'item'=>'required|string|max:255','obligatorio'=>'nullable|boolean','orden'=>'nullable|integer|min:1|max:999'
    ]);
    ConfiguracionCatDocItem::create([
      'configuracion_categoria_documento_id'=>$cfgdoc->id,
      'item'=>trim($r->item),
      'obligatorio'=>(bool)$r->obligatorio,
      'orden'=>$r->orden ?? 1,
    ]);
    return back()->with('success','Ítem agregado');
  }

  public function updateItem(Request $r, ConfiguracionCategoriaDocumento $cfgdoc, ConfiguracionCatDocItem $item) {
    $r->validate([
      'item'=>'required|string|max:255','obligatorio'=>'nullable|boolean','orden'=>'nullable|integer|min:1|max:999'
    ]);
    $item->update([
      'item'=>trim($r->item),
      'obligatorio'=>(bool)$r->obligatorio,
      'orden'=>$r->orden ?? $item->orden,
    ]);
    return back()->with('success','Ítem actualizado');
  }

  public function destroyItem(ConfiguracionCategoriaDocumento $cfgdoc, ConfiguracionCatDocItem $item) {
    $item->delete();
    return back()->with('success','Ítem eliminado');
  }
}
