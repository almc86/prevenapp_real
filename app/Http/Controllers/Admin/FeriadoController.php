<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feriado;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class FeriadoController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->integer('year');   // /feriados?year=2025
        $q = Feriado::query();

        if ($year) {
            $q->whereYear('fecha_feriado_date', $year);
        }

        $feriados = $q->orderBy('fecha_feriado_date')->get();

        return view('admin.feriados.index', compact('feriados', 'year'));
    }

    public function create()
    {
        return view('admin.feriados.create');
    }

    public function store(Request $request)
    {
        // Normaliza fecha a Y-m-d (por si acaso)
        $fecha = Carbon::parse($request->input('fecha_feriado_date'))->format('Y-m-d');

        $validated = $request->validate([
            'fecha_feriado_date' => [
                'required',
                'date',
                // Evita duplicar feriados empresariales en la misma fecha,
                // pero permite que exista un feriado "oficial" ese mismo día.
                Rule::unique('feriados', 'fecha_feriado_date')
                    ->where(fn ($q) => $q->where('es_empresarial', 1)),
            ],
            'descripcion_feriado' => ['required', 'string', 'max:255'],
            'es_empresarial' => ['nullable', 'in:1'],
        ], [
            'fecha_feriado_date.unique' => 'Ya existe un feriado empresarial en esa fecha.',
        ]);

        Feriado::create([
            'fecha_feriado_date' => $fecha,
            'descripcion_feriado' => $validated['descripcion_feriado'],
            'es_empresarial' => 1,
        ]);

        return redirect()
            ->route('admin.feriados.index')
            ->with('status', 'Feriado empresarial creado correctamente.');
    }
}