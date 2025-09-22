@extends('layouts.app')

@section('title','Crear Empresa')

@section('content')
<div class="space-y-6">
  {{-- Header --}}
  <div class="md:flex md:items-center md:justify-between">
    <div class="min-w-0 flex-1">
      <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate sm:text-3xl sm:tracking-tight">
        Crear Nueva Empresa
      </h2>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
        Completa la información básica de la empresa para agregarla al sistema.
      </p>
    </div>
    <div class="mt-4 flex md:mt-0">
      <a href="{{ route('admin.empresas.index') }}" class="btn btn-secondary">
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
  <div class="bg-white dark:bg-gray-800 shadow-soft rounded-xl overflow-hidden">
    <form method="POST" action="{{ route('admin.empresas.store') }}" id="form-empresa" enctype="multipart/form-data" class="divide-y divide-gray-200 dark:divide-gray-700">
      @csrf

      {{-- Información de la empresa --}}
      <div class="px-6 py-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4">
          <i class="bx bx-buildings mr-2"></i>
          Información de la Empresa
        </h3>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
          <div class="sm:col-span-1">
            <label class="form-label">RUT Empresa *</label>
            <input
                type="text"
                name="rut_empresa"
                id="rut_empresa"
                class="form-control"
                value="{{ old('rut_empresa') }}"
                required
                inputmode="text"
                autocomplete="off"
                placeholder="12.345.678-5"
                pattern="^\d{1,2}\.?\d{3}\.?\d{3}-[\dkK]$">
            <div class="form-error">RUT de empresa inválido.</div>
          </div>

          <div class="sm:col-span-3">
            <label class="form-label">Nombre de la Empresa *</label>
            <input type="text" name="nombre_empresa" class="form-control" value="{{ old('nombre_empresa') }}" required
                   placeholder="Ingresa el nombre completo de la empresa">
          </div>

        </div>
      </div>

      {{-- Información del representante --}}
      <div class="px-6 py-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4">
          <i class="bx bx-user mr-2"></i>
          Representante Legal
        </h3>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
          <div>
            <label class="form-label">RUT Representante *</label>
            <input
                type="text"
                name="rut_representante"
                id="rut_representante"
                class="form-control"
                value="{{ old('rut_representante') }}"
                required
                inputmode="text"
                autocomplete="off"
                placeholder="12.345.678-5"
                pattern="^\d{1,2}\.?\d{3}\.?\d{3}-[\dkK]$">
            <div class="form-error">RUT del representante inválido.</div>
          </div>

          <div>
            <label class="form-label">Nombre Completo *</label>
            <input type="text" name="nombre_representante" class="form-control" value="{{ old('nombre_representante') }}" required
                   placeholder="Nombre completo del representante legal">
          </div>
        </div>
      </div>

      {{-- Información de contacto --}}
      <div class="px-6 py-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4">
          <i class="bx bx-phone mr-2"></i>
          Información de Contacto
        </h3>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">

          <div>
            <label class="form-label">Correo del Representante</label>
            <input type="email" name="correo_representante" class="form-control" value="{{ old('correo_representante') }}"
                   placeholder="representante@empresa.com">
          </div>

          <div>
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}"
                   placeholder="+56 9 1234 5678">
          </div>

          <div>
            <label class="form-label">Correo Corporativo</label>
            <input type="email" name="correo_empresa" class="form-control" value="{{ old('correo_empresa') }}"
                   placeholder="contacto@empresa.com">
          </div>
        </div>
      </div>

      {{-- Información de ubicación --}}
      <div class="px-6 py-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4">
          <i class="bx bx-map mr-2"></i>
          Ubicación
        </h3>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">

          <div>
            <label class="form-label">Región *</label>
            <select name="region_id" id="region_id" class="form-select" required>
              <option value="">Seleccione una región...</option>
              @foreach($regiones as $r)
                <option value="{{ $r->id }}" {{ old('region_id') == $r->id ? 'selected' : '' }}>
                  {{ $r->nombre }}
                </option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="form-label">Comuna *</label>
            <select name="comuna_id" id="comuna_id" class="form-select" required disabled>
              <option value="">Seleccione primero una región</option>
            </select>
          </div>

          <div>
            <label class="form-label">Dirección *</label>
            <input type="text" name="direccion" class="form-control" value="{{ old('direccion') }}" required
                   placeholder="Dirección completa de la empresa">
          </div>
        </div>
      </div>

      {{-- Logo --}}
      <div class="px-6 py-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4">
          <i class="bx bx-image mr-2"></i>
          Logotipo (Opcional)
        </h3>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

          <div>
            <label class="form-label">Seleccionar Logo</label>
            <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
            @error('logo')
              <div class="form-error">{{ $message }}</div>
            @enderror
            <p class="form-help">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB.</p>
          </div>

          <div>
            <label class="form-label">Vista Previa</label>
            <div class="mt-2 flex items-center justify-center w-full h-32 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg bg-gray-50 dark:bg-gray-700" id="preview-container">
              <div class="text-center" id="preview-placeholder">
                <i class="bx bx-image text-3xl text-gray-400 dark:text-gray-500 mb-2"></i>
                <p class="text-sm text-gray-500 dark:text-gray-400">Vista previa del logo</p>
              </div>
              <img id="logoPreview" src="" alt="Vista previa logo" class="hidden max-h-28 object-contain rounded">
            </div>
          </div>
        </div>
      </div>

      {{-- Botones de acción --}}
      <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 flex items-center justify-end space-x-3">

        <a href="{{ route('admin.empresas.index') }}" class="btn btn-secondary">
          Cancelar
        </a>
        <button type="submit" class="btn btn-primary">
          <i class="bx bx-save mr-2"></i>
          Crear Empresa
        </button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
(function(){
  // --- Utilidades RUT ---
  function limpiarRut(v) { return (v || '').toString().replace(/[^0-9kK]/g, '').toUpperCase(); }
  function calcularDV(num) {
    let suma = 0, mul = 2;
    for (let i = num.length - 1; i >= 0; i--) {
      suma += parseInt(num[i], 10) * mul;
      mul = (mul === 7) ? 2 : (mul + 1);
    }
    const res = 11 - (suma % 11);
    if (res === 11) return '0';
    if (res === 10) return 'K';
    return String(res);
  }
  function validarRutCompleto(rut) {
    const limpio = limpiarRut(rut);
    if (limpio.length < 2) return false;
    const cuerpo = limpio.slice(0, -1);
    const dv = limpio.slice(-1);
    if (!/^\d+$/.test(cuerpo)) return false;
    return calcularDV(cuerpo) === dv;
  }
  function formatearRut(rut) {
    const limpio = limpiarRut(rut);
    if (limpio.length < 2) return limpio;
    const cuerpo = limpio.slice(0, -1);
    const dv = limpio.slice(-1);
    let conPuntos = '';
    let i = 0;
    for (let j = cuerpo.length - 1; j >= 0; j--) {
      conPuntos = cuerpo[j] + conPuntos;
      i++;
      if (i === 3 && j > 0) { conPuntos = '.' + conPuntos; i = 0; }
    }
    return conPuntos + '-' + dv;
  }
  function marcarValidez(input, valido) {
    const feedback = input.parentNode.querySelector('.form-error');
    if (valido) {
      input.classList.remove('border-red-300', 'text-red-900', 'placeholder-red-300', 'focus:ring-red-500', 'focus:border-red-500');
      input.classList.add('border-green-300', 'text-green-900', 'placeholder-green-300', 'focus:ring-green-500', 'focus:border-green-500');
      if (feedback) feedback.style.display = 'none';
    } else {
      input.classList.remove('border-green-300', 'text-green-900', 'placeholder-green-300', 'focus:ring-green-500', 'focus:border-green-500');
      input.classList.add('border-red-300', 'text-red-900', 'placeholder-red-300', 'focus:ring-red-500', 'focus:border-red-500');
      if (feedback) feedback.style.display = 'block';
    }
  }

  const rutEmpresa = document.getElementById('rut_empresa');
  const rutRep = document.getElementById('rut_representante');
  const form = document.getElementById('form-empresa');

  // Formatear al salir del campo y validar
  [rutEmpresa, rutRep].forEach(function(inp){
    if (!inp) return;
    inp.addEventListener('blur', function(){
      if (!inp.value) {
        inp.classList.remove('border-red-300', 'text-red-900', 'border-green-300', 'text-green-900');
        return;
      }
      inp.value = formatearRut(inp.value);
      marcarValidez(inp, validarRutCompleto(inp.value));
    });
    // Validación rápida mientras escribe (sin formatear)
    inp.addEventListener('input', function(){
      const ok = validarRutCompleto(inp.value);
      if (inp.value.length >= 3) marcarValidez(inp, ok);
      else inp.classList.remove('border-red-300', 'text-red-900', 'border-green-300', 'text-green-900');
    });
  });

  // Bloquear submit si algún RUT es inválido
  form.addEventListener('submit', function(e){
    let ok = true;
    [rutEmpresa, rutRep].forEach(function(inp){
      if (!inp) return;
      // Asegura formateo final antes de enviar
      inp.value = formatearRut(inp.value);
      const valido = validarRutCompleto(inp.value);
      marcarValidez(inp, valido);
      if (!valido) ok = false;
    });
    if (!ok) {
      e.preventDefault();
      e.stopPropagation();
    }
  });

  // Cargar comunas dinámicamente
  const regionSelect = document.getElementById('region_id');
  const comunaSelect = document.getElementById('comuna_id');
  const oldComuna = '{{ old('comuna_id') }}';

  async function cargarComunas(regionId) {
    comunaSelect.innerHTML = '<option value="">Cargando...</option>';
    comunaSelect.disabled = true;

    if (!regionId) {
      comunaSelect.innerHTML = '<option value="">Seleccione primero una región</option>';
      return;
    }
    try {
      const url = "{{ route('admin.regiones.comunas', ['region' => 'REGID']) }}".replace('REGID', regionId);
      const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
      const data = await res.json();

      comunaSelect.innerHTML = '<option value="">Seleccione una comuna...</option>';
      data.forEach(c => {
        const opt = document.createElement('option');
        opt.value = c.id;
        opt.textContent = c.nombre;
        if (String(c.id) === String(oldComuna)) opt.selected = true;
        comunaSelect.appendChild(opt);
      });
      comunaSelect.disabled = false;
    } catch (e) {
      comunaSelect.innerHTML = '<option value="">Error al cargar comunas</option>';
    }
  }

  regionSelect.addEventListener('change', e => cargarComunas(e.target.value));

  // Si viene con old('region_id'), cargar comunas de inmediato
  if (regionSelect.value) cargarComunas(regionSelect.value);

  // Vista previa del logo
  const logoInput = document.getElementById('logo');
  const logoPreview = document.getElementById('logoPreview');
  const previewPlaceholder = document.getElementById('preview-placeholder');

  if (logoInput && logoPreview && previewPlaceholder) {
    logoInput.addEventListener('change', function() {
      const file = this.files && this.files[0];
      if (!file) {
        logoPreview.src = '';
        logoPreview.classList.add('hidden');
        previewPlaceholder.classList.remove('hidden');
        return;
      }

      const url = URL.createObjectURL(file);
      logoPreview.src = url;
      logoPreview.classList.remove('hidden');
      previewPlaceholder.classList.add('hidden');
    });
  }
})();
</script>
@endpush
@endsection
