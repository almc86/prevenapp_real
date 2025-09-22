@extends('layouts.app')

@section('title', 'Categorías')

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
                <i class="bx bx-category mr-2"></i>
                Categorías
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Gestiona las categorías disponibles para clasificar documentos en el sistema.
            </p>
        </div>
        <div class="mt-4 flex md:mt-0">
            <a href="{{ route('admin.categorias.create') }}" class="btn btn-primary">
                <i class="bx bx-plus mr-2"></i>
                Nueva categoría
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
        <form method="GET" action="{{ route('admin.categorias.index') }}" class="px-6 py-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="lg:col-span-2">
                    <label class="form-label">Buscar Categoría</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bx bx-search text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <input type="text"
                               name="q"
                               class="form-control pl-10"
                               placeholder="Nombre o descripción..."
                               value="{{ old('q', $q ?? '') }}">
                    </div>
                </div>

                <div>
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="1" {{ ($estado ?? '') === '1' ? 'selected' : '' }}>
                            ✅ Activas
                        </option>
                        <option value="0" {{ ($estado ?? '') === '0' ? 'selected' : '' }}>
                            ❌ Inactivas
                        </option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Resultados por página</label>
                    <select name="per_page" class="form-select">
                        @foreach([10,15,25,50,100] as $pp)
                            <option value="{{ $pp }}" {{ (int)($perPage ?? 15) === $pp ? 'selected' : '' }}>
                                {{ $pp }} categorías
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <a href="{{ route('admin.categorias.index') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
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

    {{-- Tabla de Categorías --}}
    <div class="bg-white dark:bg-gray-800 shadow-soft rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    Lista de Categorías
                    @if(isset($categorias) && method_exists($categorias, 'total'))
                        <span class="ml-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                            ({{ $categorias->total() }} categorías encontradas)
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
                                <span>Nombre</span>
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
                    @forelse($categorias as $c)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-100 dark:bg-primary-800 mr-3">
                                        <i class="bx bx-category text-primary-600 dark:text-primary-300"></i>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $c->nombre }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $c->descripcion }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($c->estado)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                        <div class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1.5"></div>
                                        Activa
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        <div class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></div>
                                        Inactiva
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <!-- Botón Editar -->
                                    <a href="{{ route('admin.categorias.edit', $c) }}"
                                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium"
                                       title="Editar categoría">
                                        <i class="bx bx-edit"></i> Editar
                                    </a>

                                    <!-- Separador -->
                                    <span class="text-gray-300 dark:text-gray-600">|</span>

                                    <!-- Botón Activar/Desactivar -->
                                    <form method="POST"
                                          action="{{ route('admin.categorias.destroy', $c) }}"
                                          class="inline"
                                          onsubmit="return confirm('¿Seguro que deseas {{ $c->estado ? 'desactivar' : 'activar' }} esta categoría?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="font-medium {{ $c->estado ? 'text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300' : 'text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300' }}"
                                                title="{{ $c->estado ? 'Desactivar categoría' : 'Activar categoría' }}">
                                            <i class="bx {{ $c->estado ? 'bx-toggle-right' : 'bx-toggle-left' }}"></i>
                                            {{ $c->estado ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="bx bx-category text-4xl text-gray-400 dark:text-gray-500 mb-2"></i>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-1">No hay categorías registradas</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Comienza creando la primera categoría.</p>
                                    <a href="{{ route('admin.categorias.create') }}" class="btn btn-primary">
                                        <i class="bx bx-plus mr-2"></i>
                                        Crear Categoría
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if(method_exists($categorias, 'links'))
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $categorias->links() }}
            </div>
        @endif
    </div>
</div>
@endsection