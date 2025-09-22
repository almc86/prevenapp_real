@extends('layouts.app')
@section('title','Configuraciones: '.$empresa->nombre_empresa)

@section('content')
<div class="space-y-6">
  {{-- Mensajes --}}
  @if(session('success'))
    <div class="alert alert-success">
      <div class="flex items-center">
        <i class="bx bx-check-circle text-lg mr-2"></i>
        <span>{{ session('success') }}</span>
      </div>
    </div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger">
      <div class="flex items-center mb-2">
        <i class="bx bx-error-circle text-lg mr-2"></i>
        <span class="font-medium">Por favor corrige los siguientes errores:</span>
      </div>
      <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $e)
          <li class="text-sm">{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Header --}}
  <div class="md:flex md:items-center md:justify-between">
    <div class="min-w-0 flex-1">
      <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
        <i class="bx bx-cog mr-2"></i>
        Configuraciones
      </h2>
      <p class="mt-1 text-sm text-gray-500">
        Gestiona las configuraciones de <strong>{{ $empresa->nombre_empresa }}</strong>
      </p>
    </div>
    <div class="mt-4 flex md:mt-0 space-x-3">
      <a href="{{ route('admin.config-empresas.index') }}" class="btn btn-secondary">
        <i class="bx bx-arrow-back mr-2"></i>
        Cambiar empresa
      </a>
    </div>
  </div>

  {{-- Crear nueva configuración --}}
  <div class="bg-white shadow-soft rounded-xl overflow-hidden">
    <div class="px-6 py-6">
      <h3 class="text-lg font-medium leading-6 text-gray-900 mb-6">
        <i class="bx bx-plus-circle mr-2"></i>
        Nueva Configuración
      </h3>
      <form method="POST" action="{{ route('admin.config-empresas.configs.store', $empresa) }}">
        @csrf
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
          <div class="sm:col-span-2">
            <label class="form-label">Nombre de la configuración *</label>
            <input type="text" name="nombre" class="form-control"
                   value="{{ old('nombre') }}"
                   placeholder="Ej: Configuración principal, Config 2025, etc."
                   required maxlength="255">
            <p class="form-help">Ingresa un nombre único para identificar esta configuración</p>
          </div>
          <div class="sm:col-span-1 flex items-end">
            <button type="submit" class="btn btn-primary w-full">
              <i class="bx bx-plus mr-2"></i>
              Crear configuración
            </button>
          </div>
        </div>
        <div class="mt-4">
          <label class="form-label">Descripción</label>
          <textarea name="descripcion" class="form-control" rows="2" maxlength="255"
                    placeholder="Descripción opcional de la configuración">{{ old('descripcion') }}</textarea>
        </div>
      </form>
    </div>
  </div>

  {{-- Lista de configuraciones existentes --}}
  <div class="bg-white shadow-soft rounded-xl overflow-hidden">
    <div class="px-6 py-6">
      <h3 class="text-lg font-medium leading-6 text-gray-900 mb-6">
        <i class="bx bx-list-ul mr-2"></i>
        Configuraciones Existentes ({{ $configs->count() }})
      </h3>

      @forelse($configs as $config)
        <div class="border border-gray-200 rounded-lg p-4 mb-4 hover:shadow-sm transition-shadow">
          <div class="flex items-center justify-between">
            <div class="flex-1">
              <div class="flex items-center">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-100">
                  <i class="bx bx-cog text-primary-600"></i>
                </div>
                <div class="ml-3">
                  <h4 class="text-lg font-medium text-gray-900">{{ $config->nombre }}</h4>
                  @if($config->descripcion)
                    <p class="text-sm text-gray-500">{{ $config->descripcion }}</p>
                  @endif
                  <div class="flex items-center space-x-4 text-xs text-gray-400 mt-1">
                    <span>Creado: {{ $config->created_at->format('d/m/Y H:i') }}</span>
                    @if($config->created_by)
                      <span>Por: {{ $config->creator->name ?? 'Usuario eliminado' }}</span>
                    @endif
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $config->estado ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                      {{ $config->estado ? 'Activa' : 'Inactiva' }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <div class="ml-4">
              <a href="{{ route('admin.config-empresas.show', [$empresa, $config]) }}"
                 class="btn btn-primary">
                <i class="bx bx-cog mr-2"></i>
                Configurar
              </a>
            </div>
          </div>
        </div>
      @empty
        <div class="text-center py-8 text-gray-500">
          <i class="bx bx-cog text-4xl mb-2"></i>
          <div>No hay configuraciones creadas</div>
          <div class="text-sm">Crea tu primera configuración usando el formulario de arriba</div>
        </div>
      @endforelse
    </div>
  </div>
</div>
@endsection