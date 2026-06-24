<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Empresa, User, Documento, Configuracion, Categoria, Cargo};
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats principales
        $totalEmpresas = Empresa::count();
        $totalDocumentos = Documento::where('estado', 1)->count();
        $totalUsuarios = User::count();
        $totalConfiguraciones = Configuracion::where('estado', 1)->count();
        $totalCategorias = Categoria::count();
        $totalCargos = Cargo::count();

        // Actividad reciente: mezclar últimos registros de distintas tablas
        $actividad = collect();

        $empresasRecientes = Empresa::latest()->take(5)->get()->map(fn($e) => [
            'titulo' => 'Nueva empresa registrada',
            'detalle' => $e->nombre_empresa,
            'fecha' => $e->created_at,
            'icono' => 'bx-buildings',
            'bg' => '#dcfce7', 'fg' => '#16a34a',
        ]);

        $documentosRecientes = Documento::latest()->take(5)->get()->map(fn($d) => [
            'titulo' => 'Documento creado',
            'detalle' => $d->nombre,
            'fecha' => $d->created_at,
            'icono' => 'bx-file',
            'bg' => '#dbeafe', 'fg' => '#2563eb',
        ]);

        $usuariosRecientes = User::latest()->take(5)->get()->map(fn($u) => [
            'titulo' => 'Usuario registrado',
            'detalle' => $u->name . ' — ' . $u->email,
            'fecha' => $u->created_at,
            'icono' => 'bx-user',
            'bg' => '#fef3c7', 'fg' => '#d97706',
        ]);

        $configsRecientes = Configuracion::with('empresa')->latest()->take(5)->get()->map(fn($c) => [
            'titulo' => 'Configuración creada',
            'detalle' => $c->nombre . ($c->empresa ? ' — ' . $c->empresa->nombre_empresa : ''),
            'fecha' => $c->created_at,
            'icono' => 'bx-cog',
            'bg' => '#e0e7ff', 'fg' => '#4f46e5',
        ]);

        // collect() => colección base: estos items son arrays (no modelos), así que
        // merge() no debe llamar getKey() sobre ellos como hace la colección Eloquent.
        $actividad = collect($empresasRecientes)
            ->merge($documentosRecientes)
            ->merge($usuariosRecientes)
            ->merge($configsRecientes)
            ->sortByDesc('fecha')
            ->take(8)
            ->values();

        // Últimas 5 empresas para tabla rápida
        $ultimasEmpresas = Empresa::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalEmpresas',
            'totalDocumentos',
            'totalUsuarios',
            'totalConfiguraciones',
            'totalCategorias',
            'totalCargos',
            'actividad',
            'ultimasEmpresas',
        ));
    }
}
