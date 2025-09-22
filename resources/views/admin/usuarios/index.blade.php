@extends('layouts.app')

@section('title', 'Usuarios')

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
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                <i class="bx bx-group mr-2"></i>
                Gestión de Usuarios
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Administra los usuarios del sistema y sus permisos.
            </p>
        </div>
        <div class="mt-4 flex md:mt-0">
            <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">
                <i class="bx bx-plus mr-2"></i>
                Nuevo Usuario
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
        <form method="GET" action="{{ route('admin.usuarios.index') }}" class="px-6 py-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="lg:col-span-2">
                    <label class="form-label">Buscar Usuario</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bx bx-search text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <input type="text"
                               name="q"
                               class="form-control pl-10"
                               placeholder="Nombre, email o información del usuario..."
                               value="{{ old('q', $q ?? '') }}">
                    </div>
                </div>

                <div>
                    <label class="form-label">Filtrar por Rol</label>
                    <select name="role_id" class="form-select">
                        <option value="">Todos los roles</option>
                        @foreach($roles as $r)
                            <option value="{{ $r->id }}" {{ (string)($roleId ?? '') === (string)$r->id ? 'selected' : '' }}>
                                <i class="bx bx-shield mr-1"></i>
                                {{ ucfirst($r->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="1" {{ (string)($estado ?? '') === '1' ? 'selected' : '' }}>
                            ✅ Activo
                        </option>
                        <option value="0" {{ (string)($estado ?? '') === '0' ? 'selected' : '' }}>
                            ❌ Inactivo
                        </option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Resultados por página</label>
                    <select name="per_page" class="form-select">
                        @foreach([10,15,25,50,100] as $pp)
                            <option value="{{ $pp }}" {{ (int)($perPage ?? 15) === $pp ? 'selected' : '' }}>
                                {{ $pp }} usuarios
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <a href="{{ route('admin.usuarios.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
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

    {{-- Tabla de Usuarios Mejorada --}}
    <div class="bg-white dark:bg-gray-800 shadow-soft rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    Lista de Usuarios
                    @if(isset($usuarios) && method_exists($usuarios, 'total'))
                        <span class="ml-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                            ({{ $usuarios->total() }} usuarios encontrados)
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
                                <i class="bx bx-user"></i>
                                <span>Usuario</span>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <i class="bx bx-envelope"></i>
                                <span>Email</span>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <i class="bx bx-shield"></i>
                                <span>Roles</span>
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
                    @forelse($usuarios as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-purple-400 to-pink-400 flex items-center justify-center">
                                            <span class="text-sm font-medium text-white">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $user->name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            Registrado {{ $user->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $user->email }}</div>
                                @if($user->email_verified_at)
                                    <div class="text-sm text-green-600">
                                        <i class="bx bx-check-circle mr-1"></i>
                                        Verificado
                                    </div>
                                @else
                                    <div class="text-sm text-yellow-600">
                                        <i class="bx bx-time mr-1"></i>
                                        Sin verificar
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php $rolesUser = $user->getRoleNames(); @endphp
                                @if($rolesUser->isNotEmpty())
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($rolesUser as $rn)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $rn === 'administrador' ? 'bg-red-100 text-red-800' :
                                                   ($rn === 'principal' ? 'bg-blue-100 text-blue-800' :
                                                   ($rn === 'prevencionista' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                                                <i class="bx bx-shield mr-1"></i>
                                                {{ ucfirst($rn) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500 text-sm">Sin rol asignado</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->activo)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <div class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1.5"></div>
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <div class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></div>
                                        Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <!-- Botón Editar -->
                                    <a href="{{ route('admin.usuarios.edit', $user) }}"
                                       class="text-blue-600 hover:text-blue-800 font-medium"
                                       title="Editar usuario">
                                        <i class="bx bx-edit"></i> Editar
                                    </a>

                                    <!-- Separador -->
                                    <span class="text-gray-300">|</span>

                                    <!-- Botón Activar/Desactivar -->
                                    <form method="POST"
                                          action="{{ route('admin.usuarios.destroy', $user) }}"
                                          class="inline"
                                          onsubmit="return confirm('¿Seguro que deseas {{ $user->activo ? 'desactivar' : 'activar' }} este usuario?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="font-medium {{ $user->activo ? 'text-red-600 hover:text-red-800' : 'text-green-600 hover:text-green-800' }}"
                                                title="{{ $user->activo ? 'Desactivar usuario' : 'Activar usuario' }}">
                                            <i class="bx {{ $user->activo ? 'bx-user-x' : 'bx-user-check' }}"></i>
                                            {{ $user->activo ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="bx bx-user-x text-4xl text-gray-400 mb-2"></i>
                                    <h3 class="text-sm font-medium text-gray-900 mb-1">No hay usuarios registrados</h3>
                                    <p class="text-sm text-gray-500 mb-4">Comienza creando el primer usuario del sistema.</p>
                                    <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">
                                        <i class="bx bx-plus mr-2"></i>
                                        Crear Usuario
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if(method_exists($usuarios, 'links'))
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $usuarios->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
