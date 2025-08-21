@extends('layouts.app')

@section('title','Empresas')

@section('content')
<div class="container">
  @if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
  @endif

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">Empresas</h3>
    <a href="{{ route('admin.empresas.create') }}" class="btn btn-primary">Nueva empresa</a>
  </div>

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>RUT</th>
          <th>Nombre</th>
          <th>Región</th>
          <th>Comuna</th>
          <th>Teléfono</th>
          <th>Correo</th>
          <th>logo</th>
          <th style="width:150px;">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($empresas as $e)
          <tr>
            <td>{{ $e->rut_empresa }}</td>
            <td>{{ $e->nombre_empresa }}</td>
            <td>{{ $e->region_id ? ($e->region->nombre ?? '-') : ($e->region ?? '-') }}</td>
            <td>{{ $e->comuna_id ? ($e->comuna->nombre ?? '-') : ($e->comuna ?? '-') }}</td>
            <td>{{ $e->telefono }}</td>
            <td>{{ $e->correo_empresa }}</td>
            <td>
                @if($e->logo_path)
                    <img src="{{ Storage::url($e->logo_path) }}" alt="Logo" class="img-thumbnail"
                        style="width:48px;height:48px;object-fit:contain;">
                @else
                    —
                @endif
            </td>

            <td class="d-flex gap-2">
              <a href="{{ route('admin.empresas.edit', $e) }}" class="btn btn-sm btn-warning">Editar</a>
              <form action="{{ route('admin.empresas.destroy', $e) }}" method="POST"
                    onsubmit="return confirm('¿Eliminar empresa?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger" type="submit">Eliminar</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center text-muted">No hay empresas registradas.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ method_exists($empresas, 'links') ? $empresas->links() : '' }}
</div>
@endsection
