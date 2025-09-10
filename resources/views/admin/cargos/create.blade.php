@extends('layouts.app')

@section('title','Nuevo Cargo')

@section('content')
<div class="container">
  <h3>Nuevo cargo</h3>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.cargos.store') }}">
    @csrf
    <div class="mb-3">
      <label class="form-label">Nombre del cargo</label>
      <input type="text" name="nombre" class="form-control" required maxlength="255"
             value="{{ old('nombre') }}" placeholder="Ej: Supervisor de mantenimiento">
    </div>

    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-primary">Guardar</button>
      <a href="{{ route('admin.cargos.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection
