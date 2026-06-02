@extends('layouts.app')

@section('title','Configuración')

@section('content')
<div class="space-y-6">
  {{-- Header --}}
  <div class="md:flex md:items-center md:justify-between">
    <div class="min-w-0 flex-1">
      <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate sm:text-3xl sm:tracking-tight">
        Configuración del Sistema
      </h2>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
        Administra todos los aspectos de configuración del sistema desde aquí.
      </p>
    </div>
  </div>

  {{-- Grid de opciones de configuración --}}
  <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

    {{-- Tipo de documento --}}
    <a href="{{ route('admin.tipos-documento.index') }}" class="group">
      <div class="card card-hover h-full">
        <div class="card-body">
          <div class="flex items-center">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary-100 dark:bg-primary-800 group-hover:bg-primary-200 dark:group-hover:bg-primary-700 transition-colors">
              <i class="bx bx-file text-2xl text-primary-600 dark:text-primary-300"></i>
            </div>
            <div class="ml-4">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-300 transition-colors">
                Tipos de Documento
              </h3>
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Define nuevos tipos para clasificar documentos.
              </p>
            </div>
          </div>
        </div>
      </div>
    </a>

    {{-- Documento --}}
    <a href="{{ route('admin.documentos.index') }}" class="group">
      <div class="card card-hover h-full">
        <div class="card-body">
          <div class="flex items-center">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-success-100 dark:bg-success-800 group-hover:bg-success-200 dark:group-hover:bg-success-700 transition-colors">
              <i class="bx bx-folder text-2xl text-success-600 dark:text-success-300"></i>
            </div>
            <div class="ml-4">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white group-hover:text-success-600 dark:group-hover:text-success-300 transition-colors">
                Documentos
              </h3>
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Sube o registra documentos del repositorio.
              </p>
            </div>
          </div>
        </div>
      </div>
    </a>

    {{-- Categoría --}}
    <a href="{{ route('admin.categorias.index') }}" class="group">
      <div class="card card-hover h-full">
        <div class="card-body">
          <div class="flex items-center">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-warning-100 dark:bg-warning-800 group-hover:bg-warning-200 dark:group-hover:bg-warning-700 transition-colors">
              <i class="bx bx-category text-2xl text-warning-600 dark:text-warning-300"></i>
            </div>
            <div class="ml-4">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white group-hover:text-warning-600 dark:group-hover:text-warning-300 transition-colors">
                Categorías
              </h3>
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Organiza los documentos por categorías.
              </p>
            </div>
          </div>
        </div>
      </div>
    </a>

    {{-- Cargo --}}
    <a href="{{ route('admin.cargos.index') }}" class="group">
      <div class="card card-hover h-full">
        <div class="card-body">
          <div class="flex items-center">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-800 group-hover:bg-purple-200 dark:group-hover:bg-purple-700 transition-colors">
              <i class="bx bx-id-card text-2xl text-purple-600 dark:text-purple-300"></i>
            </div>
            <div class="ml-4">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-300 transition-colors">
                Cargos
              </h3>
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Mantén el maestro de cargos actualizado.
              </p>
            </div>
          </div>
        </div>
      </div>
    </a>

    {{-- Marca Flota --}}
    <a href="{{ route('admin.marcas-flota.create') }}" class="group">
      <div class="card card-hover h-full">
        <div class="card-body">
          <div class="flex items-center">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-800 group-hover:bg-indigo-200 dark:group-hover:bg-indigo-700 transition-colors">
              <i class="bx bx-car text-2xl text-indigo-600 dark:text-indigo-300"></i>
            </div>
            <div class="ml-4">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors">
                Marca de Flota
              </h3>
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Maestro de marcas para vehículos/equipos.
              </p>
            </div>
          </div>
        </div>
      </div>
    </a>

    {{-- Feriados --}}
    <a href="{{ route('admin.feriados.index') }}" class="group">
      <div class="card card-hover h-full">
        <div class="card-body">
          <div class="flex items-center">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-red-100 dark:bg-red-800 group-hover:bg-red-200 dark:group-hover:bg-red-700 transition-colors">
              <i class="bx bx-calendar-event text-2xl text-red-600 dark:text-red-300"></i>
            </div>
            <div class="ml-4">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white group-hover:text-red-600 dark:group-hover:text-red-300 transition-colors">
                Feriados
              </h3>
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Calendario de feriados para validaciones.
              </p>
            </div>
          </div>
        </div>
      </div>
    </a>

    {{-- Configurar Empresas --}}
    <a href="{{ route('admin.config-empresas.index') }}" class="group">
      <div class="card card-hover h-full">
        <div class="card-body">
          <div class="flex items-center">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-800 group-hover:bg-blue-200 dark:group-hover:bg-blue-700 transition-colors">
              <i class="bx bx-buildings text-2xl text-blue-600 dark:text-blue-300"></i>
            </div>
            <div class="ml-4">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-300 transition-colors">
                Empresas
              </h3>
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Crear y administrar empresas y sus datos.
              </p>
            </div>
          </div>
        </div>
      </div>
    </a>

    {{-- Tipo de Cobros --}}
    <a href="{{ route('admin.tipos-cobro.index') }}" class="group">
      <div class="card card-hover h-full">
        <div class="card-body">
          <div class="flex items-center">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 dark:bg-green-800 group-hover:bg-green-200 dark:group-hover:bg-green-700 transition-colors">
              <i class="bx bx-money text-2xl text-green-600 dark:text-green-300"></i>
            </div>
            <div class="ml-4">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-300 transition-colors">
                Tipos de Cobro
              </h3>
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Define y administra los tipos de cobro.
              </p>
            </div>
          </div>
        </div>
      </div>
    </a>

  </div>

  {{-- Stats adicionales --}}
  <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
    {{-- Documentos --}}
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-soft rounded-xl">
      <div class="p-5">
        <div class="flex items-center">
          <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-lg bg-success-100 dark:bg-success-900/40">
            <i class="bx bx-file text-2xl text-success-600 dark:text-success-300"></i>
          </div>
          <div class="ml-4 w-0 flex-1">
            <dl>
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Documentos activos</dt>
              <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['documentos_activos']) }}</dd>
              <dd class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                de {{ number_format($stats['documentos_total']) }} totales · {{ $stats['tipos_doc_total'] }} tipos
              </dd>
            </dl>
          </div>
        </div>
      </div>
    </div>

    {{-- Empresas --}}
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-soft rounded-xl">
      <div class="p-5">
        <div class="flex items-center">
          <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/40">
            <i class="bx bx-buildings text-2xl text-blue-600 dark:text-blue-300"></i>
          </div>
          <div class="ml-4 w-0 flex-1">
            <dl>
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Empresas registradas</dt>
              <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['empresas_total']) }}</dd>
              <dd class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                @if($stats['empresas_total'] > 0)
                  prom. {{ number_format($stats['configs_total'] / max($stats['empresas_total'], 1), 1) }} configs/empresa
                @else
                  sin empresas registradas
                @endif
              </dd>
            </dl>
          </div>
        </div>
      </div>
    </div>

    {{-- Configuraciones --}}
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-soft rounded-xl">
      <div class="p-5">
        <div class="flex items-center">
          <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-lg bg-primary-100 dark:bg-primary-900/40">
            <i class="bx bx-cog text-2xl text-primary-600 dark:text-primary-300"></i>
          </div>
          <div class="ml-4 w-0 flex-1">
            <dl>
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Configuraciones activas</dt>
              <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['configs_activas']) }}</dd>
              <dd class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                de {{ number_format($stats['configs_total']) }} creadas
              </dd>
            </dl>
          </div>
        </div>
      </div>
    </div>

    {{-- Catálogo maestro --}}
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-soft rounded-xl">
      <div class="p-5">
        <div class="flex items-center">
          <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900/40">
            <i class="bx bx-collection text-2xl text-purple-600 dark:text-purple-300"></i>
          </div>
          <div class="ml-4 w-0 flex-1">
            <dl>
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Catálogo maestro</dt>
              <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['cargos_total'] + $stats['categorias_total']) }}</dd>
              <dd class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                {{ $stats['cargos_total'] }} cargos · {{ $stats['categorias_total'] }} categorías · {{ $stats['feriados_anio'] }} feriados {{ now()->year }}
              </dd>
            </dl>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
