@extends('layouts.app')

@section('title','Documentos')

@section('content')
<div class="container">
  @if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
  @endif

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">Documentos</h3>
    <a href="{{ route('admin.documentos.create') }}" class="btn btn-primary">Nuevo documento</a>
  </div>

  <form method="GET" action="{{ route('admin.documentos.index') }}" class="card card-body mb-3">
    <div class="row g-2 align-items-end">
      <div class="col-md-4">
        <label class="form-label">Buscar</label>
        <input type="text" name="q" class="form-control" placeholder="Nombre o descripción..."
               value="{{ old('q', $q ?? '') }}">
      </div>

      <div class="col-md-3">
        <label class="form-label">Ámbito</label>
        <select name="tipo_documento_id" class="form-select">
          <option value="" {{ $tipoId==='' ? 'selected' : '' }}>Todos</option>
          @foreach($tipos as $t)
            <option value="{{ $t->id }}" {{ (string)$tipoId===(string)$t->id ? 'selected' : '' }}>
              {{ ucfirst($t->nombre) }}
            </option>
          @endforeach
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
          <a href="{{ route('admin.documentos.index') }}" class="small">Limpiar filtros</a>
        </div>
      </div>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Ámbito</th>
          <th>Descripción</th>
          <th>Creado por</th>
          <th>Creado</th>
          <th>Estado</th>
          <th style="width:160px;">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($docs as $d)
          <tr>
            <td>{{ $d->nombre }}</td>
            <td>
              @switch(optional($d->tipo)->nombre)
                @case('trabajador') <span class="badge bg-info">Trabajador</span> @break
                @case('empresa')    <span class="badge bg-primary">Empresa</span>   @break
                @case('flota')      <span class="badge bg-warning text-dark">Flota</span> @break
                @default            <span class="badge bg-secondary">{{ optional($d->tipo)->nombre }}</span>
              @endswitch
            </td>
            <td>{{ $d->descripcion }}</td>
            <td>{{ optional($d->creador)->name ?? '—' }}</td>
            <td>{{ optional($d->created_at)->format('Y-m-d H:i') }}</td>
            <td>
              <span class="badge {{ $d->estado ? 'bg-success' : 'bg-secondary' }}">
                {{ $d->estado ? 'Activo' : 'Inactivo' }}
              </span>
              @if(!$d->estado && $d->desactivado_at)
                <div class="small text-muted">Desde {{ $d->desactivado_at->format('Y-m-d H:i') }}</div>
              @endif
            </td>
            <td class="d-flex gap-2">
                <a href="{{ route('admin.documentos.edit', $d) }}" class="btn btn-sm btn-warning">Editar</a>

                <form method="POST" action="{{ route('admin.documentos.destroy', $d) }}"
                        onsubmit="return confirm('¿Seguro que deseas {{ $d->estado ? 'desactivar' : 'activar' }} este documento?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm {{ $d->estado ? 'btn-outline-danger' : 'btn-outline-success' }}">
                    {{ $d->estado ? 'Desactivar' : 'Activar' }}
                    </button>
                </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center text-muted">No hay documentos registrados.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if(method_exists($docs, 'links'))
    <div class="mt-3">
      {{ $docs->links() }}
    </div>
  @endif
</div>
@endsection
