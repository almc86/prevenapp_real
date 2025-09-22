@extends('layouts.app')

@section('title','Nuevo Tipo de Cobro')

@section('content')
<div class="space-y-6">
  {{-- Header --}}
  <div class="md:flex md:items-center md:justify-between">
    <div class="min-w-0 flex-1">
      <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
        Nuevo Tipo de Cobro
      </h2>
      <p class="mt-1 text-sm text-gray-500">
        Configura tipos de cobro según relación empresa, tipo de cobro/pago y rangos por cantidad de trabajadores.
      </p>
    </div>
    <div class="mt-4 flex md:mt-0">
      <a href="{{ route('admin.tipos-cobro.index') }}" class="btn btn-secondary">
        <i class="bx bx-arrow-back mr-2"></i>
        Volver
      </a>
    </div>
  </div>

  {{-- Errores --}}
  @if ($errors->any())
    <div class="alert alert-danger">
      <div class="flex items-center mb-2">
        <i class="bx bx-error-circle text-lg mr-2"></i>
        <span class="font-medium">Por favor corrige los siguientes errores:</span>
      </div>
      <ul class="list-disc list-inside space-y-1">
        @foreach ($errors->all() as $e)
          <li class="text-sm">{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Formulario --}}
  <div class="bg-white shadow-soft rounded-xl overflow-hidden">
    <form method="POST" action="{{ route('admin.tipos-cobro.store') }}" id="tipoCobroForm" class="divide-y divide-gray-200">
      @csrf

      {{-- Selección de empresas --}}
      <div class="px-6 py-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-6">
          <i class="bx bx-buildings mr-2"></i>
          Relación entre Empresas
        </h3>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
          <div>
            <label class="form-label">Empresa Principal *</label>
            <select name="empresa_principal_id" class="form-select" required>
              <option value="">Seleccionar empresa principal...</option>
              @foreach($empresas as $empresa)
                <option value="{{ $empresa->id }}" {{ old('empresa_principal_id') == $empresa->id ? 'selected' : '' }}>
                  {{ $empresa->nombre_empresa }} - {{ $empresa->rut_empresa }}
                </option>
              @endforeach
            </select>
            <p class="form-help">Empresa que realizará el cobro</p>
          </div>

          <div>
            <label class="form-label">Empresa Contratista/Subcontratista *</label>
            <select name="empresa_contratista_id" class="form-select" required>
              <option value="">Seleccionar empresa contratista...</option>
              @foreach($empresas as $empresa)
                <option value="{{ $empresa->id }}" {{ old('empresa_contratista_id') == $empresa->id ? 'selected' : '' }}>
                  {{ $empresa->nombre_empresa }} - {{ $empresa->rut_empresa }}
                </option>
              @endforeach
            </select>
            <p class="form-help">Empresa que será cobrada</p>
          </div>
        </div>
      </div>

      {{-- Configuración de cobro --}}
      <div class="px-6 py-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-6">
          <i class="bx bx-credit-card mr-2"></i>
          Configuración de Cobro
        </h3>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
          <div>
            <label class="form-label">Tipo de Cobro *</label>
            <select name="tipo_cobro" class="form-select" required>
              <option value="">Seleccionar tipo...</option>
              <option value="pesos" {{ old('tipo_cobro') === 'pesos' ? 'selected' : '' }}>
                <i class="bx bx-dollar"></i> Pesos (CLP)
              </option>
              <option value="uf" {{ old('tipo_cobro') === 'uf' ? 'selected' : '' }}>
                <i class="bx bx-trending-up"></i> UF
              </option>
            </select>
            <p class="form-help">Moneda en la que se realizará el cobro</p>
          </div>

          <div>
            <label class="form-label">Tipo de Pago *</label>
            <select name="tipo_pago" class="form-select" required>
              <option value="">Seleccionar tipo de pago...</option>
              <option value="factura" {{ old('tipo_pago') === 'factura' ? 'selected' : '' }}>
                📄 Factura
              </option>
              <option value="webpay" {{ old('tipo_pago') === 'webpay' ? 'selected' : '' }}>
                💳 WebPay
              </option>
            </select>
            <p class="form-help">Método de pago a utilizar</p>
          </div>
        </div>

        <div class="mt-6">
          <label class="form-label">Observaciones</label>
          <textarea name="observaciones"
                    class="form-control"
                    rows="3"
                    maxlength="500"
                    placeholder="Observaciones adicionales sobre este tipo de cobro (opcional)">{{ old('observaciones') }}</textarea>
          <p class="form-help">Máximo 500 caracteres</p>
        </div>
      </div>

      {{-- Rangos de cobro --}}
      <div class="px-6 py-6">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-medium leading-6 text-gray-900">
            <i class="bx bx-bar-chart mr-2"></i>
            Rangos de Cobro por Cantidad de Trabajadores
          </h3>
          <button type="button"
                  class="btn btn-sm btn-secondary"
                  onclick="agregarRango()">
            <i class="bx bx-plus mr-2"></i>
            Agregar Rango
          </button>
        </div>

        <div id="rangos-container" class="space-y-4">
          @if(old('rangos'))
            @foreach(old('rangos') as $index => $rango)
              <div class="rango-row bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                  <div>
                    <label class="form-label">Desde (trabajadores) *</label>
                    <input type="number"
                           name="rangos[{{ $index }}][trabajadores_desde]"
                           class="form-control"
                           min="1"
                           required
                           value="{{ $rango['trabajadores_desde'] ?? '' }}"
                           placeholder="1">
                  </div>
                  <div>
                    <label class="form-label">Hasta (trabajadores) *</label>
                    <input type="number"
                           name="rangos[{{ $index }}][trabajadores_hasta]"
                           class="form-control"
                           min="1"
                           required
                           value="{{ $rango['trabajadores_hasta'] ?? '' }}"
                           placeholder="10">
                  </div>
                  <div>
                    <label class="form-label">Monto *</label>
                    <div class="relative">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">$</span>
                      </div>
                      <input type="number"
                             name="rangos[{{ $index }}][monto]"
                             class="form-control pl-7"
                             min="0"
                             step="0.01"
                             required
                             value="{{ $rango['monto'] ?? '' }}"
                             placeholder="0.00">
                    </div>
                  </div>
                  <div class="flex justify-end">
                    <button type="button"
                            class="btn btn-sm btn-outline-danger"
                            onclick="eliminarRango(this)"
                            title="Eliminar rango">
                      <i class="bx bx-trash"></i>
                    </button>
                  </div>
                </div>
              </div>
            @endforeach
          @else
            <div class="rango-row bg-gray-50 rounded-lg p-4 border border-gray-200">
              <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                <div>
                  <label class="form-label">Desde (trabajadores) *</label>
                  <input type="number"
                         name="rangos[0][trabajadores_desde]"
                         class="form-control"
                         min="1"
                         required
                         value="1"
                         placeholder="1">
                </div>
                <div>
                  <label class="form-label">Hasta (trabajadores) *</label>
                  <input type="number"
                         name="rangos[0][trabajadores_hasta]"
                         class="form-control"
                         min="1"
                         required
                         value="10"
                         placeholder="10">
                </div>
                <div>
                  <label class="form-label">Monto *</label>
                  <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <span class="text-gray-500 sm:text-sm">$</span>
                    </div>
                    <input type="number"
                           name="rangos[0][monto]"
                           class="form-control pl-7"
                           min="0"
                           step="0.01"
                           required
                           placeholder="0.00">
                  </div>
                </div>
                <div class="flex justify-end">
                  <button type="button"
                          class="btn btn-sm btn-outline-danger"
                          onclick="eliminarRango(this)"
                          title="Eliminar rango">
                    <i class="bx bx-trash"></i>
                  </button>
                </div>
              </div>
            </div>
          @endif
        </div>

        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
          <div class="flex">
            <div class="flex-shrink-0">
              <i class="bx bx-info-circle text-blue-400 text-lg"></i>
            </div>
            <div class="ml-3">
              <h4 class="text-sm font-medium text-blue-800">
                Información sobre rangos
              </h4>
              <div class="mt-2 text-sm text-blue-700">
                <ul class="list-disc list-inside space-y-1">
                  <li>Define rangos de cantidad de trabajadores y el monto correspondiente para cada rango</li>
                  <li>Los rangos no deben solaparse entre sí</li>
                  <li>Ejemplo: 1-10 trabajadores = $50.000, 11-25 trabajadores = $100.000</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Botones de acción --}}
      <div class="px-6 py-4 bg-gray-50 flex items-center justify-end space-x-3">
        <a href="{{ route('admin.tipos-cobro.index') }}" class="btn btn-secondary">
          Cancelar
        </a>
        <button type="submit" class="btn btn-primary">
          <i class="bx bx-save mr-2"></i>
          Guardar Tipo de Cobro
        </button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
let rangoIndex = {{ old('rangos') ? count(old('rangos')) : 1 }};

function agregarRango() {
  const container = document.getElementById('rangos-container');
  const newRow = document.createElement('div');
  newRow.className = 'rango-row bg-gray-50 rounded-lg p-4 border border-gray-200';
  newRow.innerHTML = `
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
      <div>
        <label class="form-label">Desde (trabajadores) *</label>
        <input type="number"
               name="rangos[${rangoIndex}][trabajadores_desde]"
               class="form-control"
               min="1"
               required
               placeholder="1">
      </div>
      <div>
        <label class="form-label">Hasta (trabajadores) *</label>
        <input type="number"
               name="rangos[${rangoIndex}][trabajadores_hasta]"
               class="form-control"
               min="1"
               required
               placeholder="10">
      </div>
      <div>
        <label class="form-label">Monto *</label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <span class="text-gray-500 sm:text-sm">$</span>
          </div>
          <input type="number"
                 name="rangos[${rangoIndex}][monto]"
                 class="form-control pl-7"
                 min="0"
                 step="0.01"
                 required
                 placeholder="0.00">
        </div>
      </div>
      <div class="flex justify-end">
        <button type="button"
                class="btn btn-sm btn-outline-danger"
                onclick="eliminarRango(this)"
                title="Eliminar rango">
          <i class="bx bx-trash"></i>
        </button>
      </div>
    </div>
  `;
  container.appendChild(newRow);
  rangoIndex++;

  // Animación suave de entrada
  newRow.style.opacity = '0';
  newRow.style.transform = 'translateY(-10px)';
  setTimeout(() => {
    newRow.style.transition = 'all 0.3s ease';
    newRow.style.opacity = '1';
    newRow.style.transform = 'translateY(0)';
  }, 10);
}

function eliminarRango(button) {
  const rangosContainer = document.getElementById('rangos-container');
  if (rangosContainer.children.length > 1) {
    const rangoRow = button.closest('.rango-row');

    // Animación suave de salida
    rangoRow.style.transition = 'all 0.3s ease';
    rangoRow.style.opacity = '0';
    rangoRow.style.transform = 'translateY(-10px)';

    setTimeout(() => {
      rangoRow.remove();
    }, 300);
  } else {
    // Mostrar notificación moderna en lugar de alert
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg z-50';
    notification.innerHTML = `
      <div class="flex items-center">
        <i class="bx bx-error-circle mr-2"></i>
        <span>Debe mantener al menos un rango de cobro.</span>
      </div>
    `;
    document.body.appendChild(notification);

    setTimeout(() => {
      notification.style.transition = 'all 0.3s ease';
      notification.style.opacity = '0';
      notification.style.transform = 'translateX(100%)';
      setTimeout(() => notification.remove(), 300);
    }, 3000);
  }
}

// Validación adicional del formulario
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('tipoCobroForm');

  form.addEventListener('submit', function(e) {
    // Validar que las empresas no sean iguales
    const empresaPrincipal = document.querySelector('[name="empresa_principal_id"]').value;
    const empresaContratista = document.querySelector('[name="empresa_contratista_id"]').value;

    if (empresaPrincipal && empresaContratista && empresaPrincipal === empresaContratista) {
      e.preventDefault();

      const notification = document.createElement('div');
      notification.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg z-50';
      notification.innerHTML = `
        <div class="flex items-center">
          <i class="bx bx-error-circle mr-2"></i>
          <span>La empresa principal y contratista no pueden ser la misma.</span>
        </div>
      `;
      document.body.appendChild(notification);

      setTimeout(() => {
        notification.style.transition = 'all 0.3s ease';
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 300);
      }, 5000);
    }
  });
});
</script>
@endpush
@endsection