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

    // categorías ya asociadas (solo las de ámbito trabajador)
    $catsSel = Categoria::join('configuracion_categoria as cc','cc.categoria_id','=','categorias.id')
              ->where('cc.configuracion_id',$config->id)
              ->where('categorias.ambito', 'trabajador')
              ->select('categorias.*','cc.estado as estado_enlace')
              ->orderBy('categorias.nombre')->get();

    // categorías disponibles (solo las de ámbito trabajador que no están asociadas)
    $catsDisp = Categoria::whereNotIn('id', function($q) use ($config) {
                  $q->select('categoria_id')->from('configuracion_categoria')->where('configuracion_id',$config->id);
                })
                ->where('ambito', 'trabajador')
                ->where('estado', 1)
                ->orderBy('nombre')->get();

    // Solo documentos de tipo "trabajador" para las categorías
    $documentos = Documento::with('tipo')
        ->where('estado',1)
        ->whereHas('tipo', function($q) {
            $q->where('nombre', 'trabajador');
        })
        ->orderBy('nombre')->get();

    // Contar documentos configurados por ámbito
    $countEmpresa = ConfiguracionCategoriaDocumento::where('configuracion_id', $config->id)
        ->where('estado', 1)
        ->whereHas('documento.tipo', function($q) {
            $q->where('nombre', 'empresa');
        })
        ->count();

    $countFlota = ConfiguracionCategoriaDocumento::where('configuracion_id', $config->id)
        ->where('estado', 1)
        ->whereHas('documento.tipo', function($q) {
            $q->where('nombre', 'flota');
        })
        ->count();

    return view('admin.config_empresas.show', compact('empresa','config','catsSel','catsDisp','documentos','countEmpresa','countFlota'));
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

  public function storeDocumentoSimple(Request $r, Empresa $empresa, Configuracion $config) {
    $r->validate([
      'documento_id' => ['required','exists:documentos,id'],
      'ambito'       => ['required', 'string', 'in:empresa,flota,trabajador'],
      'obligatorio'       => 'nullable|boolean',
      'vencimiento_modo'  => ['required', Rule::in(ConfiguracionCategoriaDocumento::MODOS)],
      'meses_vencimiento' => 'nullable|integer|min:1|max:120',
      'plantilla'         => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg,webp|max:8192',
    ]);

    // Obtener o crear categoría por defecto según el ámbito
    $nombreCategoria = $r->ambito === 'empresa' ? 'Documentos de Empresa' : 'Documentos de Flota';
    $categoria = Categoria::firstOrCreate(
      ['nombre' => $nombreCategoria],
      [
        'descripcion' => 'Categoría automática para documentos de ' . $r->ambito,
        'estado' => true
      ]
    );

    // Asegurar que la categoría esté asociada a esta configuración
    ConfiguracionCategoria::firstOrCreate([
      'configuracion_id' => $config->id,
      'categoria_id' => $categoria->id,
    ], [
      'estado' => true
    ]);

    // Validar que el documento no esté ya agregado
    $exists = ConfiguracionCategoriaDocumento::where('configuracion_id', $config->id)
        ->where('documento_id', $r->documento_id)
        ->exists();

    if ($exists) {
      return back()->withErrors(['documento_id'=>'Este documento ya está agregado a esta configuración'])->withInput();
    }

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

    // Redirigir según el ámbito
    $redirectRoute = $r->ambito === 'empresa' ? 'admin.config-empresas.globales' : 'admin.config-empresas.flota';
    return redirect()->route($redirectRoute, [$empresa, $config])->with('success','Documento agregado correctamente');
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

  public function showGlobales(Empresa $empresa, Configuracion $config) {
    // Asegurar que la configuración pertenece a la empresa
    if ($config->empresa_id !== $empresa->id) {
        abort(404);
    }

    $ambito = 'empresa';
    $titulo = 'Configurar Globales (Empresa)';

    // categorías ya asociadas (solo las de ámbito empresa)
    $catsSel = Categoria::join('configuracion_categoria as cc','cc.categoria_id','=','categorias.id')
              ->where('cc.configuracion_id',$config->id)
              ->where('categorias.ambito', $ambito)
              ->select('categorias.*','cc.estado as estado_enlace')
              ->orderBy('categorias.nombre')->get();

    $catsDisp = Categoria::whereNotIn('id', function($q) use ($config) {
                  $q->select('categoria_id')->from('configuracion_categoria')->where('configuracion_id',$config->id);
                })
                ->where('ambito', $ambito)
                ->where('estado', 1)
                ->orderBy('nombre')->get();

    // Solo documentos del ámbito empresa
    $documentos = Documento::with('tipo')
        ->where('estado',1)
        ->whereHas('tipo', function($q) use ($ambito) {
            $q->where('nombre', $ambito);
        })
        ->orderBy('nombre')->get();

    // Contar documentos configurados de este ámbito
    $countDocumentos = ConfiguracionCategoriaDocumento::where('configuracion_id', $config->id)
        ->where('estado', 1)
        ->whereHas('documento.tipo', function($q) use ($ambito) {
            $q->where('nombre', $ambito);
        })
        ->count();

    return view('admin.config_empresas.show_ambito', compact('empresa','config','catsSel','catsDisp','documentos','ambito','titulo','countDocumentos'));
  }

  public function showFlota(Empresa $empresa, Configuracion $config) {
    // Asegurar que la configuración pertenece a la empresa
    if ($config->empresa_id !== $empresa->id) {
        abort(404);
    }

    $ambito = 'flota';
    $titulo = 'Configurar Flota';

    // categorías ya asociadas (solo las de ámbito flota)
    $catsSel = Categoria::join('configuracion_categoria as cc','cc.categoria_id','=','categorias.id')
              ->where('cc.configuracion_id',$config->id)
              ->where('categorias.ambito', $ambito)
              ->select('categorias.*','cc.estado as estado_enlace')
              ->orderBy('categorias.nombre')->get();

    $catsDisp = Categoria::whereNotIn('id', function($q) use ($config) {
                  $q->select('categoria_id')->from('configuracion_categoria')->where('configuracion_id',$config->id);
                })
                ->where('ambito', $ambito)
                ->where('estado', 1)
                ->orderBy('nombre')->get();

    // Solo documentos del ámbito flota
    $documentos = Documento::with('tipo')
        ->where('estado',1)
        ->whereHas('tipo', function($q) use ($ambito) {
            $q->where('nombre', $ambito);
        })
        ->orderBy('nombre')->get();

    // Contar documentos configurados de este ámbito
    $countDocumentos = ConfiguracionCategoriaDocumento::where('configuracion_id', $config->id)
        ->where('estado', 1)
        ->whereHas('documento.tipo', function($q) use ($ambito) {
            $q->where('nombre', $ambito);
        })
        ->count();

    return view('admin.config_empresas.show_ambito', compact('empresa','config','catsSel','catsDisp','documentos','ambito','titulo','countDocumentos'));
  }
}
