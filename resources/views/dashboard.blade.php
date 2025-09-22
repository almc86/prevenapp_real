@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
  {{-- Header de bienvenida --}}
  <div class="bg-gradient-to-r from-primary-600 to-primary-700 rounded-xl shadow-soft">
    <div class="px-6 py-8 sm:px-8">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-white">
            ¡Bienvenido de vuelta, {{ Auth::user()->name }}!
          </h1>
          <p class="mt-2 text-primary-100">
            Aquí tienes un resumen de la actividad del sistema.
          </p>
        </div>
        <div class="hidden sm:block">
          <div class="flex h-16 w-16 items-center justify-center rounded-full bg-white/10">
            <i class="bx bx-user text-3xl text-white"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Estadísticas principales --}}
  <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
    <div class="bg-white overflow-hidden shadow-soft rounded-xl">
      <div class="p-5">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="flex h-8 w-8 items-center justify-center rounded-md bg-primary-500">
              <i class="bx bx-buildings text-white"></i>
            </div>
          </div>
          <div class="ml-5 w-0 flex-1">
            <dl>
              <dt class="text-sm font-medium text-gray-500 truncate">Total Empresas</dt>
              <dd class="flex items-baseline">
                <div class="text-2xl font-semibold text-gray-900">89</div>
                <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                  <i class="bx bx-trending-up text-xs mr-1"></i>
                  12%
                </div>
              </dd>
            </dl>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-white overflow-hidden shadow-soft rounded-xl">
      <div class="p-5">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="flex h-8 w-8 items-center justify-center rounded-md bg-success-500">
              <i class="bx bx-file text-white"></i>
            </div>
          </div>
          <div class="ml-5 w-0 flex-1">
            <dl>
              <dt class="text-sm font-medium text-gray-500 truncate">Documentos</dt>
              <dd class="flex items-baseline">
                <div class="text-2xl font-semibold text-gray-900">1,234</div>
                <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                  <i class="bx bx-trending-up text-xs mr-1"></i>
                  5%
                </div>
              </dd>
            </dl>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-white overflow-hidden shadow-soft rounded-xl">
      <div class="p-5">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="flex h-8 w-8 items-center justify-center rounded-md bg-warning-500">
              <i class="bx bx-user text-white"></i>
            </div>
          </div>
          <div class="ml-5 w-0 flex-1">
            <dl>
              <dt class="text-sm font-medium text-gray-500 truncate">Usuarios Activos</dt>
              <dd class="flex items-baseline">
                <div class="text-2xl font-semibold text-gray-900">456</div>
                <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                  <i class="bx bx-trending-up text-xs mr-1"></i>
                  8%
                </div>
              </dd>
            </dl>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-white overflow-hidden shadow-soft rounded-xl">
      <div class="p-5">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="flex h-8 w-8 items-center justify-center rounded-md bg-danger-500">
              <i class="bx bx-error-circle text-white"></i>
            </div>
          </div>
          <div class="ml-5 w-0 flex-1">
            <dl>
              <dt class="text-sm font-medium text-gray-500 truncate">Pendientes</dt>
              <dd class="flex items-baseline">
                <div class="text-2xl font-semibold text-gray-900">23</div>
                <div class="ml-2 flex items-baseline text-sm font-semibold text-red-600">
                  <i class="bx bx-trending-down text-xs mr-1"></i>
                  3%
                </div>
              </dd>
            </dl>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Grid de contenido --}}
  <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
    {{-- Actividad reciente --}}
    <div class="bg-white shadow-soft rounded-xl overflow-hidden">
      <div class="p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
          <i class="bx bx-time mr-2"></i>
          Actividad Reciente
        </h3>
        <div class="flow-root">
          <ul class="-my-5 divide-y divide-gray-200">
            <li class="py-4">
              <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                  <div class="h-8 w-8 rounded-full bg-success-100 flex items-center justify-center">
                    <i class="bx bx-plus text-success-600 text-sm"></i>
                  </div>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900 truncate">
                    Nueva empresa registrada
                  </p>
                  <p class="text-sm text-gray-500">
                    Constructora ABC S.A.
                  </p>
                </div>
                <div class="text-sm text-gray-500">
                  Hace 2 horas
                </div>
              </div>
            </li>
            <li class="py-4">
              <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                  <div class="h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center">
                    <i class="bx bx-file text-primary-600 text-sm"></i>
                  </div>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900 truncate">
                    Documento actualizado
                  </p>
                  <p class="text-sm text-gray-500">
                    Certificado de Seguridad
                  </p>
                </div>
                <div class="text-sm text-gray-500">
                  Hace 4 horas
                </div>
              </div>
            </li>
            <li class="py-4">
              <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                  <div class="h-8 w-8 rounded-full bg-warning-100 flex items-center justify-center">
                    <i class="bx bx-user text-warning-600 text-sm"></i>
                  </div>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900 truncate">
                    Nuevo usuario registrado
                  </p>
                  <p class="text-sm text-gray-500">
                    Juan Pérez - Supervisor
                  </p>
                </div>
                <div class="text-sm text-gray-500">
                  Ayer
                </div>
              </div>
            </li>
          </ul>
        </div>
        <div class="mt-6">
          <a href="#" class="text-sm font-medium text-primary-600 hover:text-primary-500">
            Ver toda la actividad
            <i class="bx bx-right-arrow-alt ml-1"></i>
          </a>
        </div>
      </div>
    </div>

    {{-- Accesos rápidos --}}
    <div class="bg-white shadow-soft rounded-xl overflow-hidden">
      <div class="p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
          <i class="bx bx-rocket mr-2"></i>
          Accesos Rápidos
        </h3>
        <div class="grid grid-cols-2 gap-4">
          @role('administrador')
            <a href="{{ route('admin.empresas.create') }}"
               class="group flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:border-primary-300 hover:shadow-sm transition-all">
              <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary-100 group-hover:bg-primary-200 transition-colors">
                <i class="bx bx-plus text-xl text-primary-600"></i>
              </div>
              <span class="mt-2 text-sm font-medium text-gray-900 text-center">Nueva Empresa</span>
            </a>

            <a href="{{ route('admin.documentos.create') }}"
               class="group flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:border-success-300 hover:shadow-sm transition-all">
              <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-success-100 group-hover:bg-success-200 transition-colors">
                <i class="bx bx-file-plus text-xl text-success-600"></i>
              </div>
              <span class="mt-2 text-sm font-medium text-gray-900 text-center">Nuevo Documento</span>
            </a>

            <a href="{{ route('admin.usuarios.create') }}"
               class="group flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:border-warning-300 hover:shadow-sm transition-all">
              <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-warning-100 group-hover:bg-warning-200 transition-colors">
                <i class="bx bx-user-plus text-xl text-warning-600"></i>
              </div>
              <span class="mt-2 text-sm font-medium text-gray-900 text-center">Nuevo Usuario</span>
            </a>

            <a href="{{ route('admin.config.index') }}"
               class="group flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:border-indigo-300 hover:shadow-sm transition-all">
              <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-100 group-hover:bg-indigo-200 transition-colors">
                <i class="bx bx-cog text-xl text-indigo-600"></i>
              </div>
              <span class="mt-2 text-sm font-medium text-gray-900 text-center">Configuración</span>
            </a>
          @endrole
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
