@extends('layouts.app')

@section('title','Cargos')

@section('content')
<div class="container">
  @if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
  @endif

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">Cargos</h3>
    <a href="{{ route('admin.cargos.create') }}" class="btn btn-primary">Nuevo cargo</a>
  </div>

  <form method="GET" action="{{ route('admin.cargos.index') }}" class="card card-body mb-3">
    <div class="row g-2 align-items-end">
      <div class="col-md-6">
        <label class="form-label">Buscar</label>
        <input type="text" name="q" class="form-control" placeholder="Nombre del cargo..."
               value="{{ old('q', $q ?? '') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label">Por página</label>
        <select name="per_page" class="form-select">
          @foreach([10,15,25,50,100] as $pp)
            <option value="{{ $pp }}" {{ (int)($perPage ?? 15) === $pp ? 'selected' : '' }}>{{ $pp }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3 d-grid">
        <button class="btn btn-primary">Filtrar</button>
      </div>
    </div>
    <div class="mt-2">
      <a href="{{ route('admin.cargos.index') }}" class="small">Limpiar filtros</a>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>Nombre</th>
          <th style="width:180px;">Creado</th>
        </tr>
      </thead>
      <tbody>
        @forelse($cargos as $cargo)
          <tr>
            <td>{{ $cargo->nombre }}</td>
            <td>{{ optional($cargo->created_at)->format('Y-m-d H:i') }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="2" class="text-center text-muted">No hay cargos registrados.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if(method_exists($cargos, 'links'))
    <div class="mt-3">
        {{ $cargos->links() }}
    </div>
  @endif
</div>
@endsection
