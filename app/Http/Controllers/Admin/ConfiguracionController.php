<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Cargo, Categoria, Configuracion, Documento, Empresa, Feriado, TipoDocumento};

class ConfiguracionController extends Controller
{
    public function index()
    {
        $stats = [
            'documentos_activos' => Documento::where('estado', 1)->count(),
            'documentos_total'   => Documento::count(),
            'empresas_total'     => Empresa::count(),
            'configs_activas'    => Configuracion::where('estado', 1)->count(),
            'configs_total'      => Configuracion::count(),
            'cargos_total'       => Cargo::count(),
            'categorias_total'   => Categoria::count(),
            'tipos_doc_total'    => TipoDocumento::count(),
            'feriados_anio'      => Feriado::whereYear('fecha_feriado_date', now()->year)->count(),
        ];

        return view('admin.config.index', compact('stats'));
    }
}
