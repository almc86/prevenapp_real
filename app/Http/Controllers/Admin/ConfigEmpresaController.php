<?php
// app/Http/Controllers/Admin/ConfigEmpresaController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Categoria;
use App\Models\Documento;
use App\Models\EmpresaCategoria;
use App\Models\EmpresaCategoriaDocumento;
use App\Models\EmpresaCatDocItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ConfigEmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::orderBy('nombre_empresa')->get(['id','nombre_empresa']);
        return view('admin.config_empresas.index', compact('empresas'));
    }

    public function show(Empresa $empresa, Request $request)
    {
        $categoriasSeleccionadas = Categoria::join('empresa_categoria as ec', 'ec.categoria_id','=','categorias.id')
            ->where('ec.empresa_id', $empresa->id)
            ->select('categorias.*','ec.estado as estado_enlace')
            ->orderBy('categorias.nombre')
            ->get();

        $categoriasDisponibles = Categoria::whereNotIn('id', function($q) use ($empresa){
                $q->select('categoria_id')->from('empresa_categoria')->where('empresa_id',$empresa->id);
            })
            ->orderBy('nombre')->get();

        // documentos (catálogo) para agregar – mostramos todos (o podrías filtrar por tipo_documento_id)
        $documentos = Documento::with('tipo')->where('estado',1)->orderBy('nombre')->get();

        return view('admin.config_empresas.show', compact(
            'empresa','categoriasSeleccionadas','categoriasDisponibles','documentos'
        ));
    }

    public function storeCategoria(Request $request, Empresa $empresa)
    {
        $request->validate([
            'categoria_id' => ['required','exists:categorias,id',
                Rule::unique('empresa_categoria','categoria_id')->where(fn($q)=>$q->where('empresa_id',$empresa->id))
            ],
        ],[
            'categoria_id.unique'=>'La categoría ya está asociada a esta empresa.'
        ]);

        EmpresaCategoria::create([
            'empresa_id'   => $empresa->id,
            'categoria_id' => $request->categoria_id,
            'estado'       => true,
        ]);

        return back()->with('success','Categoría agregada');
    }

    public function destroyCategoria(Empresa $empresa, Categoria $categoria)
    {
        EmpresaCategoria::where('empresa_id',$empresa->id)
            ->where('categoria_id',$categoria->id)
            ->delete();

        // También puedes eliminar las configuraciones de documentos bajo esa categoría:
        // EmpresaCategoriaDocumento::where('empresa_id',$empresa->id)->where('categoria_id',$categoria->id)->delete();

        return back()->with('success','Categoría eliminada de la empresa');
    }

    public function storeDocumento(Request $request, Empresa $empresa, Categoria $categoria)
    {
        $request->validate([
            'documento_id'       => ['required','exists:documentos,id',
                Rule::unique('empresa_categoria_documento','documento_id')
                    ->where(fn($q)=>$q->where('empresa_id',$empresa->id)->where('categoria_id',$categoria->id))
            ],
            'obligatorio'        => 'nullable|boolean',
            'vencimiento_modo'   => ['required', Rule::in(EmpresaCategoriaDocumento::MODOS)],
            'meses_vencimiento'  => 'nullable|integer|min:1|max:120',
            'plantilla'          => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg,webp|max:8192',
        ],[
            'documento_id.unique' => 'Ese documento ya está configurado en esta categoría.'
        ]);

        $path = null;
        if ($request->hasFile('plantilla')) {
            $path = $request->file('plantilla')->store('plantillas', 'public');
        }

        if ($request->vencimiento_modo === 'por_meses' && empty($request->meses_vencimiento)) {
            return back()->withErrors(['meses_vencimiento' => 'Debes indicar los meses de vencimiento.'])->withInput();
        }

        EmpresaCategoriaDocumento::create([
            'empresa_id'        => $empresa->id,
            'categoria_id'      => $categoria->id,
            'documento_id'      => $request->documento_id,
            'obligatorio'       => (bool)$request->obligatorio,
            'vencimiento_modo'  => $request->vencimiento_modo,
            'meses_vencimiento' => $request->vencimiento_modo === 'por_meses' ? (int)$request->meses_vencimiento : null,
            'plantilla_path'    => $path,
            'estado'            => true,
        ]);

        return back()->with('success','Documento agregado a la categoría');
    }

    public function updateDocumento(Request $request, Empresa $empresa, Categoria $categoria, EmpresaCategoriaDocumento $config)
    {
        $request->validate([
            'obligatorio'        => 'nullable|boolean',
            'vencimiento_modo'   => ['required', Rule::in(EmpresaCategoriaDocumento::MODOS)],
            'meses_vencimiento'  => 'nullable|integer|min:1|max:120',
            'plantilla'          => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg,webp|max:8192',
            'estado'             => 'nullable|boolean',
        ]);

        if ($request->hasFile('plantilla')) {
            if ($config->plantilla_path) Storage::disk('public')->delete($config->plantilla_path);
            $config->plantilla_path = $request->file('plantilla')->store('plantillas', 'public');
        }

        $config->obligatorio       = (bool)$request->obligatorio;
        $config->vencimiento_modo  = $request->vencimiento_modo;
        $config->meses_vencimiento = $request->vencimiento_modo === 'por_meses' ? (int)$request->meses_vencimiento : null;
        $config->estado            = $request->has('estado') ? (bool)$request->estado : $config->estado;

        $config->save();

        return back()->with('success','Configuración de documento actualizada');
    }

    public function destroyDocumento(Empresa $empresa, Categoria $categoria, EmpresaCategoriaDocumento $config)
    {
        // Alterna estado (soft config)
        $config->estado = ! $config->estado;
        $config->save();

        return back()->with('success', $config->estado ? 'Documento reactivado' : 'Documento desactivado');
    }

    // ---- Ítems de revisión ----
    public function storeItem(Request $request, EmpresaCategoriaDocumento $config)
    {
        $request->validate([
            'item'        => 'required|string|max:255',
            'obligatorio' => 'nullable|boolean',
            'orden'       => 'nullable|integer|min:1|max:999',
        ]);

        EmpresaCatDocItem::create([
            'empresa_categoria_documento_id' => $config->id,
            'item'        => trim($request->item),
            'obligatorio' => (bool)$request->obligatorio,
            'orden'       => $request->orden ?? 1,
        ]);

        return back()->with('success','Ítem agregado');
    }

    public function updateItem(Request $request, EmpresaCategoriaDocumento $config, EmpresaCatDocItem $item)
    {
        $request->validate([
            'item'        => 'required|string|max:255',
            'obligatorio' => 'nullable|boolean',
            'orden'       => 'nullable|integer|min:1|max:999',
        ]);

        $item->update([
            'item'        => trim($request->item),
            'obligatorio' => (bool)$request->obligatorio,
            'orden'       => $request->orden ?? $item->orden,
        ]);

        return back()->with('success','Ítem actualizado');
    }

    public function destroyItem(EmpresaCategoriaDocumento $config, EmpresaCatDocItem $item)
    {
        $item->delete();
        return back()->with('success','Ítem eliminado');
    }
}
