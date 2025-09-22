@extends('layouts.app')

@section('title', 'Tipos de Cobro')

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
                <i class="bx bx-credit-card mr-2"></i>
                Tipos de Cobro
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Gestiona los tipos de cobro configurados entre empresas principales y contratistas.
            </p>
        </div>
        <div class="mt-4 flex md:mt-0">
            <a href="{{ route('admin.tipos-cobro.create') }}" class="btn btn-primary">
                <i class="bx bx-plus mr-2"></i>
                Nuevo Tipo de Cobro
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
        <form method="GET" action="{{ route('admin.tipos-cobro.index') }}" class="px-6 py-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-5">
                <div class="lg:col-span-2">
                    <label class="form-label">Buscar Empresa</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bx bx-search text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <input type="text"
                               name="q"
                               class="form-control pl-10"
                               placeholder="Nombre de empresa principal o contratista..."
                               value="{{ old('q', request('q')) }}">
                    </div>
                </div>

                <div>
                    <label class="form-label">Tipo de Cobro</label>
                    <select name="tipo_cobro" class="form-select">
                        <option value="">Todos los tipos</option>
                        <option value="pesos" {{ request('tipo_cobro') === 'pesos' ? 'selected' : '' }}>
                            💰 Pesos (CLP)
                        </option>
                        <option value="uf" {{ request('tipo_cobro') === 'uf' ? 'selected' : '' }}>
                            📈 UF
                        </option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Tipo de Pago</label>
                    <select name="tipo_pago" class="form-select">
                        <option value="">Todos los tipos</option>
                        <option value="factura" {{ request('tipo_pago') === 'factura' ? 'selected' : '' }}>
                            📄 Factura
                        </option>
                        <option value="webpay" {{ request('tipo_pago') === 'webpay' ? 'selected' : '' }}>
                            💳 WebPay
                        </option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="1" {{ request('estado') === '1' ? 'selected' : '' }}>
                            ✅ Activo
                        </option>
                        <option value="0" {{ request('estado') === '0' ? 'selected' : '' }}>
                            ❌ Inactivo
                        </option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <a href="{{ route('admin.tipos-cobro.index') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
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

    {{-- Tabla de Tipos de Cobro --}}
    <div class="bg-white dark:bg-gray-800 shadow-soft rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    Lista de Tipos de Cobro
                    @if(isset($tiposCobro) && method_exists($tiposCobro, 'total'))
                        <span class="ml-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                            ({{ $tiposCobro->total() }} tipos encontrados)
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
                                <i class="bx bx-buildings"></i>
                                <span>Empresa Principal</span>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <i class="bx bx-building"></i>
                                <span>Empresa Contratista</span>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <i class="bx bx-credit-card"></i>
                                <span>Tipo Cobro</span>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <i class="bx bx-wallet"></i>
                                <span>Tipo Pago</span>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <i class="bx bx-bar-chart"></i>
                                <span>Rangos</span>
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
                                <i class="bx bx-calendar"></i>
                                <span>Creado</span>
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
                    @forelse($tiposCobro as $tipoCobro)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $tipoCobro->empresaPrincipal->nombre_empresa }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $tipoCobro->empresaPrincipal->rut_empresa }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $tipoCobro->empresaContratista->nombre_empresa }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $tipoCobro->empresaContratista->rut_empresa }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($tipoCobro->tipo_cobro === 'uf')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                        <i class="bx bx-trending-up mr-1"></i>
                                        {{ $tipoCobro->tipo_cobro_formatted }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                        <i class="bx bx-dollar mr-1"></i>
                                        {{ $tipoCobro->tipo_cobro_formatted }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($tipoCobro->tipo_pago === 'webpay')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                        <i class="bx bx-credit-card mr-1"></i>
                                        {{ $tipoCobro->tipo_pago_formatted }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        <i class="bx bx-file mr-1"></i>
                                        {{ $tipoCobro->tipo_pago_formatted }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    @foreach($tipoCobro->rangos->take(2) as $rango)
                                        <div>{{ $rango->trabajadores_desde }}-{{ $rango->trabajadores_hasta }}: {{ $rango->monto_formatted }}</div>
                                    @endforeach
                                    @if($tipoCobro->rangos->count() > 2)
                                        <div class="text-gray-500 dark:text-gray-400">... +{{ $tipoCobro->rangos->count() - 2 }} más</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($tipoCobro->activo)
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
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $tipoCobro->created_at->format('Y-m-d') }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $tipoCobro->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <!-- Botón Ver -->
                                    <a href="{{ route('admin.tipos-cobro.show', $tipoCobro) }}"
                                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium"
                                       title="Ver detalles">
                                        <i class="bx bx-show"></i> Ver
                                    </a>

                                    <!-- Separador -->
                                    <span class="text-gray-300 dark:text-gray-600">|</span>

                                    <!-- Botón Editar -->
                                    <a href="{{ route('admin.tipos-cobro.edit', $tipoCobro) }}"
                                       class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300 font-medium"
                                       title="Editar tipo de cobro">
                                        <i class="bx bx-edit"></i> Editar
                                    </a>

                                    <!-- Separador -->
                                    <span class="text-gray-300 dark:text-gray-600">|</span>

                                    <!-- Botón Eliminar -->
                                    <form method="POST"
                                          action="{{ route('admin.tipos-cobro.destroy', $tipoCobro) }}"
                                          class="inline"
                                          onsubmit="return confirm('¿Estás seguro de eliminar este tipo de cobro?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 font-medium"
                                                title="Eliminar tipo de cobro">
                                            <i class="bx bx-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="bx bx-credit-card text-4xl text-gray-400 dark:text-gray-500 mb-2"></i>
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-1">No hay tipos de cobro</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Comienza creando el primer tipo de cobro.</p>
                                    <a href="{{ route('admin.tipos-cobro.create') }}" class="btn btn-primary">
                                        <i class="bx bx-plus mr-2"></i>
                                        Crear Tipo de Cobro
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if(method_exists($tiposCobro, 'links'))
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $tiposCobro->links() }}
            </div>
        @endif
    </div>
</div>
@endsection