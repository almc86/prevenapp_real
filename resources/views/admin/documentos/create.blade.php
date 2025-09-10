@extends('layouts.app')

@section('title','Nuevo Documento')

@section('content')
<div class="container">
  <h3>Nuevo documento</h3>

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.documentos.store') }}">
    @csrf

    <div class="mb-3">
      <label class="form-label">Nombre</label>
      <input type="text" name="nombre" class="form-control" required maxlength="255"
             value="{{ old('nombre') }}" placeholder="Ej: Licencia de Conducir clase B">
    </div>

    <div class="mb-3">
      <label class="form-label">Descripción (opcional)</label>
      <input type="text" name="descripcion" class="form-control" maxlength="255"
             value="{{ old('descripcion') }}" placeholder="Notas o alcance del documento">
    </div>

    <div class="mb-3">
      <label class="form-label">Ámbito</label>
      <select name="tipo_documento_id" class="form-select" required>
        <option value="">Seleccione...</option>
        @foreach($tipos as $t)
          <option value="{{ $t->id }}" {{ old('tipo_documento_id')==$t->id ? 'selected' : '' }}>
            {{ ucfirst($t->nombre) }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-primary">Guardar</button>
      <a href="{{ route('admin.documentos.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection
