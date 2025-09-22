@extends('layouts.app')

@section('title','Ver Tipo de Cobro')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">Detalle del Tipo de Cobro</h3>
    <div class="btn-group">
      <a href="{{ route('admin.tipos-cobro.edit', $tiposCobro) }}" class="btn btn-warning">
        <i class="bx bx-edit"></i> Editar
      </a>
      <a href="{{ route('admin.tipos-cobro.index') }}" class="btn btn-secondary">
        <i class="bx bx-arrow-back"></i> Volver
      </a>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">
            <i class="bx bx-buildings"></i> Información de Empresas
          </h5>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label class="fw-bold">Empresa Principal:</label>
            <div>{{ $tiposCobro->empresaPrincipal->nombre_empresa }}</div>
            <small class="text-muted">{{ $tiposCobro->empresaPrincipal->rut_empresa }}</small>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Empresa Contratista:</label>
            <div>{{ $tiposCobro->empresaContratista->nombre_empresa }}</div>
            <small class="text-muted">{{ $tiposCobro->empresaContratista->rut_empresa }}</small>
          </div>

          <div class="row">
            <div class="col-6">
              <label class="fw-bold">Tipo de Cobro:</label>
              <div>
                <span class="badge bg-{{ $tiposCobro->tipo_cobro === 'uf' ? 'warning' : 'info' }} fs-6">
                  {{ $tiposCobro->tipo_cobro_formatted }}
                </span>
              </div>
            </div>
            <div class="col-6">
              <label class="fw-bold">Tipo de Pago:</label>
              <div>
                <span class="badge bg-{{ $tiposCobro->tipo_pago === 'webpay' ? 'success' : 'secondary' }} fs-6">
                  {{ $tiposCobro->tipo_pago_formatted }}
                </span>
              </div>
            </div>
          </div>

          <div class="mt-3">
            <label class="fw-bold">Estado:</label>
            <div>
              @if($tiposCobro->activo)
                <span class="badge bg-success fs-6">Activo</span>
              @else
                <span class="badge bg-danger fs-6">Inactivo</span>
              @endif
            </div>
          </div>

          @if($tiposCobro->observaciones)
            <div class="mt-3">
              <label class="fw-bold">Observaciones:</label>
              <div class="text-muted">{{ $tiposCobro->observaciones }}</div>
            </div>
          @endif
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">
            <i class="bx bx-group"></i> Rangos de Cobro
          </h5>
        </div>
        <div class="card-body">
          @if($tiposCobro->rangos->count() > 0)
            <div class="table-responsive">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>Rango de Trabajadores</th>
                    <th class="text-end">Monto</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($tiposCobro->rangos->sortBy('trabajadores_desde') as $rango)
                    <tr>
                      <td>
                        <span class="badge bg-light text-dark">
                          {{ $rango->trabajadores_desde }} - {{ $rango->trabajadores_hasta }}
                        </span>
                        trabajadores
                      </td>
                      <td class="text-end">
                        <strong>{{ $rango->monto_formatted }}</strong>
                        @if($tiposCobro->tipo_cobro === 'uf')
                          <small class="text-muted">UF</small>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="text-center text-muted py-3">
              No hay rangos de cobro configurados.
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="card mt-4">
    <div class="card-header">
      <h5 class="card-title mb-0">
        <i class="bx bx-time"></i> Información de Registro
      </h5>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <label class="fw-bold">Fecha de Creación:</label>
          <div>{{ $tiposCobro->created_at->format('d/m/Y H:i:s') }}</div>
        </div>
        <div class="col-md-6">
          <label class="fw-bold">Última Modificación:</label>
          <div>{{ $tiposCobro->updated_at->format('d/m/Y H:i:s') }}</div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection