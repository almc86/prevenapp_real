@extends('layouts.app')

@section('title','Categorías')

@section('content')
<div class="container">
  @if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
  @endif

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">Categorías</h3>
    <a href="{{ route('admin.categorias.create') }}" class="btn btn-primary">Nueva categoría</a>
  </div>

  <form method="GET" action="{{ route('admin.categorias.index') }}" class="card card-body mb-3">
    <div class="row g-2 align-items-end">
      <div class="col-md-5">
        <label class="form-label">Buscar</label>
        <input type="text" name="q" class="form-control" placeholder="Nombre o descripción..."
               value="{{ old('q', $q ?? '') }}">
      </div>

      <div class="col-md-3">
        <label class="form-label">Estado</label>
        <select name="estado" class="form-select">
          <option value=""  {{ $estado==='' ? 'selected' : '' }}>Todos</option>
          <option value="1" {{ $estado==='1' ? 'selected' : '' }}>Activas</option>
          <option value="0" {{ $estado==='0' ? 'selected' : '' }}>Inactivas</option>
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

      <div class="col-md-2 d-grid">
        <button class="btn btn-primary">Filtrar</button>
      </div>
    </div>
    <div class="mt-2">
      <a href="{{ route('admin.categorias.index') }}" class="small">Limpiar filtros</a>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Descripción</th>
          <th>Estado</th>
          <th style="width:180px;">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($categorias as $c)
          <tr>
            <td>{{ $c->nombre }}</td>
            <td>{{ $c->descripcion }}</td>
            <td>
              <span class="badge {{ $c->estado ? 'bg-success' : 'bg-secondary' }}">
                {{ $c->estado ? 'Activa' : 'Inactiva' }}
              </span>
            </td>
            <td class="d-flex gap-2">
              <a href="{{ route('admin.categorias.edit', $c) }}" class="btn btn-sm btn-warning">
                Editar
              </a>

              <form method="POST" action="{{ route('admin.categorias.destroy', $c) }}"
                    onsubmit="return confirm('¿Seguro que deseas {{ $c->estado ? 'desactivar' : 'activar' }} esta categoría?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm {{ $c->estado ? 'btn-outline-danger' : 'btn-outline-success' }}">
                  {{ $c->estado ? 'Desactivar' : 'Activar' }}
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="4" class="text-center text-muted">No hay categorías registradas.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if(method_exists($categorias, 'links'))
    <div class="mt-3">
      {{ $categorias->links() }}
    </div>
  @endif
</div>
@endsection
