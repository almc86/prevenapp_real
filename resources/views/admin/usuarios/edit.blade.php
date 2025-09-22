@extends('layouts.app')

@section('title','Editar Usuario')

@section('content')
<div class="space-y-6">
  {{-- Header --}}
  <div class="md:flex md:items-center md:justify-between">
    <div class="min-w-0 flex-1">
      <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
        <i class="bx bx-edit mr-2"></i>
        Editar Usuario
      </h2>
      <p class="mt-1 text-sm text-gray-500">
        Modifica la información y permisos de {{ $usuario->name }}.
      </p>
    </div>
    <div class="mt-4 flex md:mt-0 space-x-3">
      <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">
        <i class="bx bx-arrow-back mr-2"></i>
        Volver a lista
      </a>
    </div>
  </div>

  {{-- Información del Usuario Actual --}}
  <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
    <div class="flex items-center space-x-4">
      <div class="h-16 w-16 rounded-full bg-gradient-to-r from-purple-400 to-pink-400 flex items-center justify-center">
        <span class="text-xl font-bold text-white">
          {{ strtoupper(substr($usuario->name, 0, 2)) }}
        </span>
      </div>
      <div class="flex-1">
        <h3 class="text-lg font-medium text-gray-900">{{ $usuario->name }}</h3>
        <p class="text-sm text-gray-600">{{ $usuario->email }}</p>
        <div class="mt-2 flex items-center space-x-3">
          <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $usuario->activo ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
            <div class="w-1.5 h-1.5 {{ $usuario->activo ? 'bg-green-400' : 'bg-gray-400' }} rounded-full mr-1.5"></div>
            {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
          </span>
          <span class="text-xs text-gray-500">
            Registrado {{ $usuario->created_at->diffForHumans() }}
          </span>
        </div>
      </div>
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
    <form method="POST" action="{{ route('admin.usuarios.update', $usuario) }}" enctype="multipart/form-data" id="form-user" class="divide-y divide-gray-200">
      @csrf
      @method('PUT')

      {{-- Información Básica --}}
      <div class="px-6 py-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-6">
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
                   value="{{ old('name', $usuario->name) }}"
                   placeholder="Ingrese el nombre completo">
          </div>

          <div>
            <label class="form-label">Correo Electrónico *</label>
            <input type="email"
                   name="email"
                   class="form-control"
                   required
                   value="{{ old('email', $usuario->email) }}"
                   placeholder="usuario@empresa.com">
          </div>

          <div>
            <label class="form-label">Nueva Contraseña</label>
            <input type="password"
                   name="password"
                   class="form-control"
                   placeholder="Dejar vacío para mantener la actual">
            <p class="form-help">Solo complete si desea cambiar la contraseña</p>
          </div>

          <div>
            <label class="form-label">Confirmar Nueva Contraseña</label>
            <input type="password"
                   name="password_confirmation"
                   class="form-control"
                   placeholder="Confirme la nueva contraseña">
          </div>
        </div>
      </div>

      {{-- Asignación de Rol --}}
      <div class="px-6 py-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-6">
          <i class="bx bx-shield mr-2"></i>
          Asignación de Rol y Permisos
        </h3>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
          <div class="sm:col-span-2">
            <label class="form-label">Rol del Usuario *</label>
            <select name="role_id" id="role_id" class="form-select" required>
              <option value="">Seleccione un rol...</option>
              @foreach($roles as $rol)
                <option value="{{ $rol->id }}"
                  {{ (string)old('role_id', $roleSelectedId) === (string)$rol->id ? 'selected' : '' }}>
                  {{ ucfirst($rol->name) }}
                </option>
              @endforeach
            </select>
            <p class="form-help">Cambiar el rol afectará los permisos y accesos del usuario</p>
          </div>
        </div>

        {{-- Mostrar rol actual --}}
        @if($usuario->roles->isNotEmpty())
          <div class="mt-4 p-4 bg-gray-50 rounded-lg">
            <h4 class="text-sm font-medium text-gray-900 mb-2">Rol Actual:</h4>
            <div class="flex flex-wrap gap-2">
              @foreach($usuario->roles as $role)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                  <i class="bx bx-shield mr-1"></i>
                  {{ ucfirst($role->name) }}
                </span>
              @endforeach
            </div>
          </div>
        @endif
      </div>

      {{-- Asignación de Empresas --}}
      <div class="px-6 py-6" id="empresas-section">
        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-6">
          <i class="bx bx-buildings mr-2"></i>
          Asignación de Empresas
        </h3>

        {{-- Empresas Principales --}}
        <div class="hidden" id="box_principales">
          <div class="mb-6">
            <label class="form-label">Empresas Principales *</label>
            <select name="empresas_principales[]"
                    id="empresas_principales"
                    class="form-control select-multiple"
                    multiple
                    style="min-height: 120px;">
              @php
                $selPrincipales = collect(old('empresas_principales', $principalesSel ?? []))->map(fn($v)=>(int)$v)->all();
              @endphp
              @foreach($empresas as $e)
                <option value="{{ $e->id }}" @selected(in_array($e->id, $selPrincipales))>
                  {{ $e->nombre_empresa }} - {{ $e->rut_empresa }}
                </option>
              @endforeach
            </select>
            <p class="form-help">Mantenga presionado Ctrl/Cmd para seleccionar múltiples empresas</p>
          </div>
        </div>

        {{-- Empresas Contratistas --}}
        <div class="hidden" id="box_contratistas">
          <div class="mb-6">
            <label class="form-label">Empresas Contratistas</label>
            <select name="empresas_contratistas[]"
                    id="empresas_contratistas"
                    class="form-control select-multiple"
                    multiple
                    style="min-height: 120px;">
              @php
                $selContratistas = collect(old('empresas_contratistas', $contratistasSel ?? []))->map(fn($v)=>(int)$v)->all();
              @endphp
              @foreach($empresas as $e)
                <option value="{{ $e->id }}" @selected(in_array($e->id, $selContratistas))>
                  {{ $e->nombre_empresa }} - {{ $e->rut_empresa }}
                </option>
              @endforeach
            </select>
            <p class="form-help">Empresas contratistas asociadas al usuario</p>
          </div>
        </div>

        {{-- Empresas Subcontratistas --}}
        <div class="hidden" id="box_subcontratistas">
          <div class="mb-6">
            <label class="form-label">Empresas Subcontratistas</label>
            <select name="empresas_subcontratistas[]"
                    id="empresas_subcontratistas"
                    class="form-control select-multiple"
                    multiple
                    style="min-height: 120px;">
              @php
                $selSub = collect(old('empresas_subcontratistas', $subcontratistasSel ?? []))->map(fn($v)=>(int)$v)->all();
              @endphp
              @foreach($empresas as $e)
                <option value="{{ $e->id }}" @selected(in_array($e->id, $selSub))>
                  {{ $e->nombre_empresa }} - {{ $e->rut_empresa }}
                </option>
              @endforeach
            </select>
            <p class="form-help">Empresas subcontratistas asociadas al usuario</p>
          </div>
        </div>

        {{-- Información adicional por rol --}}
        <div class="hidden p-4 bg-blue-50 rounded-lg border border-blue-200" id="role-info">
          <div class="flex">
            <div class="flex-shrink-0">
              <i class="bx bx-info-circle text-blue-400 text-lg"></i>
            </div>
            <div class="ml-3">
              <h4 class="text-sm font-medium text-blue-800" id="role-info-title">
                Información del Rol
              </h4>
              <div class="mt-2 text-sm text-blue-700" id="role-info-content">
                Seleccione un rol para ver la información correspondiente.
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Información Especial Prevencionista --}}
      <div class="px-6 py-6 hidden" id="box_prevencionista">
        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-6">
          <i class="bx bx-certificate mr-2"></i>
          Información Profesional
        </h3>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
          <div>
            <label class="form-label">N° Registro SEREMI</label>
            <input type="text"
                   name="seremi_registro"
                   class="form-control"
                   value="{{ old('seremi_registro', $usuario->seremi_registro) }}"
                   placeholder="Ej: 12345678">
            <p class="form-help">Número de registro en SEREMI de Salud</p>
          </div>

          <div>
            <label class="form-label">Firma Digital</label>

            {{-- Firma actual --}}
            @if(!empty($usuario->firma_path))
              <div class="mb-4 p-4 bg-gray-50 rounded-lg border">
                <label class="text-sm font-medium text-gray-700 block mb-2">Firma actual:</label>
                <div class="flex items-center justify-between">
                  <img src="{{ Storage::url($usuario->firma_path) }}"
                       alt="Firma actual"
                       class="max-h-24 border rounded"
                       style="object-fit: contain;">
                  <div class="ml-4">
                    <div class="flex items-center">
                      <input class="form-check-input mr-2"
                             type="checkbox"
                             id="remove_firma"
                             name="remove_firma"
                             value="1">
                      <label for="remove_firma" class="text-sm text-red-600 font-medium">
                        <i class="bx bx-trash mr-1"></i>
                        Eliminar firma actual
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            @endif

            {{-- Nueva firma --}}
            <input type="file"
                   name="firma"
                   id="firma"
                   class="form-control"
                   accept="image/*">
            @error('firma')
              <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
            <p class="form-help">
              @if(!empty($usuario->firma_path))
                Seleccione una nueva imagen para reemplazar la firma actual
              @else
                Imagen de la firma en formato JPG, PNG o GIF (máx. 2MB)
              @endif
            </p>

            {{-- Preview de nueva firma --}}
            <div class="mt-3 hidden" id="firma-preview-container">
              <label class="text-sm font-medium text-gray-700">Vista previa de nueva firma:</label>
              <div class="mt-1 border-2 border-dashed border-gray-300 rounded-lg p-4">
                <img id="firmaPreview"
                     src=""
                     class="max-h-32 mx-auto object-contain"
                     alt="Vista previa de la nueva firma">
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Botones de acción --}}
      <div class="px-6 py-4 bg-gray-50 flex items-center justify-between">
        <div class="text-sm text-gray-500">
          <i class="bx bx-time mr-1"></i>
          Última modificación: {{ $usuario->updated_at->diffForHumans() }}
        </div>
        <div class="flex items-center space-x-3">
          <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">
            Cancelar
          </a>
          <button type="submit" class="btn btn-primary">
            <i class="bx bx-save mr-2"></i>
            Guardar Cambios
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
(function(){
  const roleSelect = document.getElementById('role_id');

  const boxPrincipales      = document.getElementById('box_principales');
  const boxContratistas     = document.getElementById('box_contratistas');
  const boxSubcontratistas  = document.getElementById('box_subcontratistas');
  const boxPrevencionista   = document.getElementById('box_prevencionista');
  const roleInfo            = document.getElementById('role-info');
  const roleInfoTitle       = document.getElementById('role-info-title');
  const roleInfoContent     = document.getElementById('role-info-content');

  const selPrincipales     = document.getElementById('empresas_principales');
  const selContratistas    = document.getElementById('empresas_contratistas');
  const selSubcontratistas = document.getElementById('empresas_subcontratistas');

  // Información de cada rol
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

  const setRequired = (el, req) => { if (!el) return; el.toggleAttribute('required', !!req); };

  function toggleByRole() {
    const selected = roleSelect.options[roleSelect.selectedIndex]?.text?.toLowerCase().trim() || '';

    // Resetea visibilidad con animaciones
    [boxPrincipales, boxContratistas, boxSubcontratistas, boxPrevencionista].forEach(b => {
      if (b) {
        b.style.transition = 'all 0.3s ease';
        b.classList.add('hidden');
      }
    });

    setRequired(selPrincipales, false);

    // Ocultar info de rol inicialmente
    if (roleInfo) {
      roleInfo.style.transition = 'all 0.3s ease';
      roleInfo.classList.add('hidden');
    }

    // Si hay un rol seleccionado, mostrar información
    if (selected && roleInfoData[selected]) {
      setTimeout(() => {
        if (roleInfo && roleInfoTitle && roleInfoContent) {
          roleInfoTitle.textContent = roleInfoData[selected].title;
          roleInfoContent.textContent = roleInfoData[selected].content;
          roleInfo.classList.remove('hidden');
        }
      }, 150);
    }

    // Reglas de empresas según rol
    if (selected === 'principal' || selected === 'visualizador') {
      setTimeout(() => {
        if (boxPrincipales) {
          boxPrincipales.classList.remove('hidden');
          setRequired(selPrincipales, true);
        }
      }, 150);
    } else if (selected.includes('contratista') || selected === 'prevencionista') {
      setTimeout(() => {
        if (boxPrincipales) boxPrincipales.classList.remove('hidden');
        if (boxContratistas) boxContratistas.classList.remove('hidden');
        if (boxSubcontratistas) boxSubcontratistas.classList.remove('hidden');

        if (selected === 'prevencionista' && boxPrevencionista) {
          boxPrevencionista.classList.remove('hidden');
        }
      }, 150);
    }
  }

  roleSelect.addEventListener('change', toggleByRole);
  toggleByRole(); // estado inicial

  // Preview firma mejorado
  const inputFirma = document.getElementById('firma');
  const firmaPreview = document.getElementById('firmaPreview');
  const firmaPreviewContainer = document.getElementById('firma-preview-container');

  if (inputFirma && firmaPreview && firmaPreviewContainer) {
    inputFirma.addEventListener('change', function(){
      const file = this.files && this.files[0];

      if (!file) {
        firmaPreview.src = '';
        firmaPreviewContainer.classList.add('hidden');
        return;
      }

      // Validar tipo de archivo
      if (!file.type.startsWith('image/')) {
        alert('Por favor seleccione un archivo de imagen válido.');
        this.value = '';
        return;
      }

      // Validar tamaño (2MB máximo)
      if (file.size > 2 * 1024 * 1024) {
        alert('El archivo debe ser menor a 2MB.');
        this.value = '';
        return;
      }

      const url = URL.createObjectURL(file);
      firmaPreview.src = url;
      firmaPreviewContainer.classList.remove('hidden');
    });
  }

  // Mejorar la experiencia de los select múltiples
  document.querySelectorAll('.select-multiple').forEach(select => {
    select.style.borderRadius = '0.5rem';
    select.style.border = '1px solid #d1d5db';
    select.style.padding = '0.5rem';

    // Agregar evento para mejorar la experiencia
    select.addEventListener('focus', function() {
      this.style.borderColor = '#3b82f6';
      this.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.1)';
    });

    select.addEventListener('blur', function() {
      this.style.borderColor = '#d1d5db';
      this.style.boxShadow = 'none';
    });
  });

  // Validación adicional del formulario
  const form = document.getElementById('form-user');
  if (form) {
    form.addEventListener('submit', function(e) {
      // Verificar que se hayan seleccionado empresas cuando es requerido
      const selectedRole = roleSelect.options[roleSelect.selectedIndex]?.text?.toLowerCase().trim() || '';

      if ((selectedRole === 'principal' || selectedRole === 'visualizador') && selPrincipales) {
        if (selPrincipales.selectedOptions.length === 0) {
          e.preventDefault();
          alert('Debe seleccionar al menos una empresa principal para este rol.');
          selPrincipales.focus();
          return;
        }
      }
    });
  }

  // Confirmación para cambios importantes
  let originalRoleValue = roleSelect.value;
  roleSelect.addEventListener('change', function() {
    if (originalRoleValue && this.value !== originalRoleValue) {
      const confirmChange = confirm('¿Está seguro que desea cambiar el rol? Esto afectará los permisos del usuario.');
      if (!confirmChange) {
        this.value = originalRoleValue;
        toggleByRole();
        return;
      }
    }
    originalRoleValue = this.value;
  });
})();
</script>
@endpush
@endsection
