@extends('layouts.app')

@section('title','Configuración')

@section('content')
<div class="space-y-6">
  {{-- Header --}}
  <div class="md:flex md:items-center md:justify-between">
    <div class="min-w-0 flex-1">
      <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
        Configuración del Sistema
      </h2>
      <p class="mt-1 text-sm text-gray-500">
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
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary-100 group-hover:bg-primary-200 transition-colors">
              <i class="bx bx-file text-2xl text-primary-600"></i>
            </div>
            <div class="ml-4">
              <h3 class="text-lg font-medium text-gray-900 group-hover:text-primary-600 transition-colors">
                Tipos de Documento
              </h3>
              <p class="text-sm text-gray-500">
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
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-success-100 group-hover:bg-success-200 transition-colors">
              <i class="bx bx-folder text-2xl text-success-600"></i>
            </div>
            <div class="ml-4">
              <h3 class="text-lg font-medium text-gray-900 group-hover:text-success-600 transition-colors">
                Documentos
              </h3>
              <p class="text-sm text-gray-500">
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
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-warning-100 group-hover:bg-warning-200 transition-colors">
              <i class="bx bx-category text-2xl text-warning-600"></i>
            </div>
            <div class="ml-4">
              <h3 class="text-lg font-medium text-gray-900 group-hover:text-warning-600 transition-colors">
                Categorías
              </h3>
              <p class="text-sm text-gray-500">
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
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100 group-hover:bg-purple-200 transition-colors">
              <i class="bx bx-id-card text-2xl text-purple-600"></i>
            </div>
            <div class="ml-4">
              <h3 class="text-lg font-medium text-gray-900 group-hover:text-purple-600 transition-colors">
                Cargos
              </h3>
              <p class="text-sm text-gray-500">
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
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-100 group-hover:bg-indigo-200 transition-colors">
              <i class="bx bx-car text-2xl text-indigo-600"></i>
            </div>
            <div class="ml-4">
              <h3 class="text-lg font-medium text-gray-900 group-hover:text-indigo-600 transition-colors">
                Marca de Flota
              </h3>
              <p class="text-sm text-gray-500">
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
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-red-100 group-hover:bg-red-200 transition-colors">
              <i class="bx bx-calendar-event text-2xl text-red-600"></i>
            </div>
            <div class="ml-4">
              <h3 class="text-lg font-medium text-gray-900 group-hover:text-red-600 transition-colors">
                Feriados
              </h3>
              <p class="text-sm text-gray-500">
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
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 group-hover:bg-blue-200 transition-colors">
              <i class="bx bx-buildings text-2xl text-blue-600"></i>
            </div>
            <div class="ml-4">
              <h3 class="text-lg font-medium text-gray-900 group-hover:text-blue-600 transition-colors">
                Empresas
              </h3>
              <p class="text-sm text-gray-500">
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
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 group-hover:bg-green-200 transition-colors">
              <i class="bx bx-money text-2xl text-green-600"></i>
            </div>
            <div class="ml-4">
              <h3 class="text-lg font-medium text-gray-900 group-hover:text-green-600 transition-colors">
                Tipos de Cobro
              </h3>
              <p class="text-sm text-gray-500">
                Define y administra los tipos de cobro.
              </p>
            </div>
          </div>
        </div>
      </div>
    </a>

  </div>

  {{-- Stats adicionales --}}
  <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
    <div class="bg-white overflow-hidden shadow-soft rounded-xl">
      <div class="p-5">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <i class="bx bx-file text-2xl text-gray-400"></i>
          </div>
          <div class="ml-5 w-0 flex-1">
            <dl>
              <dt class="text-sm font-medium text-gray-500 truncate">Total Documentos</dt>
              <dd class="text-lg font-medium text-gray-900">1,234</dd>
            </dl>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-white overflow-hidden shadow-soft rounded-xl">
      <div class="p-5">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <i class="bx bx-buildings text-2xl text-gray-400"></i>
          </div>
          <div class="ml-5 w-0 flex-1">
            <dl>
              <dt class="text-sm font-medium text-gray-500 truncate">Empresas Activas</dt>
              <dd class="text-lg font-medium text-gray-900">89</dd>
            </dl>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-white overflow-hidden shadow-soft rounded-xl">
      <div class="p-5">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <i class="bx bx-user text-2xl text-gray-400"></i>
          </div>
          <div class="ml-5 w-0 flex-1">
            <dl>
              <dt class="text-sm font-medium text-gray-500 truncate">Usuarios Registrados</dt>
              <dd class="text-lg font-medium text-gray-900">456</dd>
            </dl>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
