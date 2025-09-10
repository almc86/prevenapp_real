@extends('layouts.app')

@section('title','Editar Categoría')

@section('content')
<div class="container">
  <h3>Editar categoría</h3>

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.categorias.update', $categoria) }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label class="form-label">Nombre</label>
      <input type="text" name="nombre" class="form-control" required maxlength="255"
             value="{{ old('nombre', $categoria->nombre) }}">
    </div>

    <div class="mb-3">
      <label class="form-label">Descripción (opcional)</label>
      <input type="text" name="descripcion" class="form-control" maxlength="255"
             value="{{ old('descripcion', $categoria->descripcion) }}">
    </div>

    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-primary">Guardar cambios</button>
      <a href="{{ route('admin.categorias.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection
