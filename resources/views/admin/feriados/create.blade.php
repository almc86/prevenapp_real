@extends('layouts.app')

@section('title','Nuevo feriado empresarial')
@section('skip-template-js', true)

@push('head')
<script>
  window.Helpers = window.Helpers || {};
  window.Helpers.setCollapsed = window.Helpers.setCollapsed || function(){};
</script>
@endpush

@section('content')
<div class="container" style="max-width: 760px;">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Nuevo feriado empresarial</h2>
    <a href="{{ route('admin.feriados.index') }}" class="btn btn-outline-secondary">
      <i class="bx bx-arrow-back"></i> Volver
    </a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <form method="POST" action="{{ route('admin.feriados.store') }}" novalidate>
        @csrf
        <input type="hidden" name="es_empresarial" value="1">

        <div class="row g-3">
          <div class="col-md-4">
            <label for="fecha_feriado_date" class="form-label">Fecha</label>
            <input type="date"
                   id="fecha_feriado_date"
                   name="fecha_feriado_date"
                   class="form-control @error('fecha_feriado_date') is-invalid @enderror"
                   value="{{ old('fecha_feriado_date') }}"
                   required>
            @error('fecha_feriado_date')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-8">
            <label for="descripcion_feriado" class="form-label">Descripción</label>
            <input type="text"
                   id="descripcion_feriado"
                   name="descripcion_feriado"
                   class="form-control @error('descripcion_feriado') is-invalid @enderror"
                   value="{{ old('descripcion_feriado') }}"
                   placeholder="Ej: Cierre corporativo por inventario"
                   maxlength="255"
                   required>
            @error('descripcion_feriado')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="mt-4 d-flex gap-2">
          <button type="submit" class="btn btn-primary">
            <i class="bx bx-save"></i> Guardar
          </button>
          <a href="{{ route('admin.feriados.index') }}" class="btn btn-light">Cancelar</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
