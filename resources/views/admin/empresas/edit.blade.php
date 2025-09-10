@extends('layouts.app')

@section('title','Editar Empresa')

@section('content')
<div class="container">
  <h2>Editar Empresa</h2>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="POST"
        action="{{ route('admin.empresas.update', $empresa) }}"
        enctype="multipart/form-data"
        id="form-empresa">
    @csrf
    @method('PUT')

    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">RUT Empresa</label>
        <input type="text" name="rut_empresa" id="rut_empresa" class="form-control"
               value="{{ old('rut_empresa', $empresa->rut_empresa) }}" required>
      </div>
      <div class="col-md-8">
        <label class="form-label">Nombre Empresa</label>
        <input type="text" name="nombre_empresa" class="form-control"
               value="{{ old('nombre_empresa', $empresa->nombre_empresa) }}" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">RUT Representante</label>
        <input type="text" name="rut_representante" id="rut_representante" class="form-control"
               value="{{ old('rut_representante', $empresa->rut_representante) }}" required>
      </div>
      <div class="col-md-8">
        <label class="form-label">Nombre Representante</label>
        <input type="text" name="nombre_representante" class="form-control"
               value="{{ old('nombre_representante', $empresa->nombre_representante) }}" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Correo Representante</label>
        <input type="email" name="correo_representante" class="form-control"
               value="{{ old('correo_representante', $empresa->correo_representante) }}">
      </div>
      <div class="col-md-3">
        <label class="form-label">Teléfono</label>
        <input type="text" name="telefono" class="form-control"
               value="{{ old('telefono', $empresa->telefono) }}">
      </div>
      <div class="col-md-3">
        <label class="form-label">Correo Empresa</label>
        <input type="email" name="correo_empresa" class="form-control"
               value="{{ old('correo_empresa', $empresa->correo_empresa) }}">
      </div>

      <div class="col-md-4">
        <label class="form-label">Región</label>
        <select name="region_id" id="region_id" class="form-select" required>
          <option value="">Seleccione...</option>
          @foreach($regiones as $r)
            <option value="{{ $r->id }}"
              {{ (int)old('region_id', $empresa->region_id) === (int)$r->id ? 'selected' : '' }}>
              {{ $r->nombre }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label">Comuna</label>
        <select name="comuna_id" id="comuna_id" class="form-select" required>
          <option value="">Seleccione...</option>
          @foreach($comunas as $c)
            <option value="{{ $c->id }}"
              {{ (int)old('comuna_id', $empresa->comuna_id) === (int)$c->id ? 'selected' : '' }}>
              {{ $c->nombre }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label">Dirección</label>
        <input type="text" name="direccion" class="form-control"
               value="{{ old('direccion', $empresa->direccion) }}" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">Logo (opcional)</label>
        <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
        @if($empresa->logo_path)
          <div class="mt-2">
            <img src="{{ asset('storage/'.$empresa->logo_path) }}" class="img-thumbnail" style="max-height:120px;object-fit:contain;">
          </div>
        @endif
        <div class="mt-2">
          <img id="logoPreview" src="" class="d-none img-thumbnail" style="max-height:120px;object-fit:contain;">
        </div>
      </div>
    </div>

    <div class="mt-3 d-flex gap-2">
      <button type="submit" class="btn btn-primary">Guardar cambios</button>
      <a href="{{ route('admin.empresas.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
  </form>
</div>

<script>
(function(){
  const regionSelect = document.getElementById('region_id');
  const comunaSelect = document.getElementById('comuna_id');
  async function cargarComunas(regionId) {
    if (!regionId) return;
    try {
      const url = "{{ route('admin.regiones.comunas', ['region' => 'REGID']) }}".replace('REGID', regionId);
      const res = await fetch(url, { headers: {'X-Requested-With':'XMLHttpRequest'} });
      const data = await res.json();
      const selected = "{{ (int)old('comuna_id', (int)$empresa->comuna_id) }}";
      comunaSelect.innerHTML = '<option value=\"\">Seleccione...</option>';
      data.forEach(c => {
        const opt = document.createElement('option');
        opt.value = c.id; opt.textContent = c.nombre;
        if (String(c.id) === String(selected)) opt.selected = true;
        comunaSelect.appendChild(opt);
      });
    } catch(e) { /* noop */ }
  }
  regionSelect.addEventListener('change', e => cargarComunas(e.target.value));
  if (!comunaSelect.options.length || !comunaSelect.value) cargarComunas(regionSelect.value);

  // preview logo nuevo
  const input = document.getElementById('logo');
  const preview = document.getElementById('logoPreview');
  if (input && preview) {
    input.addEventListener('change', function(){
      const file = this.files?.[0];
      if (!file) { preview.src=''; preview.classList.add('d-none'); return; }
      preview.src = URL.createObjectURL(file);
      preview.classList.remove('d-none');
    });
  }
})();
</script>
@endsection
