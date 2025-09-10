@extends('layouts.app')

@section('title','Tipos de Documento')

@section('content')
<div class="container">
  @if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
  @endif

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">Tipos de Documento</h3>
    <a href="{{ route('admin.tipos-documento.create') }}" class="btn btn-primary">Nuevo tipo</a>
  </div>

  <form method="GET" action="{{ route('admin.tipos-documento.index') }}" class="card card-body mb-3">
    <div class="row g-2 align-items-end">
      <div class="col-md-4">
        <label class="form-label">Buscar</label>
        <input type="text" name="q" class="form-control" placeholder="Ámbito o descripción..."
               value="{{ old('q', $q ?? '') }}">
      </div>

      <div class="col-md-3">
        <label class="form-label">Ámbito</label>
        <select name="tipo" class="form-select">
          <option value="" {{ $tipo==='' ? 'selected' : '' }}>Todos</option>
          <option value="trabajador" {{ $tipo==='trabajador' ? 'selected' : '' }}>Trabajador</option>
          <option value="empresa"    {{ $tipo==='empresa' ? 'selected' : '' }}>Empresa</option>
          <option value="flota"      {{ $tipo==='flota' ? 'selected' : '' }}>Flota</option>
        </select>
      </div>

      <div class="col-md-3">
        <label class="form-label">Estado</label>
        <select name="estado" class="form-select">
          <option value=""  {{ $estado==='' ? 'selected' : '' }}>Todos</option>
          <option value="1" {{ $estado==='1' ? 'selected' : '' }}>Activos</option>
          <option value="0" {{ $estado==='0' ? 'selected' : '' }}>Inactivos</option>
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

      <div class="col-12 mt-2 d-grid">
        <button class="btn btn-primary">Filtrar</button>
        <div class="mt-2">
          <a href="{{ route('admin.tipos-documento.index') }}" class="small">Limpiar filtros</a>
        </div>
      </div>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>Ámbito</th>
          <th>Descripción</th>
          <th>Estado</th>
          <th style="width:160px;">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($tipos as $t)
          <tr>
            <td>
              @switch($t->nombre)
                @case('trabajador') <span class="badge bg-info">Trabajador</span> @break
                @case('empresa')    <span class="badge bg-primary">Empresa</span>   @break
                @case('flota')      <span class="badge bg-warning text-dark">Flota</span> @break
                @default            <span class="badge bg-secondary">{{ $t->nombre }}</span>
              @endswitch
            </td>
            <td>{{ $t->descripcion }}</td>
            <td>
              <span class="badge {{ $t->estado ? 'bg-success' : 'bg-secondary' }}">
                {{ $t->estado ? 'Activo' : 'Inactivo' }}
              </span>
            </td>
            <td class="d-flex gap-2">
              <form method="POST" action="{{ route('admin.tipos-documento.destroy', $t) }}"
                    onsubmit="return confirm('¿Seguro que deseas {{ $t->estado ? 'desactivar' : 'activar' }} este tipo?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm {{ $t->estado ? 'btn-outline-danger' : 'btn-outline-success' }}">
                  {{ $t->estado ? 'Desactivar' : 'Activar' }}
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="text-center text-muted">No hay tipos registrados.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if(method_exists($tipos, 'links'))
    <div class="mt-3">
      {{ $tipos->links() }}
    </div>
  @endif
</div>
@endsection
