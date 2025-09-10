<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoriaController extends Controller
{
    public function index(Request $request)
    {
        $q       = trim((string)$request->input('q'));
        $perPage = (int) $request->input('per_page', 15);
        $estado  = $request->input('estado', ''); // '' todos, '1' activos, '0' inactivos
        if (!in_array($perPage, [10,15,25,50,100], true)) $perPage = 15;

        $categorias = Categoria::query()
            ->when($q, fn($qq) => $qq->where(function($w) use ($q) {
                $w->where('nombre','like',"%{$q}%")
                  ->orWhere('descripcion','like',"%{$q}%");
            }))
            ->when($estado !== '', fn($qq) => $qq->where('estado', (int)$estado))
            ->orderBy('nombre')
            ->paginate($perPage)
            ->appends($request->query());

        return view('admin.categorias.index', compact('categorias','q','perPage','estado'));
    }

    public function create()
    {
        return view('admin.categorias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255|unique:categorias,nombre',
            'descripcion' => 'nullable|string|max:255',
        ], [
            'nombre.unique' => 'Ya existe una categoría con ese nombre.',
        ]);

        Categoria::create([
            'nombre'      => trim($request->nombre),
            'descripcion' => trim((string)$request->descripcion),
            'estado'      => true,
        ]);

        return redirect()->route('admin.categorias.index')
            ->with('success','Categoría creada correctamente');
    }

    public function edit(Categoria $categoria)
    {
        return view('admin.categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nombre'      => ['required','string','max:255', Rule::unique('categorias','nombre')->ignore($categoria->id)],
            'descripcion' => 'nullable|string|max:255',
        ], [
            'nombre.unique' => 'Ya existe una categoría con ese nombre.',
        ]);

        $categoria->update([
            'nombre'      => trim($request->nombre),
            'descripcion' => trim((string)$request->descripcion),
        ]);

        return redirect()->route('admin.categorias.index')
            ->with('success','Categoría actualizada correctamente');
    }

    // Usamos destroy como "toggle" activar/desactivar
    public function destroy(Categoria $categoria)
    {
        $categoria->estado = ! $categoria->estado;
        $categoria->save();

        return redirect()->route('admin.categorias.index')
            ->with('success','Estado actualizado');
    }
}
