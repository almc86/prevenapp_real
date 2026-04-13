@extends('layouts.app')

@section('title','Crear Usuario')

@section('content')
<div class="space-y-6">
  {{-- Header --}}
  <div class="md:flex md:items-center md:justify-between">
    <div class="min-w-0 flex-1">
      <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate sm:text-3xl sm:tracking-tight">
        <i class="bx bx-user-plus mr-2"></i>
        Crear Usuario
      </h2>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
        Complete la información para crear un nuevo usuario en el sistema.
      </p>
    </div>
    <div class="mt-4 flex md:mt-0">
      <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">
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
    <form method="POST" action="{{ route('admin.usuarios.store') }}" enctype="multipart/form-data" id="form-user" class="divide-y divide-gray-200 dark:divide-gray-700">
      @csrf

      {{-- Información Básica --}}
      <div class="px-6 py-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-6">
          <i class="bx bx-id-card mr-2"></i>
          Información Básica
        </h3>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
          <div>
            <label class="form-label">Nombre Completo *</label>
            <input type="text"
                   name="name"
                   class="form-control"
                   required
                   value="{{ old('name') }}"
                   placeholder="Ingrese el nombre completo">
          </div>

          <div>
            <label class="form-label">Correo Electrónico *</label>
            <input type="email"
                   name="email"
                   class="form-control"
                   required
                   value="{{ old('email') }}"
                   placeholder="usuario@empresa.com">
          </div>

          <div>
            <label class="form-label">Contraseña *</label>
            <input type="password"
                   name="password"
                   class="form-control"
                   required
                   placeholder="Mínimo 8 caracteres">
            <p class="form-help">La contraseña debe tener al menos 8 caracteres</p>
          </div>

          <div>
            <label class="form-label">Confirmar Contraseña *</label>
            <input type="password"
                   name="password_confirmation"
                   class="form-control"
                   required
                   placeholder="Confirme la contraseña">
          </div>
        </div>
      </div>

      {{-- Asignación de Rol --}}
      <div class="px-6 py-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-6">
          <i class="bx bx-shield mr-2"></i>
          Asignación de Rol y Permisos
        </h3>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
          <div class="sm:col-span-2">
            <label class="form-label">Rol del Usuario *</label>
            <select name="role_id" id="role_id" class="form-select" required>
              <option value="">Seleccione un rol...</option>
              @foreach($roles as $rol)
                <option value="{{ $rol->id }}" {{ old('role_id') == $rol->id ? 'selected' : '' }}>
                  {{ ucfirst($rol->name) }}
                </option>
              @endforeach
            </select>
            <p class="form-help">El rol determina los permisos y accesos del usuario</p>
          </div>
        </div>
      </div>

      {{-- Asignación de Empresas --}}
      <div class="px-6 py-6" id="empresas-section">
        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-6">
          <i class="bx bx-buildings mr-2"></i>
          Asignación de Empresas
        </h3>

        {{-- Empresas Principales --}}
        <div class="hidden" id="box_principales">
          <div class="mb-6">
            <label class="form-label">Empresas Principales *</label>
            <div class="multi-select-wrapper" id="ms_principales">
              <div class="multi-select-trigger" tabindex="0">
                <div class="multi-select-tags" id="tags_principales">
                  <span class="multi-select-placeholder">Seleccione empresas...</span>
                </div>
                <i class="bx bx-chevron-down multi-select-arrow"></i>
              </div>
              <div class="multi-select-dropdown hidden">
                <div class="multi-select-search-wrap">
                  <i class="bx bx-search"></i>
                  <input type="text" class="multi-select-search" placeholder="Buscar empresa...">
                </div>
                <ul class="multi-select-options">
                  @foreach($empresas as $e)
                    <li>
                      <label class="multi-select-option">
                        <input type="checkbox"
                               name="empresas_principales[]"
                               value="{{ $e->id }}"
                               {{ collect(old('empresas_principales',[]))->contains($e->id) ? 'checked' : '' }}>
                        <span>{{ $e->nombre_empresa }} - {{ $e->rut_empresa }}</span>
                      </label>
                    </li>
                  @endforeach
                </ul>
              </div>
            </div>
            <p class="form-help">Seleccione una o varias empresas principales</p>
          </div>
        </div>

        {{-- Empresas Contratistas --}}
        <div class="hidden" id="box_contratistas">
          <div class="mb-6">
            <label class="form-label">Empresas Contratistas</label>
            <div class="multi-select-wrapper" id="ms_contratistas">
              <div class="multi-select-trigger" tabindex="0">
                <div class="multi-select-tags" id="tags_contratistas">
                  <span class="multi-select-placeholder">Seleccione empresas...</span>
                </div>
                <i class="bx bx-chevron-down multi-select-arrow"></i>
              </div>
              <div class="multi-select-dropdown hidden">
                <div class="multi-select-search-wrap">
                  <i class="bx bx-search"></i>
                  <input type="text" class="multi-select-search" placeholder="Buscar empresa...">
                </div>
                <ul class="multi-select-options">
                  @foreach($empresas as $e)
                    <li>
                      <label class="multi-select-option">
                        <input type="checkbox"
                               name="empresas_contratistas[]"
                               value="{{ $e->id }}"
                               {{ collect(old('empresas_contratistas',[]))->contains($e->id) ? 'checked' : '' }}>
                        <span>{{ $e->nombre_empresa }} - {{ $e->rut_empresa }}</span>
                      </label>
                    </li>
                  @endforeach
                </ul>
              </div>
            </div>
            <p class="form-help">Empresas contratistas asociadas al usuario</p>
          </div>
        </div>

        {{-- Empresas Subcontratistas --}}
        <div class="hidden" id="box_subcontratistas">
          <div class="mb-6">
            <label class="form-label">Empresas Subcontratistas</label>
            <div class="multi-select-wrapper" id="ms_subcontratistas">
              <div class="multi-select-trigger" tabindex="0">
                <div class="multi-select-tags" id="tags_subcontratistas">
                  <span class="multi-select-placeholder">Seleccione empresas...</span>
                </div>
                <i class="bx bx-chevron-down multi-select-arrow"></i>
              </div>
              <div class="multi-select-dropdown hidden">
                <div class="multi-select-search-wrap">
                  <i class="bx bx-search"></i>
                  <input type="text" class="multi-select-search" placeholder="Buscar empresa...">
                </div>
                <ul class="multi-select-options">
                  @foreach($empresas as $e)
                    <li>
                      <label class="multi-select-option">
                        <input type="checkbox"
                               name="empresas_subcontratistas[]"
                               value="{{ $e->id }}"
                               {{ collect(old('empresas_subcontratistas',[]))->contains($e->id) ? 'checked' : '' }}>
                        <span>{{ $e->nombre_empresa }} - {{ $e->rut_empresa }}</span>
                      </label>
                    </li>
                  @endforeach
                </ul>
              </div>
            </div>
            <p class="form-help">Empresas subcontratistas asociadas al usuario</p>
          </div>
        </div>

        {{-- Información adicional por rol --}}
        <div class="hidden p-4 bg-blue-50 dark:bg-blue-900 rounded-lg border border-blue-200 dark:border-blue-700" id="role-info">
          <div class="flex">
            <div class="flex-shrink-0">
              <i class="bx bx-info-circle text-blue-400 dark:text-blue-300 text-lg"></i>
            </div>
            <div class="ml-3">
              <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200" id="role-info-title">
                Información del Rol
              </h4>
              <div class="mt-2 text-sm text-blue-700 dark:text-blue-300" id="role-info-content">
                Seleccione un rol para ver la información correspondiente.
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Información Especial Prevencionista --}}
      <div class="px-6 py-6 hidden" id="box_prevencionista">
        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-6">
          <i class="bx bx-certificate mr-2"></i>
          Información Profesional
        </h3>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
          <div>
            <label class="form-label">N° Registro SEREMI</label>
            <input type="text"
                   name="seremi_registro"
                   class="form-control"
                   value="{{ old('seremi_registro') }}"
                   placeholder="Ej: 12345678">
            <p class="form-help">Número de registro en SEREMI de Salud</p>
          </div>

          <div>
            <label class="form-label">Firma Digital (opcional)</label>
            <input type="file"
                   name="firma"
                   id="firma"
                   class="form-control"
                   accept="image/*">
            @error('firma')
              <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
            <p class="form-help">Imagen de la firma en formato JPG, PNG o GIF (máx. 2MB)</p>

            {{-- Preview de la firma --}}
            <div class="mt-3 hidden" id="firma-preview-container">
              <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Vista previa:</label>
              <div class="mt-1 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4">
                <img id="firmaPreview"
                     src=""
                     class="max-h-32 mx-auto object-contain"
                     alt="Vista previa de la firma">
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Botones de acción --}}
      <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 flex items-center justify-end space-x-3">
        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">
          Cancelar
        </a>
        <button type="submit" class="btn btn-primary">
          <i class="bx bx-save mr-2"></i>
          Crear Usuario
        </button>
      </div>
    </form>
  </div>
</div>

@push('head')
<style>
  .multi-select-wrapper { position: relative; }
  .multi-select-trigger {
    display: flex; align-items: center; justify-content: space-between;
    min-height: 42px; padding: 6px 12px; cursor: pointer;
    border: 1px solid #d1d5db; border-radius: 0.5rem;
    background: var(--ms-bg, #fff); transition: border-color .2s, box-shadow .2s;
  }
  .dark .multi-select-trigger { border-color: #4b5563; background: #374151; color: #e5e7eb; }
  .multi-select-trigger:focus, .multi-select-wrapper.open .multi-select-trigger {
    border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.15); outline: none;
  }
  .multi-select-tags { display: flex; flex-wrap: wrap; gap: 4px; flex: 1; }
  .multi-select-placeholder { color: #9ca3af; font-size: .875rem; }
  .multi-select-tag {
    display: inline-flex; align-items: center; gap: 4px;
    background: #dbeafe; color: #1e40af; font-size: .75rem; font-weight: 500;
    padding: 2px 8px; border-radius: 9999px; max-width: 220px;
  }
  .dark .multi-select-tag { background: #1e3a5f; color: #93c5fd; }
  .multi-select-tag span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
  .multi-select-tag button {
    background: none; border: none; cursor: pointer; color: inherit;
    font-size: .875rem; line-height: 1; padding: 0; opacity: .7;
  }
  .multi-select-tag button:hover { opacity: 1; }
  .multi-select-arrow { font-size: 1.25rem; color: #6b7280; transition: transform .2s; flex-shrink: 0; }
  .multi-select-wrapper.open .multi-select-arrow { transform: rotate(180deg); }
  .multi-select-dropdown {
    position: absolute; top: calc(100% + 4px); left: 0; right: 0;
    background: #fff; border: 1px solid #d1d5db; border-radius: 0.5rem;
    box-shadow: 0 10px 15px -3px rgba(0,0,0,.1); z-index: 50;
    max-height: 280px; flex-direction: column;
  }
  .multi-select-dropdown.hidden { display: none !important; }
  .multi-select-wrapper.open .multi-select-dropdown { display: flex; }
  .dark .multi-select-dropdown { background: #1f2937; border-color: #4b5563; }
  .multi-select-search-wrap {
    display: flex; align-items: center; padding: 8px 12px;
    border-bottom: 1px solid #e5e7eb; gap: 8px;
  }
  .dark .multi-select-search-wrap { border-bottom-color: #374151; }
  .multi-select-search-wrap i { color: #9ca3af; font-size: 1rem; }
  .multi-select-search {
    border: none; outline: none; width: 100%; font-size: .875rem;
    background: transparent; color: inherit;
  }
  .dark .multi-select-search { color: #e5e7eb; }
  .multi-select-search::placeholder { color: #9ca3af; }
  .multi-select-options {
    list-style: none; margin: 0; padding: 4px 0;
    overflow-y: auto; flex: 1;
  }
  .multi-select-option {
    display: flex; align-items: center; gap: 8px; padding: 8px 12px;
    cursor: pointer; font-size: .875rem; transition: background .15s;
  }
  .multi-select-option:hover { background: #f3f4f6; }
  .dark .multi-select-option:hover { background: #374151; }
  .multi-select-option input[type="checkbox"] {
    width: 16px; height: 16px; accent-color: #3b82f6; cursor: pointer; flex-shrink: 0;
  }
  .multi-select-option span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
  .multi-select-options li.ms-hidden { display: none; }
</style>
@endpush

@push('scripts')
<script>
(function(){
  /* ─── Multi-select component ─── */
  const allMultiSelects = [];

  function closeAllMultiSelects(except) {
    allMultiSelects.forEach(ms => {
      if (ms.wrapper !== except) {
        ms.dropdown.classList.add('hidden');
        ms.wrapper.classList.remove('open');
      }
    });
  }

  function initMultiSelect(wrapper) {
    const trigger  = wrapper.querySelector('.multi-select-trigger');
    const dropdown = wrapper.querySelector('.multi-select-dropdown');
    const tagsDiv  = wrapper.querySelector('.multi-select-tags');
    const search   = wrapper.querySelector('.multi-select-search');
    const items    = wrapper.querySelectorAll('.multi-select-options li');
    const checkboxes = wrapper.querySelectorAll('input[type="checkbox"]');

    allMultiSelects.push({ wrapper, dropdown });

    function renderTags() {
      const checked = [...checkboxes].filter(cb => cb.checked);
      tagsDiv.innerHTML = '';
      if (checked.length === 0) {
        tagsDiv.innerHTML = '<span class="multi-select-placeholder">Seleccione empresas...</span>';
        return;
      }
      checked.forEach(cb => {
        const tag = document.createElement('span');
        tag.className = 'multi-select-tag';
        const txt = document.createElement('span');
        txt.textContent = cb.closest('label').querySelector('span').textContent;
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.innerHTML = '&times;';
        btn.addEventListener('click', e => { e.stopPropagation(); cb.checked = false; renderTags(); });
        tag.append(txt, btn);
        tagsDiv.appendChild(tag);
      });
    }

    function toggle(open) {
      const show = open !== undefined ? open : dropdown.classList.contains('hidden');
      if (show) closeAllMultiSelects(wrapper);
      dropdown.classList.toggle('hidden', !show);
      wrapper.classList.toggle('open', show);
      if (show) { search.value = ''; filterList(''); search.focus(); }
    }

    function filterList(q) {
      const term = q.toLowerCase();
      items.forEach(li => {
        const text = li.textContent.toLowerCase();
        li.classList.toggle('ms-hidden', !text.includes(term));
      });
    }

    trigger.addEventListener('click', () => toggle());
    trigger.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggle(); }});
    search.addEventListener('input', () => filterList(search.value));
    checkboxes.forEach(cb => cb.addEventListener('change', () => renderTags()));

    renderTags();
  }

  document.querySelectorAll('.multi-select-wrapper').forEach(initMultiSelect);

  // Cerrar al hacer click fuera de cualquier multi-select
  document.addEventListener('click', e => {
    const inAny = allMultiSelects.some(ms => ms.wrapper.contains(e.target));
    if (!inAny) closeAllMultiSelects(null);
  });

  /* ─── Role toggle logic ─── */
  const roleSelect = document.getElementById('role_id');

  const boxPrincipales      = document.getElementById('box_principales');
  const boxContratistas     = document.getElementById('box_contratistas');
  const boxSubcontratistas  = document.getElementById('box_subcontratistas');
  const boxPrevencionista   = document.getElementById('box_prevencionista');
  const roleInfo            = document.getElementById('role-info');
  const roleInfoTitle       = document.getElementById('role-info-title');
  const roleInfoContent     = document.getElementById('role-info-content');

  const roleInfoData = {
    'principal': {
      title: 'Rol: Principal',
      content: 'Usuario administrador de empresas principales. Tiene acceso completo para gestionar sus empresas y contratos.'
    },
    'visualizador': {
      title: 'Rol: Visualizador',
      content: 'Usuario con permisos de solo lectura. Puede ver información pero no realizar modificaciones.'
    },
    'contratista': {
      title: 'Rol: Contratista',
      content: 'Usuario de empresa contratista. Puede gestionar información relacionada con sus contratos y trabajadores.'
    },
    'subcontratista': {
      title: 'Rol: Subcontratista',
      content: 'Usuario de empresa subcontratista. Tiene permisos limitados según la relación contractual.'
    },
    'prevencionista': {
      title: 'Rol: Prevencionista de Riesgos',
      content: 'Profesional especializado en prevención de riesgos. Requiere registro SEREMI y puede cargar firma digital.'
    },
    'administrador': {
      title: 'Rol: Administrador',
      content: 'Usuario con permisos completos del sistema. Puede gestionar todos los aspectos de la aplicación.'
    }
  };

  function toggleByRole() {
    const selected = roleSelect.options[roleSelect.selectedIndex]?.text?.toLowerCase().trim() || '';

    [boxPrincipales, boxContratistas, boxSubcontratistas, boxPrevencionista].forEach(b => {
      if (b) { b.style.transition = 'all 0.3s ease'; b.classList.add('hidden'); }
    });

    if (roleInfo) { roleInfo.style.transition = 'all 0.3s ease'; roleInfo.classList.add('hidden'); }

    if (selected && roleInfoData[selected]) {
      setTimeout(() => {
        if (roleInfo && roleInfoTitle && roleInfoContent) {
          roleInfoTitle.textContent = roleInfoData[selected].title;
          roleInfoContent.textContent = roleInfoData[selected].content;
          roleInfo.classList.remove('hidden');
        }
      }, 150);
    }

    setTimeout(() => {
      if (selected === 'principal' || selected === 'visualizador') {
        if (boxPrincipales) boxPrincipales.classList.remove('hidden');
      } else if (selected === 'contratista') {
        if (boxPrincipales) boxPrincipales.classList.remove('hidden');
        if (boxContratistas) boxContratistas.classList.remove('hidden');
      } else if (selected.includes('subcontratista') || selected === 'sub contratista') {
        if (boxPrincipales) boxPrincipales.classList.remove('hidden');
        if (boxContratistas) boxContratistas.classList.remove('hidden');
        if (boxSubcontratistas) boxSubcontratistas.classList.remove('hidden');
      } else if (selected === 'prevencionista') {
        if (boxPrincipales) boxPrincipales.classList.remove('hidden');
        if (boxContratistas) boxContratistas.classList.remove('hidden');
        if (boxSubcontratistas) boxSubcontratistas.classList.remove('hidden');
        if (boxPrevencionista) boxPrevencionista.classList.remove('hidden');
      }
    }, 150);
  }

  roleSelect.addEventListener('change', toggleByRole);
  toggleByRole();

  // Preview firma
  const inputFirma = document.getElementById('firma');
  const firmaPreview = document.getElementById('firmaPreview');
  const firmaPreviewContainer = document.getElementById('firma-preview-container');

  if (inputFirma && firmaPreview && firmaPreviewContainer) {
    inputFirma.addEventListener('change', function(){
      const file = this.files && this.files[0];
      if (!file) { firmaPreview.src = ''; firmaPreviewContainer.classList.add('hidden'); return; }
      if (!file.type.startsWith('image/')) { alert('Por favor seleccione un archivo de imagen válido.'); this.value = ''; return; }
      if (file.size > 2 * 1024 * 1024) { alert('El archivo debe ser menor a 2MB.'); this.value = ''; return; }
      firmaPreview.src = URL.createObjectURL(file);
      firmaPreviewContainer.classList.remove('hidden');
    });
  }

  // Validación del formulario
  const form = document.getElementById('form-user');
  if (form) {
    form.addEventListener('submit', function(e) {
      const selectedRole = roleSelect.options[roleSelect.selectedIndex]?.text?.toLowerCase().trim() || '';
      if (selectedRole === 'principal' || selectedRole === 'visualizador') {
        const checked = document.querySelectorAll('#ms_principales input[type="checkbox"]:checked');
        if (checked.length === 0) {
          e.preventDefault();
          alert('Debe seleccionar al menos una empresa principal para este rol.');
          return;
        }
      }
    });
  }
})();
</script>
@endpush
@endsection
