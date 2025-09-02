<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TipoDocumentoController extends Controller
{
    public function index(Request $request)
    {
        $q       = trim((string) $request->input('q'));
        $perPage = (int) $request->input('per_page', 15);
        $estado  = $request->input('estado', ''); // '' todos, '1' activos, '0' inactivos
        $tipo    = $request->input('tipo', '');   // '' todos, o trabajador|empresa|flota
        if (!in_array($perPage, [10,15,25,50,100], true)) $perPage = 15;

        $tipos = TipoDocumento::query()
            ->when($q, fn($qq) => $qq->where(function($w) use ($q){
                $w->where('nombre','like',"%{$q}%")
                  ->orWhere('descripcion','like',"%{$q}%");
            }))
            ->when($estado !== '', fn($qq) => $qq->where('estado', (int)$estado))
            ->when($tipo !== '' && in_array($tipo, TipoDocumento::TIPOS, true),
                   fn($qq) => $qq->where('nombre', $tipo))
            ->orderBy('nombre')
            ->paginate($perPage)
            ->appends($request->query());

        return view('admin.tipos_documento.index', compact('tipos','q','perPage','estado','tipo'));
    }

    public function create()
    {
        $ambitos = TipoDocumento::TIPOS; // trabajador|empresa|flota
        return view('admin.tipos_documento.create', compact('ambitos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => ['required', Rule::in(TipoDocumento::TIPOS), 'unique:tipos_documento,nombre'],
            'descripcion' => 'nullable|string|max:255',
        ], [
            'nombre.in'     => 'El ámbito debe ser trabajador, empresa o flota.',
            'nombre.unique' => 'Ese ámbito ya fue registrado.',
        ]);

        TipoDocumento::create([
            'nombre'      => $request->nombre,                 // trabajador|empresa|flota
            'descripcion' => (string) $request->descripcion,
            'estado'      => true,
        ]);

        return redirect()->route('admin.tipos-documento.index')
            ->with('success','Tipo de documento creado correctamente');
    }

    // Usamos destroy como "toggle" activar/desactivar
    public function destroy(TipoDocumento $tipos_documento)
    {
        $tipos_documento->estado = ! $tipos_documento->estado;
        $tipos_documento->save();

        return redirect()->route('admin.tipos-documento.index')
            ->with('success', 'Estado actualizado');
    }
}
