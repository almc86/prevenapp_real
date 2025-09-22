@extends('layouts.app')

@section('title','Tipos de Cobro')

@section('content')
<div class="container">
  @if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
  @endif

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">Tipos de Cobro</h3>
    <a href="{{ route('admin.tipos-cobro.create') }}" class="btn btn-primary">
      <i class="bx bx-plus"></i> Nuevo Tipo de Cobro
    </a>
  </div>

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>Empresa Principal</th>
          <th>Empresa Contratista</th>
          <th>Tipo Cobro</th>
          <th>Tipo Pago</th>
          <th>Rangos</th>
          <th>Estado</th>
          <th style="width:180px;">Creado</th>
          <th style="width:200px;">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($tiposCobro as $tipoCobro)
          <tr>
            <td>
              <strong>{{ $tipoCobro->empresaPrincipal->nombre_empresa }}</strong><br>
              <small class="text-muted">{{ $tipoCobro->empresaPrincipal->rut_empresa }}</small>
            </td>
            <td>
              <strong>{{ $tipoCobro->empresaContratista->nombre_empresa }}</strong><br>
              <small class="text-muted">{{ $tipoCobro->empresaContratista->rut_empresa }}</small>
            </td>
            <td>
              <span class="badge bg-{{ $tipoCobro->tipo_cobro === 'uf' ? 'warning' : 'info' }}">
                {{ $tipoCobro->tipo_cobro_formatted }}
              </span>
            </td>
            <td>
              <span class="badge bg-{{ $tipoCobro->tipo_pago === 'webpay' ? 'success' : 'secondary' }}">
                {{ $tipoCobro->tipo_pago_formatted }}
              </span>
            </td>
            <td>
              <small>
                @foreach($tipoCobro->rangos->take(2) as $rango)
                  {{ $rango->trabajadores_desde }}-{{ $rango->trabajadores_hasta }}: {{ $rango->monto_formatted }}<br>
                @endforeach
                @if($tipoCobro->rangos->count() > 2)
                  <span class="text-muted">... +{{ $tipoCobro->rangos->count() - 2 }} más</span>
                @endif
              </small>
            </td>
            <td>
              @if($tipoCobro->activo)
                <span class="badge bg-success">Activo</span>
              @else
                <span class="badge bg-danger">Inactivo</span>
              @endif
            </td>
            <td>{{ $tipoCobro->created_at->format('Y-m-d H:i') }}</td>
            <td>
              <div class="btn-group btn-group-sm">
                <a href="{{ route('admin.tipos-cobro.show', $tipoCobro) }}"
                   class="btn btn-outline-info" title="Ver">
                  <i class="bx bx-show"></i>
                </a>
                <a href="{{ route('admin.tipos-cobro.edit', $tipoCobro) }}"
                   class="btn btn-outline-warning" title="Editar">
                  <i class="bx bx-edit"></i>
                </a>
                <form method="POST" action="{{ route('admin.tipos-cobro.destroy', $tipoCobro) }}"
                      class="d-inline"
                      onsubmit="return confirm('¿Estás seguro de eliminar este tipo de cobro?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                    <i class="bx bx-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center text-muted py-4">
              No hay tipos de cobro registrados.
              <br>
              <a href="{{ route('admin.tipos-cobro.create') }}" class="btn btn-primary mt-2">
                Crear el primero
              </a>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if(method_exists($tiposCobro, 'links'))
    <div class="mt-3">
        {{ $tiposCobro->links() }}
    </div>
  @endif
</div>
@endsection