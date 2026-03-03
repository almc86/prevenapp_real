@extends('layouts.app')

@section('title','Nueva Categoría')

@section('content')
<div class="container">
  <h3>Nueva categoría</h3>

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.categorias.store') }}">
    @csrf

    <div class="mb-3">
      <label class="form-label">Nombre</label>
      <input type="text" name="nombre" class="form-control" required maxlength="255"
             value="{{ old('nombre') }}" placeholder="Ej: Seguridad, RRHH, Legal">
    </div>

    <div class="mb-3">
      <label class="form-label">Descripción (opcional)</label>
      <input type="text" name="descripcion" class="form-control" maxlength="255"
             value="{{ old('descripcion') }}" placeholder="Breve descripción de la categoría">
    </div>

    <div class="mb-3">
      <label class="form-label">Ámbito *</label>
      <select name="ambito" class="form-select" required>
        <option value="">Seleccione ámbito...</option>
        @foreach(\App\Models\Categoria::AMBITOS as $key => $label)
          <option value="{{ $key }}" {{ old('ambito') == $key ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
      </select>
      <small class="form-text text-muted">Define en qué sección aparecerá esta categoría</small>
    </div>

    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-primary">Guardar</button>
      <a href="{{ route('admin.categorias.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection
