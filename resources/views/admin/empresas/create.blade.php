@extends('layouts.app')

@section('title','Crear Empresa')

@section('content')
<div class="container">
  <h2>Crear Empresa</h2>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.empresas.store') }}" id="form-empresa" enctype="multipart/form-data">
    @csrf

    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">RUT Empresa</label>
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
            <div class="invalid-feedback">RUT de empresa inválido.</div>
        </div>
      <div class="col-md-8">
        <label class="form-label">Nombre Empresa</label>
        <input type="text" name="nombre_empresa" class="form-control" value="{{ old('nombre_empresa') }}" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">RUT Representante</label>
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
        <div class="invalid-feedback">RUT del representante inválido.</div>
      </div>
      <div class="col-md-8">
        <label class="form-label">Nombre Representante</label>
        <input type="text" name="nombre_representante" class="form-control" value="{{ old('nombre_representante') }}" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Correo Representante</label>
        <input type="email" name="correo_representante" class="form-control" value="{{ old('correo_representante') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label">Teléfono</label>
        <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label">Correo Empresa</label>
        <input type="email" name="correo_empresa" class="form-control" value="{{ old('correo_empresa') }}">
      </div>

      <div class="col-md-4">
        <label class="form-label">Región</label>
        <select name="region_id" id="region_id" class="form-select" required>
          <option value="">Seleccione...</option>
          @foreach($regiones as $r)
            <option value="{{ $r->id }}" {{ old('region_id') == $r->id ? 'selected' : '' }}>
              {{ $r->nombre }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label">Comuna</label>
        <select name="comuna_id" id="comuna_id" class="form-select" required disabled>
          <option value="">Seleccione primero una región</option>
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label">Dirección</label>
        <input type="text" name="direccion" class="form-control" value="{{ old('direccion') }}" required>
      </div>

        <div class="col-md-4">
            <label class="form-label">Logo (opcional)</label>
            <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
            @error('logo') <div class="text-danger small">{{ $message }}</div> @enderror

            <div class="mt-2">
            <img id="logoPreview" src="" alt="Vista previa logo" class="d-none img-thumbnail" style="max-height: 120px; object-fit: contain;">
            </div>
        </div>

    </div>

    <div class="mt-3">
      <button type="submit" class="btn btn-primary">Guardar</button>
      <a href="{{ route('admin.empresas.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
  </form>
</div>

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
    if (valido) {
      input.classList.remove('is-invalid');
      input.classList.add('is-valid');
    } else {
      input.classList.remove('is-valid');
      input.classList.add('is-invalid');
    }
  }

  const rutEmpresa = document.getElementById('rut_empresa');
  const rutRep = document.getElementById('rut_representante');
  const form = document.getElementById('form-empresa');

   // Formatear al salir del campo y validar
  [rutEmpresa, rutRep].forEach(function(inp){
    if (!inp) return;
    inp.addEventListener('blur', function(){
      if (!inp.value) { inp.classList.remove('is-valid','is-invalid'); return; }
      inp.value = formatearRut(inp.value);
      marcarValidez(inp, validarRutCompleto(inp.value));
    });
    // Validación rápida mientras escribe (sin formatear)
    inp.addEventListener('input', function(){
      const ok = validarRutCompleto(inp.value);
      if (inp.value.length >= 3) marcarValidez(inp, ok);
      else inp.classList.remove('is-valid','is-invalid');
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

      comunaSelect.innerHTML = '<option value="">Seleccione...</option>';
      data.forEach(c => {
        const opt = document.createElement('option');
        opt.value = c.id;
        opt.textContent = c.nombre;
        if (String(c.id) === String(oldComuna)) opt.selected = true;
        comunaSelect.appendChild(opt);
      });
      comunaSelect.disabled = false;
    } catch (e) {
      comunaSelect.innerHTML = '<option value=\"\">Error al cargar comunas</option>';
    }
  }

  regionSelect.addEventListener('change', e => cargarComunas(e.target.value));

  // Si viene con old('region_id'), cargar comunas de inmediato
  if (regionSelect.value) cargarComunas(regionSelect.value);
  const input = document.getElementById('logo');
  const preview = document.getElementById('logoPreview');
  if (!input || !preview) return;
  input.addEventListener('change', function() {
    const file = this.files && this.files[0];
    if (!file) { preview.src=''; preview.classList.add('d-none'); return; }
    const url = URL.createObjectURL(file);
    preview.src = url;
    preview.classList.remove('d-none');
  });


})();
</script>
@endsection
