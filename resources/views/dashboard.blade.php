@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
  {{-- Header de bienvenida --}}
  <div id="tour-welcome" class="bg-gradient-to-r from-primary-600 to-primary-700 rounded-xl shadow-soft">
    <div class="px-6 py-8 sm:px-8">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-white">
            Bienvenido, {{ Auth::user()->name }}
          </h1>
          <p class="mt-2 text-primary-100">
            Resumen general del sistema PrevenApp.
          </p>
        </div>
        <div class="hidden sm:block">
          <div class="flex h-16 w-16 items-center justify-center rounded-full bg-white/10">
            <i class="bx bx-bar-chart-alt-2 text-3xl text-white"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Estadísticas principales --}}
  <div id="tour-stats" class="grid grid-cols-2 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
    {{-- Empresas --}}
    <div class="bg-white dark:bg-gray-800 shadow-soft rounded-xl p-5 min-w-0">
      <div class="flex items-center gap-3 min-w-0">
        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/40">
          <i class="bx bx-buildings text-blue-600 dark:text-blue-400 text-lg"></i>
        </div>
        <div class="min-w-0">
          <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Empresas</p>
          <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($totalEmpresas) }}</p>
        </div>
      </div>
    </div>

    {{-- Documentos --}}
    <div class="bg-white dark:bg-gray-800 shadow-soft rounded-xl p-5 min-w-0">
      <div class="flex items-center gap-3 min-w-0">
        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900/40">
          <i class="bx bx-file text-green-600 dark:text-green-400 text-lg"></i>
        </div>
        <div class="min-w-0">
          <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Documentos</p>
          <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($totalDocumentos) }}</p>
        </div>
      </div>
    </div>

    {{-- Usuarios --}}
    <div class="bg-white dark:bg-gray-800 shadow-soft rounded-xl p-5 min-w-0">
      <div class="flex items-center gap-3 min-w-0">
        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/40">
          <i class="bx bx-user text-amber-600 dark:text-amber-400 text-lg"></i>
        </div>
        <div class="min-w-0">
          <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Usuarios</p>
          <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($totalUsuarios) }}</p>
        </div>
      </div>
    </div>

    {{-- Configuraciones --}}
    <div class="bg-white dark:bg-gray-800 shadow-soft rounded-xl p-5 min-w-0">
      <div class="flex items-center gap-3 min-w-0">
        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900/40">
          <i class="bx bx-cog text-purple-600 dark:text-purple-400 text-lg"></i>
        </div>
        <div class="min-w-0">
          <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Configuraciones</p>
          <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($totalConfiguraciones) }}</p>
        </div>
      </div>
    </div>

    {{-- Categorias --}}
    <div class="bg-white dark:bg-gray-800 shadow-soft rounded-xl p-5 min-w-0">
      <div class="flex items-center gap-3 min-w-0">
        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-teal-100 dark:bg-teal-900/40">
          <i class="bx bx-category text-teal-600 dark:text-teal-400 text-lg"></i>
        </div>
        <div class="min-w-0">
          <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Categorías</p>
          <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($totalCategorias) }}</p>
        </div>
      </div>
    </div>

    {{-- Cargos --}}
    <div class="bg-white dark:bg-gray-800 shadow-soft rounded-xl p-5 min-w-0">
      <div class="flex items-center gap-3 min-w-0">
        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-rose-100 dark:bg-rose-900/40">
          <i class="bx bx-hard-hat text-rose-600 dark:text-rose-400 text-lg"></i>
        </div>
        <div class="min-w-0">
          <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Cargos</p>
          <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($totalCargos) }}</p>
        </div>
      </div>
    </div>
  </div>

  {{-- Grid de contenido --}}
  <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    {{-- Actividad reciente --}}
    <div class="lg:col-span-2 bg-white dark:bg-gray-800 shadow-soft rounded-xl overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center">
          <i class="bx bx-time-five mr-2 text-gray-400"></i>
          Actividad Reciente
        </h3>
      </div>
      <div class="divide-y divide-gray-100 dark:divide-gray-700">
        @forelse($actividad as $item)
          <div class="px-6 py-3.5 flex items-center gap-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
            <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full" style="background-color: {{ $item['bg'] }}">
              <i class="bx {{ $item['icono'] }}" style="color: {{ $item['fg'] }}"></i>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $item['titulo'] }}</p>
              <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $item['detalle'] }}</p>
            </div>
            <span class="text-xs text-gray-400 dark:text-gray-500 whitespace-nowrap">
              {{ $item['fecha'] ? $item['fecha']->diffForHumans() : '' }}
            </span>
          </div>
        @empty
          <div class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
            <i class="bx bx-time-five text-3xl mb-2"></i>
            <p class="text-sm">No hay actividad registrada</p>
          </div>
        @endforelse
      </div>
    </div>

    {{-- Panel derecho --}}
    <div class="space-y-6">
      {{-- Accesos rápidos --}}
      @role('administrador')
      <div id="tour-quick" class="bg-white dark:bg-gray-800 shadow-soft rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
          <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center">
            <i class="bx bx-rocket mr-2 text-gray-400"></i>
            Accesos Rápidos
          </h3>
        </div>
        <div class="p-4 grid grid-cols-2 gap-3">
          <a id="tour-nueva-empresa" href="{{ route('admin.empresas.create') }}" class="group flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-600 hover:shadow-sm transition-all">
            <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/40 group-hover:bg-blue-200 dark:group-hover:bg-blue-800/50 transition-colors">
              <i class="bx bx-plus text-blue-600 dark:text-blue-400"></i>
            </div>
            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Nueva Empresa</span>
          </a>
          <a id="tour-nuevo-documento" href="{{ route('admin.documentos.create') }}" class="group flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-green-300 dark:hover:border-green-600 hover:shadow-sm transition-all">
            <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900/40 group-hover:bg-green-200 dark:group-hover:bg-green-800/50 transition-colors">
              <i class="bx bx-file-plus text-green-600 dark:text-green-400"></i>
            </div>
            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Nuevo Documento</span>
          </a>
          <a id="tour-nuevo-usuario" href="{{ route('admin.usuarios.create') }}" class="group flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-amber-300 dark:hover:border-amber-600 hover:shadow-sm transition-all">
            <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/40 group-hover:bg-amber-200 dark:group-hover:bg-amber-800/50 transition-colors">
              <i class="bx bx-user-plus text-amber-600 dark:text-amber-400"></i>
            </div>
            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Nuevo Usuario</span>
          </a>
          <a id="tour-config" href="{{ route('admin.config.index') }}" class="group flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-purple-300 dark:hover:border-purple-600 hover:shadow-sm transition-all">
            <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900/40 group-hover:bg-purple-200 dark:group-hover:bg-purple-800/50 transition-colors">
              <i class="bx bx-cog text-purple-600 dark:text-purple-400"></i>
            </div>
            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Configuración</span>
          </a>
        </div>
      </div>
      @endrole

      {{-- Últimas empresas --}}
      <div class="bg-white dark:bg-gray-800 shadow-soft rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
          <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center">
            <i class="bx bx-buildings mr-2 text-gray-400"></i>
            Últimas Empresas
          </h3>
          <a href="{{ route('admin.empresas.index') }}" class="text-xs text-primary-600 hover:text-primary-500">Ver todas <i class="bx bx-right-arrow-alt"></i></a>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
          @forelse($ultimasEmpresas as $emp)
            <a href="{{ route('admin.empresas.edit', $emp) }}" class="px-6 py-3 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors block">
              <div class="min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $emp->nombre_empresa }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $emp->rut_empresa }}</p>
              </div>
              <span class="text-xs text-gray-400 whitespace-nowrap ml-3">{{ $emp->created_at?->diffForHumans() }}</span>
            </a>
          @empty
            <div class="px-6 py-6 text-center text-gray-500 dark:text-gray-400 text-sm">
              No hay empresas registradas
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>

@include('partials.dashboard-tour')
@endsection
