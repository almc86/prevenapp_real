@extends('layouts.app')

@section('title','Editar Documento')

@section('content')
<div class="container">
  <h3>Editar documento</h3>

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.documentos.update', $documento) }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label class="form-label">Nombre</label>
      <input type="text" name="nombre" class="form-control" required maxlength="255"
             value="{{ old('nombre', $documento->nombre) }}">
    </div>

    <div class="mb-3">
      <label class="form-label">Descripción (opcional)</label>
      <input type="text" name="descripcion" class="form-control" maxlength="255"
             value="{{ old('descripcion', $documento->descripcion) }}">
    </div>

    <div class="mb-3">
      <label class="form-label">Ámbito</label>
      <select name="tipo_documento_id" class="form-select" required>
        @foreach($tipos as $t)
          <option value="{{ $t->id }}"
            {{ (int)old('tipo_documento_id', $documento->tipo_documento_id) === (int)$t->id ? 'selected' : '' }}>
            {{ ucfirst($t->nombre) }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="form-check form-switch mb-3">
      <input class="form-check-input" type="checkbox" role="switch" id="estado" name="estado" value="1"
             {{ old('estado', $documento->estado) ? 'checked' : '' }}>
      <label class="form-check-label" for="estado">Activo</label>
      @if(!$documento->estado && $documento->desactivado_at)
        <div class="small text-muted">Desactivado desde {{ $documento->desactivado_at->format('Y-m-d H:i') }}</div>
      @endif
    </div>

    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-primary">Guardar cambios</button>
      <a href="{{ route('admin.documentos.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection
