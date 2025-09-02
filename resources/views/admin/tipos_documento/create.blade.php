@extends('layouts.app')

@section('title','Nuevo Tipo de Documento')

@section('content')
<div class="container">
  <h3>Nuevo tipo de documento</h3>

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.tipos-documento.store') }}">
    @csrf

    <div class="mb-3">
      <label class="form-label">Ámbito</label>
      <select name="nombre" class="form-select" required>
        <option value="">Seleccione...</option>
        <option value="trabajador" {{ old('nombre')==='trabajador' ? 'selected' : '' }}>Trabajador</option>
        <option value="empresa"    {{ old('nombre')==='empresa' ? 'selected' : '' }}>Empresa</option>
        <option value="flota"      {{ old('nombre')==='flota' ? 'selected' : '' }}>Flota</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Descripción (opcional)</label>
      <input type="text" name="descripcion" class="form-control" maxlength="255"
             value="{{ old('descripcion') }}" placeholder="Ej: documentos relacionados a...">
    </div>

    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-primary">Guardar</button>
      <a href="{{ route('admin.tipos-documento.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection
