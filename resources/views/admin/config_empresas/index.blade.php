@extends('layouts.app')

@section('title','Configurar Empresas')

@section('content')
<div class="space-y-6">
  {{-- Header --}}
  <div class="md:flex md:items-center md:justify-between">
    <div class="min-w-0 flex-1">
      <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate sm:text-3xl sm:tracking-tight">
        Configurar Empresas
      </h2>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
        Selecciona una empresa para configurar sus categorías, documentos e ítems específicos.
      </p>
    </div>
    <div class="mt-4 flex md:mt-0">
      <a href="{{ route('admin.config.index') }}" class="btn btn-secondary">
        <i class="bx bx-arrow-back mr-2"></i>
        Volver a Configuración
      </a>
    </div>
  </div>

  {{-- Selector de empresa --}}
  <div class="bg-white dark:bg-gray-800 shadow-soft rounded-xl overflow-hidden">
    <div class="px-6 py-6">
      <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-6">
        <i class="bx bx-buildings mr-2"></i>
        Seleccionar Empresa
      </h3>

      <form method="GET"
            action="{{ route('admin.config-empresas.configs.index', ['empresa' => '__ID__']) }}"
            onsubmit="this.action=this.action.replace('__ID__', document.getElementById('empresa_id').value)">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-4 items-end">
          <div class="sm:col-span-3">
            <label class="form-label">Empresa *</label>
            <select id="empresa_id" class="form-select" required>
              <option value="">Seleccione una empresa...</option>
              @foreach($empresas as $e)
                <option value="{{ $e->id }}">
                  {{ $e->nombre_empresa }} - {{ $e->rut_empresa }}
                </option>
              @endforeach
            </select>
            <p class="form-help">Selecciona la empresa que deseas configurar</p>
          </div>
          <div class="sm:col-span-1">
            <button type="submit" class="btn btn-primary w-full">
              <i class="bx bx-cog mr-2"></i>
              Configurar
            </button>
          </div>
        </div>
      </form>
    </div>

    {{-- Lista de empresas recientes (opcional) --}}
    @if($empresas->count() > 0)
      <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-700">
        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Acceso rápido</h4>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
          @foreach($empresas->take(6) as $empresa)
            <a href="{{ route('admin.config-empresas.configs.index', $empresa) }}"
               class="group flex items-center p-3 bg-white dark:bg-gray-600 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-primary-300 dark:hover:border-primary-500 hover:shadow-sm transition-all">
              <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-100 dark:bg-primary-800 group-hover:bg-primary-200 dark:group-hover:bg-primary-700 transition-colors">
                <i class="bx bx-building text-primary-600 dark:text-primary-300"></i>
              </div>
              <div class="ml-3 min-w-0 flex-1">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                  {{ $empresa->nombre_empresa }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                  {{ $empresa->rut_empresa }}
                </p>
              </div>
              <div class="ml-2">
                <i class="bx bx-chevron-right text-gray-400 dark:text-gray-500 group-hover:text-primary-600 dark:group-hover:text-primary-300 transition-colors"></i>
              </div>
            </a>
          @endforeach
        </div>
      </div>
    @endif
  </div>
</div>
@endsection
