@extends('layouts.app')

@section('title', 'Cargos')

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
                <i class="bx bx-briefcase mr-2"></i>
                Cargos
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Gestiona los cargos disponibles para asignar a los trabajadores en el sistema.
            </p>
        </div>
        <div class="mt-4 flex md:mt-0">
            <a href="{{ route('admin.cargos.create') }}" class="btn btn-primary">
                <i class="bx bx-plus mr-2"></i>
                Nuevo cargo
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
        <form method="GET" action="{{ route('admin.cargos.index') }}" class="px-6 py-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <label class="form-label">Buscar Cargo</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bx bx-search text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <input type="text"
                               name="q"
                               class="form-control pl-10"
                               placeholder="Nombre del cargo..."
                               value="{{ old('q', $q ?? '') }}">
                    </div>
                </div>

                <div>
                    <label class="form-label">Resultados por página</label>
                    <select name="per_page" class="form-select">
                        @foreach([10,15,25,50,100] as $pp)
                            <option value="{{ $pp }}" {{ (int)($perPage ?? 15) === $pp ? 'selected' : '' }}>
                                {{ $pp }} cargos
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <a href="{{ route('admin.cargos.index') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
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

    {{-- Tabla de Cargos --}}
    <div class="bg-white dark:bg-gray-800 shadow-soft rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    Lista de Cargos
                    @if(isset($cargos) && method_exists($cargos, 'total'))
                        <span class="ml-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                            ({{ $cargos->total() }} cargos encontrados)
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
                                <i class="bx bx-briefcase"></i>
                                <span>Nombre del Cargo</span>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <i class="bx bx-calendar"></i>
                                <span>Fecha de Creación</span>
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
                    @forelse($cargos as $cargo)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-800 mr-3">
                                        <i class="bx bx-briefcase text-blue-600 dark:text-blue-300"></i>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $cargo->nombre }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ optional($cargo->created_at)->format('Y-m-d') }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ optional($cargo->created_at)->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <!-- Botón Ver/Editar -->
                                    <a href="#"
                                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium"
                                       title="Ver detalles del cargo">
                                        <i class="bx bx-show"></i> Ver
                                    </a>

                                    <!-- Separador -->
                                    <span class="text-gray-300 dark:text-gray-600">|</span>

                                    <!-- Botón Editar -->
                                    <a href="#"
                                       class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300 font-medium"
                                       title="Editar cargo">
                                        <i class="bx bx-edit"></i> Editar
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="bx bx-briefcase text-4xl text-gray-400 dark:text-gray-500 mb-2"></i>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-1">No hay cargos registrados</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Comienza creando el primer cargo.</p>
                                    <a href="{{ route('admin.cargos.create') }}" class="btn btn-primary">
                                        <i class="bx bx-plus mr-2"></i>
                                        Crear Cargo
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if(method_exists($cargos, 'links'))
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $cargos->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
</div>
@endsection