<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TipoCobro;
use App\Models\TipoCobroRango;
use App\Models\Empresa;
use Illuminate\Http\Request;

class TipoCobroController extends Controller
{
    public function index()
    {
        $tiposCobro = TipoCobro::with(['empresaPrincipal', 'empresaContratista', 'rangos'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.tipos_cobro.index', compact('tiposCobro'));
    }

    public function create()
    {
        $empresas = Empresa::orderBy('nombre_empresa')->get();

        return view('admin.tipos_cobro.create', compact('empresas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'empresa_principal_id' => 'required|exists:empresas,id',
            'empresa_contratista_id' => 'required|exists:empresas,id|different:empresa_principal_id',
            'tipo_cobro' => 'required|in:uf,pesos',
            'tipo_pago' => 'required|in:webpay,factura',
            'observaciones' => 'nullable|string|max:500',
            'rangos' => 'required|array|min:1',
            'rangos.*.trabajadores_desde' => 'required|integer|min:1',
            'rangos.*.trabajadores_hasta' => 'required|integer|min:1',
            'rangos.*.monto' => 'required|numeric|min:0',
        ]);

        // Validar que los rangos no se solapen
        $rangos = collect($request->rangos)->sortBy('trabajadores_desde');
        for ($i = 0; $i < $rangos->count() - 1; $i++) {
            $current = $rangos->values()[$i];
            $next = $rangos->values()[$i + 1];

            if ($current['trabajadores_hasta'] >= $next['trabajadores_desde']) {
                return back()->withErrors(['rangos' => 'Los rangos de trabajadores no pueden solaparse.'])
                    ->withInput();
            }
        }

        $tipoCobro = TipoCobro::create($request->only([
            'empresa_principal_id',
            'empresa_contratista_id',
            'tipo_cobro',
            'tipo_pago',
            'observaciones'
        ]));

        foreach ($request->rangos as $rango) {
            $tipoCobro->rangos()->create($rango);
        }

        return redirect()->route('admin.tipos-cobro.index')
            ->with('success', 'Tipo de cobro creado exitosamente.');
    }

    public function show(TipoCobro $tiposCobro)
    {
        $tiposCobro->load(['empresaPrincipal', 'empresaContratista', 'rangos']);

        return view('admin.tipos_cobro.show', compact('tiposCobro'));
    }

    public function edit(TipoCobro $tiposCobro)
    {
        $empresas = Empresa::orderBy('nombre_empresa')->get();
        $tiposCobro->load('rangos');

        return view('admin.tipos_cobro.edit', compact('tiposCobro', 'empresas'));
    }

    public function update(Request $request, TipoCobro $tiposCobro)
    {
        $request->validate([
            'empresa_principal_id' => 'required|exists:empresas,id',
            'empresa_contratista_id' => 'required|exists:empresas,id|different:empresa_principal_id',
            'tipo_cobro' => 'required|in:uf,pesos',
            'tipo_pago' => 'required|in:webpay,factura',
            'activo' => 'boolean',
            'observaciones' => 'nullable|string|max:500',
            'rangos' => 'required|array|min:1',
            'rangos.*.trabajadores_desde' => 'required|integer|min:1',
            'rangos.*.trabajadores_hasta' => 'required|integer|min:1',
            'rangos.*.monto' => 'required|numeric|min:0',
        ]);

        // Validar que los rangos no se solapen
        $rangos = collect($request->rangos)->sortBy('trabajadores_desde');
        for ($i = 0; $i < $rangos->count() - 1; $i++) {
            $current = $rangos->values()[$i];
            $next = $rangos->values()[$i + 1];

            if ($current['trabajadores_hasta'] >= $next['trabajadores_desde']) {
                return back()->withErrors(['rangos' => 'Los rangos de trabajadores no pueden solaparse.'])
                    ->withInput();
            }
        }

        $tiposCobro->update($request->only([
            'empresa_principal_id',
            'empresa_contratista_id',
            'tipo_cobro',
            'tipo_pago',
            'activo',
            'observaciones'
        ]));

        // Eliminar rangos existentes y crear nuevos
        $tiposCobro->rangos()->delete();
        foreach ($request->rangos as $rango) {
            $tiposCobro->rangos()->create($rango);
        }

        return redirect()->route('admin.tipos-cobro.index')
            ->with('success', 'Tipo de cobro actualizado exitosamente.');
    }

    public function destroy(TipoCobro $tiposCobro)
    {
        $tiposCobro->delete();

        return redirect()->route('admin.tipos-cobro.index')
            ->with('success', 'Tipo de cobro eliminado exitosamente.');
    }
}
