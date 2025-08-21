{{-- resources/views/admin/usuarios/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="m-0">Usuarios</h3>
        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">Nuevo usuario</a>
    </div>

    {{-- Filtros --}}
    <form method="GET" action="{{ route('admin.usuarios.index') }}" class="card card-body mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Buscar</label>
                <input type="text" name="q" class="form-control" placeholder="Nombre o email..."
                       value="{{ old('q', $q ?? '') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label">Rol</label>
                <select name="role_id" class="form-select">
                    <option value="">Todos</option>
                    @foreach($roles as $r)
                        <option value="{{ $r->id }}" {{ (string)($roleId ?? '') === (string)$r->id ? 'selected' : '' }}>
                            {{ ucfirst($r->name) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select">
                    <option value="">Todos</option>
                    <option value="1" {{ (string)($estado ?? '') === '1' ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ (string)($estado ?? '') === '0' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Por página</label>
                <select name="per_page" class="form-select">
                    @foreach([10,15,25,50,100] as $pp)
                        <option value="{{ $pp }}" {{ (int)($perPage ?? 15) === $pp ? 'selected' : '' }}>{{ $pp }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-1 d-grid">
                <button class="btn btn-primary">Filtrar</button>
            </div>
        </div>
        <div class="mt-2">
            <a href="{{ route('admin.usuarios.index') }}" class="small">Limpiar filtros</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol(es)</th>
                    <th>Estado</th>
                    <th style="width:160px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>

                        <td>
                            @php $rolesUser = $user->getRoleNames(); @endphp
                            @if($rolesUser->isNotEmpty())
                                @foreach($rolesUser as $rn)
                                    <span class="badge bg-info text-dark me-1 mb-1">{{ $rn }}</span>
                                @endforeach
                            @else
                                —
                            @endif
                        </td>

                        <td>
                            <span class="badge {{ $user->activo ? 'bg-success' : 'bg-secondary' }}">
                                {{ $user->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>

                        <td class="d-flex gap-2">
                            <a href="{{ route('admin.usuarios.edit', $user) }}" class="btn btn-sm btn-warning">
                                Editar
                            </a>
                            <form method="POST"
                                  action="{{ route('admin.usuarios.destroy', $user) }}"
                                  onsubmit="return confirm('¿Seguro que deseas {{ $user->activo ? 'desactivar' : 'activar' }} este usuario?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm {{ $user->activo ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                    {{ $user->activo ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No hay usuarios registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($usuarios, 'links'))
        <div class="mt-3">
            {{ $usuarios->links() }}
        </div>
    @endif
</div>
@endsection
