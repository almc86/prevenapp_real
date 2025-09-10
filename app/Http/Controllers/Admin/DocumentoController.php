<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Documento;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class DocumentoController extends Controller
{
    public function index(Request $request)
    {
        $q       = trim((string) $request->input('q'));
        $perPage = (int) $request->input('per_page', 15);
        $estado  = $request->input('estado', ''); // '' todos, '1' activos, '0' inactivos
        $tipoId  = $request->input('tipo_documento_id', ''); // filtro por ámbito
        if (!in_array($perPage, [10,15,25,50,100], true)) $perPage = 15;

        $tipos = TipoDocumento::orderBy('nombre')->get(['id','nombre']);

        $docs = Documento::with(['tipo','creador'])
            ->when($q, fn($qq) => $qq->where(function($w) use ($q){
                $w->where('nombre','like',"%{$q}%")
                  ->orWhere('descripcion','like',"%{$q}%");
            }))
            ->when($estado !== '', fn($qq) => $qq->where('estado', (int)$estado))
            ->when($tipoId !== '', fn($qq) => $qq->where('tipo_documento_id', (int)$tipoId))
            ->orderBy('nombre')
            ->paginate($perPage)
            ->appends($request->query());

        return view('admin.documentos.index', compact('docs','tipos','q','perPage','estado','tipoId'));
    }

    public function create()
    {
        // Solo tipos activos (por si desactivas alguno)
        $tipos = TipoDocumento::where('estado',1)->orderBy('nombre')->get(['id','nombre']);
        return view('admin.documentos.create', compact('tipos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'            => [
                'required','string','max:255',
                Rule::unique('documentos','nombre')
                    ->where(fn($q) => $q->where('tipo_documento_id', $request->tipo_documento_id)),
            ],
            'descripcion'       => 'nullable|string|max:255',
            'tipo_documento_id' => 'required|exists:tipos_documento,id',
        ], [
            'nombre.unique' => 'Ya existe un documento con ese nombre para el ámbito seleccionado.',
        ]);

        Documento::create([
            'nombre'            => trim($request->nombre),
            'descripcion'       => trim((string)$request->descripcion),
            'tipo_documento_id' => (int)$request->tipo_documento_id,
            'estado'            => true,
            'created_by'        => Auth::id(),
        ]);

        return redirect()->route('admin.documentos.index')
            ->with('success','Documento creado correctamente');
    }

    // Usamos destroy para alternar estado (activar / desactivar)
    public function destroy(Documento $documento)
    {
        $documento->estado = ! $documento->estado;
        $documento->desactivado_at = $documento->estado ? null : now();
        $documento->save();

        return redirect()->route('admin.documentos.index')
            ->with('success','Estado actualizado');
    }

    public function edit(Documento $documento)
    {
        // Traemos todos los tipos (aunque alguno esté inactivo) para no “perder” el valor actual
        $tipos = TipoDocumento::orderBy('nombre')->get(['id','nombre']);
        return view('admin.documentos.edit', compact('documento','tipos'));
    }

    public function update(Request $request, Documento $documento)
    {
        $request->validate([
            'nombre'            => [
                'required','string','max:255',
                Rule::unique('documentos','nombre')
                    ->ignore($documento->id)
                    ->where(fn($q) => $q->where('tipo_documento_id', $request->tipo_documento_id)),
            ],
            'descripcion'       => 'nullable|string|max:255',
            'tipo_documento_id' => 'required|exists:tipos_documento,id',
            // “estado” viene desde un switch/checkbox (opcional)
        ], [
            'nombre.unique' => 'Ya existe un documento con ese nombre para el ámbito seleccionado.',
        ]);

        $documento->nombre            = trim($request->nombre);
        $documento->descripcion       = trim((string)$request->descripcion);
        $documento->tipo_documento_id = (int)$request->tipo_documento_id;

        // Manejo de estado + fecha de desactivación
        if ($request->has('estado')) {
            $nuevoEstado = (bool)$request->input('estado');
        } else {
            $nuevoEstado = false; // si el switch no viene marcado
        }

        if ($nuevoEstado !== (bool)$documento->estado) {
            $documento->estado = $nuevoEstado;
            $documento->desactivado_at = $nuevoEstado ? null : now();
        }

        $documento->save();

        return redirect()->route('admin.documentos.index')
            ->with('success','Documento actualizado correctamente');
    }
}
