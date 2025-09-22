@extends('layouts.app')

@section('title','Nuevo Tipo de Cobro')

@section('content')
<div class="container">
  <h3>Nuevo Tipo de Cobro</h3>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.tipos-cobro.store') }}" id="tipoCobroForm">
    @csrf

    <div class="row">
      <div class="col-md-6">
        <div class="mb-3">
          <label class="form-label">Empresa Principal</label>
          <select name="empresa_principal_id" class="form-select" required>
            <option value="">Seleccionar empresa principal...</option>
            @foreach($empresas as $empresa)
              <option value="{{ $empresa->id }}" {{ old('empresa_principal_id') == $empresa->id ? 'selected' : '' }}>
                {{ $empresa->nombre_empresa }} - {{ $empresa->rut_empresa }}
              </option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3">
          <label class="form-label">Empresa Contratista/Subcontratista</label>
          <select name="empresa_contratista_id" class="form-select" required>
            <option value="">Seleccionar empresa contratista...</option>
            @foreach($empresas as $empresa)
              <option value="{{ $empresa->id }}" {{ old('empresa_contratista_id') == $empresa->id ? 'selected' : '' }}>
                {{ $empresa->nombre_empresa }} - {{ $empresa->rut_empresa }}
              </option>
            @endforeach
          </select>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="mb-3">
          <label class="form-label">Tipo de Cobro</label>
          <select name="tipo_cobro" class="form-select" required>
            <option value="">Seleccionar tipo...</option>
            <option value="pesos" {{ old('tipo_cobro') === 'pesos' ? 'selected' : '' }}>Pesos (CLP)</option>
            <option value="uf" {{ old('tipo_cobro') === 'uf' ? 'selected' : '' }}>UF</option>
          </select>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3">
          <label class="form-label">Tipo de Pago</label>
          <select name="tipo_pago" class="form-select" required>
            <option value="">Seleccionar tipo de pago...</option>
            <option value="factura" {{ old('tipo_pago') === 'factura' ? 'selected' : '' }}>Factura</option>
            <option value="webpay" {{ old('tipo_pago') === 'webpay' ? 'selected' : '' }}>WebPay</option>
          </select>
        </div>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Observaciones</label>
      <textarea name="observaciones" class="form-control" rows="3" maxlength="500"
                placeholder="Observaciones adicionales (opcional)">{{ old('observaciones') }}</textarea>
    </div>

    <div class="mb-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>Rangos de Cobro por Cantidad de Trabajadores</h5>
        <button type="button" class="btn btn-outline-primary btn-sm" onclick="agregarRango()">
          <i class="bx bx-plus"></i> Agregar Rango
        </button>
      </div>

      <div id="rangos-container">
        @if(old('rangos'))
          @foreach(old('rangos') as $index => $rango)
            <div class="row rango-row mb-2">
              <div class="col-md-3">
                <label class="form-label">Desde (trabajadores)</label>
                <input type="number" name="rangos[{{ $index }}][trabajadores_desde]"
                       class="form-control" min="1" required
                       value="{{ $rango['trabajadores_desde'] ?? '' }}">
              </div>
              <div class="col-md-3">
                <label class="form-label">Hasta (trabajadores)</label>
                <input type="number" name="rangos[{{ $index }}][trabajadores_hasta]"
                       class="form-control" min="1" required
                       value="{{ $rango['trabajadores_hasta'] ?? '' }}">
              </div>
              <div class="col-md-4">
                <label class="form-label">Monto</label>
                <div class="input-group">
                  <span class="input-group-text">$</span>
                  <input type="number" name="rangos[{{ $index }}][monto]"
                         class="form-control" min="0" step="0.01" required
                         value="{{ $rango['monto'] ?? '' }}">
                </div>
              </div>
              <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarRango(this)">
                  <i class="bx bx-trash"></i>
                </button>
              </div>
            </div>
          @endforeach
        @else
          <div class="row rango-row mb-2">
            <div class="col-md-3">
              <label class="form-label">Desde (trabajadores)</label>
              <input type="number" name="rangos[0][trabajadores_desde]" class="form-control" min="1" required value="1">
            </div>
            <div class="col-md-3">
              <label class="form-label">Hasta (trabajadores)</label>
              <input type="number" name="rangos[0][trabajadores_hasta]" class="form-control" min="1" required value="10">
            </div>
            <div class="col-md-4">
              <label class="form-label">Monto</label>
              <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" name="rangos[0][monto]" class="form-control" min="0" step="0.01" required>
              </div>
            </div>
            <div class="col-md-2 d-flex align-items-end">
              <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarRango(this)">
                <i class="bx bx-trash"></i>
              </button>
            </div>
          </div>
        @endif
      </div>
    </div>

    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-primary">Guardar</button>
      <a href="{{ route('admin.tipos-cobro.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
  </form>
</div>

<script>
let rangoIndex = {{ old('rangos') ? count(old('rangos')) : 1 }};

function agregarRango() {
  const container = document.getElementById('rangos-container');
  const newRow = document.createElement('div');
  newRow.className = 'row rango-row mb-2';
  newRow.innerHTML = `
    <div class="col-md-3">
      <label class="form-label">Desde (trabajadores)</label>
      <input type="number" name="rangos[${rangoIndex}][trabajadores_desde]" class="form-control" min="1" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">Hasta (trabajadores)</label>
      <input type="number" name="rangos[${rangoIndex}][trabajadores_hasta]" class="form-control" min="1" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">Monto</label>
      <div class="input-group">
        <span class="input-group-text">$</span>
        <input type="number" name="rangos[${rangoIndex}][monto]" class="form-control" min="0" step="0.01" required>
      </div>
    </div>
    <div class="col-md-2 d-flex align-items-end">
      <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarRango(this)">
        <i class="bx bx-trash"></i>
      </button>
    </div>
  `;
  container.appendChild(newRow);
  rangoIndex++;
}

function eliminarRango(button) {
  const rangosContainer = document.getElementById('rangos-container');
  if (rangosContainer.children.length > 1) {
    button.closest('.rango-row').remove();
  } else {
    alert('Debe mantener al menos un rango de cobro.');
  }
}
</script>
@endsection