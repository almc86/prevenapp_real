@extends('layouts.app')

@section('title','Empresas')

@section('content')
<div class="space-y-6">
  {{-- Alertas --}}
  @if(session('success'))
    <div class="alert alert-success">
      <i class="bx bx-check-circle mr-2"></i>
      {{ session('success') }}
    </div>
  @endif

  {{-- Header --}}
  <div class="sm:flex sm:items-center sm:justify-between">
    <div>
      <h1 class="text-2xl font-bold leading-tight text-gray-900 dark:text-white">Empresas</h1>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
        Gestiona todas las empresas del sistema y sus configuraciones.
      </p>
    </div>
    <div class="mt-4 sm:mt-0">
      <a href="{{ route('admin.empresas.create') }}" class="btn btn-primary">
        <i class="bx bx-plus mr-2"></i>
        Nueva empresa
      </a>
    </div>
  </div>

  {{-- Lista de empresas --}}
  <div class="bg-white dark:bg-gray-800 shadow-soft rounded-xl overflow-hidden">
    @forelse($empresas as $e)
      <div class="border-b border-gray-200 dark:border-gray-700 last:border-b-0">
        <div class="p-6">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
              {{-- Logo --}}
              <div class="flex-shrink-0">
                @if($e->logo_path)
                  <img src="{{ Storage::url($e->logo_path) }}" alt="Logo"
                       class="h-12 w-12 rounded-lg object-contain border border-gray-200">
                @else
                  <div class="h-12 w-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                    <i class="bx bx-buildings text-xl text-gray-400 dark:text-gray-500"></i>
                  </div>
                @endif
              </div>

              {{-- Información principal --}}
              <div class="min-w-0 flex-1">
                <div class="flex items-center space-x-2">
                  <h3 class="text-lg font-medium text-gray-900 dark:text-white truncate">
                    {{ $e->nombre_empresa }}
                  </h3>
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Activa
                  </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">RUT: {{ $e->rut_empresa }}</p>

                {{-- Información adicional en mobile --}}
                <div class="mt-2 sm:hidden">
                  <div class="text-sm text-gray-500 dark:text-gray-400 space-y-1">
                    <div class="flex items-center">
                      <i class="bx bx-map text-xs mr-1"></i>
                      {{ $e->region_id ? ($e->region->nombre ?? '-') : ($e->region ?? '-') }},
                      {{ $e->comuna_id ? ($e->comuna->nombre ?? '-') : ($e->comuna ?? '-') }}
                    </div>
                    @if($e->telefono)
                      <div class="flex items-center">
                        <i class="bx bx-phone text-xs mr-1"></i>
                        {{ $e->telefono }}
                      </div>
                    @endif
                    @if($e->correo_empresa)
                      <div class="flex items-center">
                        <i class="bx bx-envelope text-xs mr-1"></i>
                        {{ $e->correo_empresa }}
                      </div>
                    @endif
                  </div>
                </div>
              </div>
            </div>

            {{-- Acciones --}}
            <div class="flex items-center space-x-2">
              <a href="{{ route('admin.empresas.edit', $e) }}"
                 class="btn btn-sm btn-secondary">
                <i class="bx bx-edit text-sm"></i>
                <span class="hidden sm:inline ml-1">Editar</span>
              </a>
              <form action="{{ route('admin.empresas.destroy', $e) }}" method="POST" class="inline"
                    onsubmit="return confirm('¿Eliminar empresa {{ $e->nombre_empresa }}?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">
                  <i class="bx bx-trash text-sm"></i>
                  <span class="hidden sm:inline ml-1">Eliminar</span>
                </button>
              </form>
            </div>
          </div>

          {{-- Información adicional para desktop --}}
          <div class="hidden sm:block mt-4">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
              <div>
                <span class="text-gray-500 dark:text-gray-400">Ubicación:</span>
                <p class="font-medium text-gray-900 dark:text-white">
                  {{ $e->region_id ? ($e->region->nombre ?? '-') : ($e->region ?? '-') }},
                  {{ $e->comuna_id ? ($e->comuna->nombre ?? '-') : ($e->comuna ?? '-') }}
                </p>
              </div>
              <div>
                <span class="text-gray-500 dark:text-gray-400">Teléfono:</span>
                <p class="font-medium text-gray-900 dark:text-white">{{ $e->telefono ?: '-' }}</p>
              </div>
              <div>
                <span class="text-gray-500 dark:text-gray-400">Correo:</span>
                <p class="font-medium text-gray-900 dark:text-white">{{ $e->correo_empresa ?: '-' }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="text-center py-12">
        <div class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500">
          <i class="bx bx-buildings text-4xl"></i>
        </div>
        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay empresas</h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comienza creando una nueva empresa.</p>
        <div class="mt-6">
          <a href="{{ route('admin.empresas.create') }}" class="btn btn-primary">
            <i class="bx bx-plus mr-2"></i>
            Nueva empresa
          </a>
        </div>
      </div>
    @endforelse
  </div>

  {{-- Paginación --}}
  @if(method_exists($empresas, 'links'))
    <div class="flex justify-center">
      {{ $empresas->links() }}
    </div>
  @endif
</div>
@endsection
