@extends('layouts.app')

@section('title', 'Tipos de Documento')

@section('content')
<div class="space-y-6">
    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            <div class="flex items-center">
                <i class="bx bx-check-circle text-lg mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- Header --}}
    <div class="md:flex md:items-center md:justify-between">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate sm:text-3xl sm:tracking-tight">
                <i class="bx bx-file mr-2"></i>
                Tipos de Documento
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Gestiona los tipos de documentos disponibles en el sistema.
            </p>
        </div>
        <div class="mt-4 flex md:mt-0">
            <a href="{{ route('admin.tipos-documento.create') }}" class="btn btn-primary">
                <i class="bx bx-plus mr-2"></i>
                Nuevo Tipo
            </a>
        </div>
    </div>

    {{-- Filtros Mejorados --}}
    <div class="bg-white dark:bg-gray-800 shadow-soft rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                <i class="bx bx-filter mr-2"></i>
                Filtros de Búsqueda
            </h3>
        </div>
        <form method="GET" action="{{ route('admin.tipos-documento.index') }}" class="px-6 py-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="lg:col-span-1">
                    <label class="form-label">Buscar Tipo</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bx bx-search text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <input type="text"
                               name="q"
                               class="form-control pl-10"
                               placeholder="Ámbito o descripción..."
                               value="{{ old('q', $q ?? '') }}">
                    </div>
                </div>

                <div>
                    <label class="form-label">Filtrar por Ámbito</label>
                    <select name="tipo" class="form-select">
                        <option value="">Todos los ámbitos</option>
                        <option value="trabajador" {{ ($tipo ?? '') === 'trabajador' ? 'selected' : '' }}>
                            👤 Trabajador
                        </option>
                        <option value="empresa" {{ ($tipo ?? '') === 'empresa' ? 'selected' : '' }}>
                            🏢 Empresa
                        </option>
                        <option value="flota" {{ ($tipo ?? '') === 'flota' ? 'selected' : '' }}>
                            🚛 Flota
                        </option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="1" {{ ($estado ?? '') === '1' ? 'selected' : '' }}>
                            ✅ Activo
                        </option>
                        <option value="0" {{ ($estado ?? '') === '0' ? 'selected' : '' }}>
                            ❌ Inactivo
                        </option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Resultados por página</label>
                    <select name="per_page" class="form-select">
                        @foreach([10,15,25,50,100] as $pp)
                            <option value="{{ $pp }}" {{ (int)($perPage ?? 15) === $pp ? 'selected' : '' }}>
                                {{ $pp }} tipos
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <a href="{{ route('admin.tipos-documento.index') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                    <i class="bx bx-refresh mr-1"></i>
                    Limpiar filtros
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-search mr-2"></i>
                    Aplicar Filtros
                </button>
            </div>
        </form>
    </div>

    {{-- Tabla de Tipos de Documento --}}
    <div class="bg-white dark:bg-gray-800 shadow-soft rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    Lista de Tipos de Documento
                    @if(isset($tipos) && method_exists($tipos, 'total'))
                        <span class="ml-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                            ({{ $tipos->total() }} tipos encontrados)
                        </span>
                    @endif
                </h3>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <i class="bx bx-category"></i>
                                <span>Ámbito</span>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <i class="bx bx-text"></i>
                                <span>Descripción</span>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <i class="bx bx-info-circle"></i>
                                <span>Estado</span>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <i class="bx bx-cog"></i>
                                <span>Acciones</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($tipos as $tipo)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @switch($tipo->nombre)
                                    @case('trabajador')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                            <i class="bx bx-user mr-1"></i>
                                            Trabajador
                                        </span>
                                        @break
                                    @case('empresa')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300">
                                            <i class="bx bx-buildings mr-1"></i>
                                            Empresa
                                        </span>
                                        @break
                                    @case('flota')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                            <i class="bx bx-car mr-1"></i>
                                            Flota
                                        </span>
                                        @break
                                    @default
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            <i class="bx bx-file mr-1"></i>
                                            {{ ucfirst($tipo->nombre) }}
                                        </span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $tipo->descripcion }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($tipo->estado)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                        <div class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1.5"></div>
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        <div class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></div>
                                        Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <!-- Botón Editar -->
                                    <a href="{{ route('admin.tipos-documento.edit', $tipo) }}"
                                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium"
                                       title="Editar tipo">
                                        <i class="bx bx-edit"></i> Editar
                                    </a>

                                    <!-- Separador -->
                                    <span class="text-gray-300 dark:text-gray-600">|</span>

                                    <!-- Botón Activar/Desactivar -->
                                    <form method="POST"
                                          action="{{ route('admin.tipos-documento.destroy', $tipo) }}"
                                          class="inline"
                                          onsubmit="return confirm('¿Seguro que deseas {{ $tipo->estado ? 'desactivar' : 'activar' }} este tipo?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="font-medium {{ $tipo->estado ? 'text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300' : 'text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300' }}"
                                                title="{{ $tipo->estado ? 'Desactivar tipo' : 'Activar tipo' }}">
                                            <i class="bx {{ $tipo->estado ? 'bx-toggle-right' : 'bx-toggle-left' }}"></i>
                                            {{ $tipo->estado ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="bx bx-file-blank text-4xl text-gray-400 dark:text-gray-500 mb-2"></i>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-1">No hay tipos de documento</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Comienza creando el primer tipo de documento.</p>
                                    <a href="{{ route('admin.tipos-documento.create') }}" class="btn btn-primary">
                                        <i class="bx bx-plus mr-2"></i>
                                        Crear Tipo
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if(method_exists($tipos, 'links'))
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $tipos->links() }}
            </div>
        @endif
    </div>
</div>
@endsection