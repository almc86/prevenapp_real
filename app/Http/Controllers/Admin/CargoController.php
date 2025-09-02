<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use Illuminate\Http\Request;

class CargoController extends Controller
{
    public function index(Request $request)
    {
        $q       = trim((string) $request->input('q'));
        $perPage = (int) $request->input('per_page', 15);
        if (!in_array($perPage, [10,15,25,50,100], true)) $perPage = 15;

        $cargos = Cargo::query()
            ->when($q, fn($qq) => $qq->where('nombre', 'like', "%{$q}%"))
            ->orderBy('nombre')
            ->paginate($perPage)
            ->appends($request->query());

        return view('admin.cargos.index', compact('cargos','q','perPage'));
    }

    public function create()
    {
        return view('admin.cargos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:cargos,nombre',
        ], [
            'nombre.unique' => 'Ese cargo ya existe.',
        ]);

        Cargo::create(['nombre' => trim($request->nombre)]);

        return redirect()->route('admin.cargos.index')
            ->with('success', 'Cargo creado correctamente');
    }
}
