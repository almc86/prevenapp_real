@extends('layouts.app')

@section('title','Editar Usuario')

@section('content')
<div class="container">
  <h2>Editar Usuario</h2>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.usuarios.update', $usuario) }}" enctype="multipart/form-data" id="form-user">
    @csrf
    @method('PUT')

    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Nombre</label>
        <input type="text" name="name" class="form-control" required value="{{ old('name', $usuario->name) }}">
      </div>

      <div class="col-md-6">
        <label class="form-label">Correo</label>
        <input type="email" name="email" class="form-control" required value="{{ old('email', $usuario->email) }}">
      </div>

      <div class="col-md-6">
        <label class="form-label">Nueva contraseña (opcional)</label>
        <input type="password" name="password" class="form-control">
      </div>

      <div class="col-md-6">
        <label class="form-label">Confirmar contraseña</label>
        <input type="password" name="password_confirmation" class="form-control">
      </div>

      <div class="col-md-6">
        <label class="form-label">Rol</label>
        <select name="role_id" id="role_id" class="form-select" required>
          <option value="">Seleccione un rol</option>
          @foreach($roles as $rol)
            <option value="{{ $rol->id }}"
              {{ (string)old('role_id', $roleSelectedId) === (string)$rol->id ? 'selected' : '' }}>
              {{ ucfirst($rol->name) }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- Empresas Principales --}}
      <div class="col-md-6 d-none" id="box_principales">
        <label class="form-label">Empresas Principales</label>
        <select name="empresas_principales[]" id="empresas_principales" class="form-select" multiple>
          @php
            $selPrincipales = collect(old('empresas_principales', $principalesSel ?? []))->map(fn($v)=>(int)$v)->all();
          @endphp
          @foreach($empresas as $e)
            <option value="{{ $e->id }}" @selected(in_array($e->id, $selPrincipales))>
              {{ $e->nombre_empresa }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- Empresas Contratistas --}}
      <div class="col-md-6 d-none" id="box_contratistas">
        <label class="form-label">Empresas Contratistas</label>
        <select name="empresas_contratistas[]" id="empresas_contratistas" class="form-select" multiple>
          @php
            $selContratistas = collect(old('empresas_contratistas', $contratistasSel ?? []))->map(fn($v)=>(int)$v)->all();
          @endphp
          @foreach($empresas as $e)
            <option value="{{ $e->id }}" @selected(in_array($e->id, $selContratistas))>
              {{ $e->nombre_empresa }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- Empresas Subcontratistas --}}
      <div class="col-md-6 d-none" id="box_subcontratistas">
        <label class="form-label">Empresas Subcontratistas</label>
        <select name="empresas_subcontratistas[]" id="empresas_subcontratistas" class="form-select" multiple>
          @php
            $selSub = collect(old('empresas_subcontratistas', $subcontratistasSel ?? []))->map(fn($v)=>(int)$v)->all();
          @endphp
          @foreach($empresas as $e)
            <option value="{{ $e->id }}" @selected(in_array($e->id, $selSub))>
              {{ $e->nombre_empresa }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- Prevencionista: SEREMI + Firma --}}
      <div class="col-md-6 d-none" id="box_prevencionista">
        <label class="form-label">N° Registro SEREMI</label>
        <input type="text" name="seremi_registro" class="form-control"
               value="{{ old('seremi_registro', $usuario->seremi_registro) }}">

        <div class="mt-2">
          <label class="form-label d-block">Firma (opcional)</label>

          @if(!empty($usuario->firma_path))
            <div class="mb-2">
              <img src="{{ Storage::url($usuario->firma_path) }}" alt="Firma" class="img-thumbnail"
                   style="max-height:120px;object-fit:contain">
            </div>
            <div class="form-check mb-2">
              <input class="form-check-input" type="checkbox" id="remove_firma" name="remove_firma" value="1">
              <label for="remove_firma" class="form-check-label">Eliminar firma</label>
            </div>
          @endif

          <input type="file" name="firma" id="firma" class="form-control" accept="image/*">
          @error('firma') <div class="text-danger small">{{ $message }}</div> @enderror

          <img id="firmaPreview" src="" class="d-none img-thumbnail mt-2" style="max-height:120px;object-fit:contain" alt="Vista previa firma">
        </div>
      </div>
    </div>

    <div class="mt-3">
      <button type="submit" class="btn btn-primary">Guardar cambios</button>
      <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
  </form>
</div>

<script>
(function(){
  const roleSelect = document.getElementById('role_id');

  const boxPrincipales      = document.getElementById('box_principales');
  const boxContratistas     = document.getElementById('box_contratistas');
  const boxSubcontratistas  = document.getElementById('box_subcontratistas');
  const boxPrevencionista   = document.getElementById('box_prevencionista');

  const selPrincipales     = document.getElementById('empresas_principales');

  const setRequired = (el, req) => { if (!el) return; el.toggleAttribute('required', !!req); };

  function toggleByRole() {
    const selected = roleSelect.options[roleSelect.selectedIndex]?.text?.toLowerCase().trim() || '';

    [boxPrincipales, boxContratistas, boxSubcontratistas, boxPrevencionista].forEach(b => b && b.classList.add('d-none'));
    setRequired(selPrincipales, false);

    if (selected === 'principal' || selected === 'visualizador') {
      boxPrincipales.classList.remove('d-none');
      setRequired(selPrincipales, true);
    } else if (selected.includes('contratista') || selected === 'prevencionista') {
      boxPrincipales.classList.remove('d-none');
      boxContratistas.classList.remove('d-none');
      boxSubcontratistas.classList.remove('d-none');

      if (selected === 'prevencionista') {
        boxPrevencionista.classList.remove('d-none');
      }
    }
  }

  roleSelect.addEventListener('change', toggleByRole);
  toggleByRole();

  // Preview para nueva firma
  const inputFirma = document.getElementById('firma');
  const firmaPreview = document.getElementById('firmaPreview');
  if (inputFirma && firmaPreview) {
    inputFirma.addEventListener('change', function(){
      const f = this.files && this.files[0];
      if (!f) { firmaPreview.src=''; firmaPreview.classList.add('d-none'); return; }
      const url = URL.createObjectURL(f);
      firmaPreview.src = url;
      firmaPreview.classList.remove('d-none');
    });
  }
})();
</script>
@endsection
