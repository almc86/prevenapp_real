@extends('layouts.app')
@section('title', $titulo.' - '.$config->nombre.' - '.$empresa->nombre_empresa)

@section('content')
<div class="space-y-6">
  {{-- Mensajes --}}
  @if(session('success'))
    <div class="alert alert-success">
      <div class="flex items-center">
        <i class="bx bx-check-circle text-lg mr-2"></i>
        <span>{{ session('success') }}</span>
      </div>
    </div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger">
      <div class="flex items-center mb-2">
        <i class="bx bx-error-circle text-lg mr-2"></i>
        <span class="font-medium">Por favor corrige los siguientes errores:</span>
      </div>
      <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $e)
          <li class="text-sm">{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Header --}}
  <div class="md:flex md:items-center md:justify-between">
    <div class="min-w-0 flex-1">
      <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate sm:text-3xl sm:tracking-tight">
        <i class="bx {{ $ambito === 'empresa' ? 'bx-buildings' : 'bx-car' }} mr-2"></i>
        {{ $titulo }}
      </h2>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
        <strong>{{ $empresa->nombre_empresa }}</strong> - {{ $config->nombre }}
      </p>
    </div>
    <div class="mt-4 flex md:mt-0 space-x-3">
      <a href="{{ route('admin.config-empresas.show', [$empresa, $config]) }}" class="btn btn-secondary">
        <i class="bx bx-arrow-back mr-2"></i>
        Volver a configuración
      </a>
    </div>
  </div>

  {{-- Agregar categoría --}}
  <div class="bg-white dark:bg-gray-800 shadow-soft rounded-xl overflow-visible">
    <div class="px-6 py-6">
      <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-6">
        <i class="bx bx-plus-circle mr-2"></i>
        Agregar Categoría de {{ $ambito === 'empresa' ? 'Empresa' : 'Flota' }}
      </h3>
      @if($catsDisp->count() > 0)
        <form method="POST" action="{{ route('admin.config-empresas.categoria.store', [$empresa, $config]) }}">
          @csrf
          <div class="grid grid-cols-1 gap-6 sm:grid-cols-4 items-end">
            <div class="sm:col-span-3">
              <label class="form-label">Categoría disponible *</label>
              <div class="searchable-select relative" data-name="categoria_id">
                <input type="hidden" name="categoria_id" required>
                <div class="form-select flex items-center justify-between cursor-pointer" id="catDropdownToggle">
                  <input type="text" class="bg-transparent border-none outline-none w-full text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500" placeholder="Buscar categoría..." autocomplete="off" id="catSearchInput">
                  <i class="bx bx-chevron-down text-gray-400 ml-2 transition-transform" id="catChevron"></i>
                </div>
                <ul class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow-lg max-h-60 overflow-y-auto hidden" id="catDropdownList">
                  @foreach($catsDisp as $cat)
                    <li class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-primary-50 dark:hover:bg-primary-900 cursor-pointer" data-value="{{ $cat->id }}">{{ $cat->nombre }}</li>
                  @endforeach
                  <li class="px-4 py-2 text-sm text-gray-400 dark:text-gray-500 hidden" id="catNoResults">Sin resultados</li>
                </ul>
              </div>
              <p class="form-help">Solo se muestran categorías de ámbito {{ $ambito === 'empresa' ? 'Empresa' : 'Flota' }}</p>
            </div>
            <div class="sm:col-span-1">
              <button type="submit" class="btn btn-primary w-full">
                <i class="bx bx-plus mr-2"></i>
                Añadir
              </button>
            </div>
          </div>
        </form>
      @else
        <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
          <div class="flex">
            <i class="bx bx-info-circle text-yellow-400 text-xl mr-3"></i>
            <div>
              <p class="text-sm text-yellow-800 dark:text-yellow-200">
                No hay categorías disponibles de ámbito <strong>{{ $ambito === 'empresa' ? 'Empresa' : 'Flota' }}</strong>.
              </p>
              <p class="text-xs text-yellow-700 dark:text-yellow-300 mt-1">
                <a href="{{ route('admin.categorias.create') }}" class="underline">Crea una nueva categoría</a> con ámbito "{{ $ambito === 'empresa' ? 'Empresa (Global)' : 'Flota' }}" primero.
              </p>
            </div>
          </div>
        </div>
      @endif
    </div>
  </div>

  {{-- Listado de categorías ya asociadas --}}
  @forelse($catsSel as $cat)
    <div class="bg-white dark:bg-gray-800 shadow-soft rounded-xl overflow-hidden">
      {{-- Header de la categoría --}}
      <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600 flex items-center justify-between">
        <div class="flex items-center">
          <div class="flex h-10 w-10 items-center justify-center rounded-lg {{ $ambito === 'empresa' ? 'bg-purple-100 dark:bg-purple-800' : 'bg-orange-100 dark:bg-orange-800' }}">
            <i class="bx {{ $ambito === 'empresa' ? 'bx-buildings' : 'bx-car' }} {{ $ambito === 'empresa' ? 'text-purple-600 dark:text-purple-300' : 'text-orange-600 dark:text-orange-300' }}"></i>
          </div>
          <div class="ml-3">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $cat->nombre }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Gestiona documentos de {{ $ambito === 'empresa' ? 'empresa' : 'flota' }} para esta categoría</p>
          </div>
        </div>
        <form method="POST" action="{{ route('admin.config-empresas.categoria.destroy', [$empresa, $config, $cat->id]) }}"
              onsubmit="return confirm('¿Quitar categoría de la configuración?')">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-sm btn-outline-danger">
            <i class="bx bx-trash mr-2"></i>
            Quitar
          </button>
        </form>
      </div>

      @php
        // Documentos ya configurados en esta categoría (para excluirlos del select)
        $docs_config = \App\Models\ConfiguracionCategoriaDocumento::with(['documento.tipo','items'])
            ->where('configuracion_id',$config->id)
            ->where('categoria_id',$cat->id)
            ->orderBy('id','desc')
            ->get();
        $usedDocIds = $docs_config->pluck('documento_id')->all();
      @endphp

      <div class="p-6">
        {{-- Form para agregar documento a esta categoría --}}
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
          <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">
            <i class="bx bx-file-plus mr-2"></i>
            Agregar Documento
          </h4>
          @if($documentos->count() > 0)
            <form method="POST" action="{{ route('admin.config-empresas.documento.store', [$empresa, $config, $cat->id]) }}" enctype="multipart/form-data">
              @csrf
              <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-6">
                <div class="lg:col-span-2">
                  <label class="form-label">Documento *</label>
                  <x-searchable-select
                    name="documento_id"
                    :options="$documentos->map(fn($d) => ['value' => $d->id, 'label' => $d->nombre])"
                    :exclude="$usedDocIds"
                    placeholder="Seleccione documento..."
                    empty-text="Todos los documentos disponibles ya están agregados a esta categoría."
                    required
                  />
                </div>
                <div>
                  <label class="form-label">Obligatorio</label>
                  <select name="obligatorio" class="form-select">
                    <option value="0">No</option>
                    <option value="1">Sí</option>
                  </select>
                </div>
                <div>
                  <label class="form-label">Vencimiento</label>
                  <select name="vencimiento_modo" class="form-select">
                    <option value="por_documento">Por documento</option>
                    <option value="por_meses">Por meses</option>
                    <option value="sin_vencimiento">Sin vencimiento</option>
                  </select>
                </div>
                <div>
                  <label class="form-label">Meses</label>
                  <input type="number" min="1" max="120" name="meses_vencimiento" class="form-control" placeholder="12">
                </div>
                <div>
                  <label class="form-label">Plantilla</label>
                  <input type="file" name="plantilla" class="form-control text-sm" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg,.webp">
                </div>
              </div>
              <div class="mt-4">
                <button type="submit" class="btn btn-success">
                  <i class="bx bx-plus mr-2"></i>
                  Agregar documento
                </button>
              </div>
            </form>
          @else
            <div class="text-sm text-yellow-600 dark:text-yellow-400">
              <i class="bx bx-info-circle mr-1"></i>
              No hay documentos de tipo {{ $ambito }} disponibles. Crea documentos de este tipo primero.
            </div>
          @endif
        </div>

        {{-- Documentos configurados en esta categoría (la query se hizo arriba) --}}

        <div class="space-y-4">
          <h4 class="text-md font-medium text-gray-900 dark:text-white">
            <i class="bx bx-file mr-2"></i>
            Documentos Configurados ({{ $docs_config->count() }})
          </h4>

          @forelse($docs_config as $cfg)
            <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 bg-white dark:bg-gray-800 hover:shadow-sm transition-shadow">
              {{-- Info principal del documento --}}
              <div class="flex items-start justify-between">
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                  <div>
                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $cfg->documento->nombre }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst(optional($cfg->documento->tipo)->nombre) }}</div>
                  </div>
                  <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Obligatorio</div>
                    <div class="text-sm">
                      <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $cfg->obligatorio ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $cfg->obligatorio ? 'Sí' : 'No' }}
                      </span>
                    </div>
                  </div>
                  <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Vencimiento</div>
                    <div class="text-sm text-gray-900 dark:text-white">
                      @switch($cfg->vencimiento_modo)
                        @case('por_documento') Por documento @break
                        @case('por_meses') {{ $cfg->meses_vencimiento }} meses @break
                        @default Sin vencimiento
                      @endswitch
                    </div>
                  </div>
                  <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Estado</div>
                    <div class="text-sm">
                      <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $cfg->estado ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $cfg->estado ? 'Activo' : 'Inactivo' }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              {{-- Información adicional --}}
              <div class="mt-3 flex items-center justify-between">
                <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                  <div class="flex items-center">
                    <i class="bx bx-file-blank mr-1"></i>
                    Plantilla:
                    @if($cfg->plantilla_path)
                      <a href="{{ asset('storage/'.$cfg->plantilla_path) }}" target="_blank" class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 ml-1">Ver archivo</a>
                    @else
                      <span class="ml-1">No disponible</span>
                    @endif
                  </div>
                  <div class="flex items-center">
                    <i class="bx bx-list-ul mr-1"></i>
                    {{ $cfg->items->count() }} ítems
                  </div>
                </div>

                {{-- Acciones --}}
                <div class="flex items-center space-x-2">
                  <form method="POST" action="{{ route('admin.config-empresas.documento.destroy', [$empresa, $config, $cat->id, $cfg->id]) }}"
                        onsubmit="return confirm('¿Cambiar estado de este documento?')" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm {{ $cfg->estado ? 'btn-outline-danger' : 'btn-outline-success' }}">
                      <i class="bx {{ $cfg->estado ? 'bx-pause' : 'bx-play' }} mr-1"></i>
                      {{ $cfg->estado ? 'Desactivar' : 'Activar' }}
                    </button>
                  </form>
                </div>
              </div>

              {{-- Agregar ítem rápido --}}
              <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-600">
                <form method="POST" action="{{ route('admin.config-empresas.items.store', $cfg) }}" class="flex gap-2">
                  @csrf
                  <div class="flex-1">
                    <input type="text" name="item" class="form-control form-control-sm" placeholder="Agregar nuevo ítem..." required>
                  </div>
                  <button type="submit" class="btn btn-sm btn-secondary">
                    <i class="bx bx-plus"></i>
                  </button>
                </form>
              </div>

              {{-- Items listados --}}
              @if($cfg->items->count())
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-600">
                  <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">Ítems configurados:</div>
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    @foreach($cfg->items->sortBy('orden') as $it)
                      <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 rounded p-2">
                        <span class="text-sm text-gray-900 dark:text-white">
                          {{ $it->item }}
                          @if($it->obligatorio)
                            <span class="text-red-600 dark:text-red-400 font-medium">*</span>
                          @endif
                        </span>
                        <form method="POST" action="{{ route('admin.config-empresas.items.destroy', [$cfg, $it]) }}"
                              class="inline" onsubmit="return confirm('¿Eliminar ítem?')">
                          @csrf @method('DELETE')
                          <button type="submit" class="text-red-600 hover:text-red-800 text-sm p-1">
                            <i class="bx bx-trash text-xs"></i>
                          </button>
                        </form>
                      </div>
                    @endforeach
                  </div>
                </div>
              @endif
            </div>
          @empty
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
              <i class="bx bx-file text-4xl mb-2"></i>
              <div>Sin documentos configurados.</div>
              <div class="text-sm">Usa el formulario de arriba para agregar documentos a esta categoría.</div>
            </div>
          @endforelse
        </div>
      </div>
    </div>
  @empty
    <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-6 text-center">
      <div class="flex justify-center mb-4">
        <div class="flex h-16 w-16 items-center justify-center rounded-full {{ $ambito === 'empresa' ? 'bg-purple-100 dark:bg-purple-800' : 'bg-orange-100 dark:bg-orange-800' }}">
          <i class="bx {{ $ambito === 'empresa' ? 'bx-buildings' : 'bx-car' }} {{ $ambito === 'empresa' ? 'text-purple-600 dark:text-purple-300' : 'text-orange-600 dark:text-orange-300' }} text-2xl"></i>
        </div>
      </div>
      <h3 class="text-lg font-medium text-blue-900 dark:text-blue-200 mb-2">No hay categorías de {{ $ambito === 'empresa' ? 'Empresa' : 'Flota' }} asociadas</h3>
      <p class="text-blue-700 dark:text-blue-300 text-sm">
        Usa el formulario de arriba para agregar categorías y comenzar a configurar documentos de {{ $ambito === 'empresa' ? 'empresa' : 'flota' }}.
      </p>
    </div>
  @endforelse
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('catSearchInput');
  const dropdownList = document.getElementById('catDropdownList');
  const chevron = document.getElementById('catChevron');
  const hiddenInput = document.querySelector('.searchable-select input[name="categoria_id"]');
  const noResults = document.getElementById('catNoResults');
  if (!searchInput || !dropdownList) return;
  const items = dropdownList.querySelectorAll('li[data-value]');

  function openDropdown() {
    dropdownList.classList.remove('hidden');
    chevron.classList.add('rotate-180');
  }

  function closeDropdown() {
    dropdownList.classList.add('hidden');
    chevron.classList.remove('rotate-180');
  }

  searchInput.addEventListener('focus', openDropdown);

  searchInput.addEventListener('input', function() {
    const filter = this.value.toLowerCase();
    let visible = 0;
    items.forEach(function(item) {
      const text = item.textContent.toLowerCase();
      if (text.includes(filter)) {
        item.classList.remove('hidden');
        visible++;
      } else {
        item.classList.add('hidden');
      }
    });
    noResults.classList.toggle('hidden', visible > 0);
    openDropdown();
  });

  items.forEach(function(item) {
    item.addEventListener('click', function() {
      hiddenInput.value = this.dataset.value;
      searchInput.value = this.textContent;
      closeDropdown();
    });
  });

  document.addEventListener('click', function(e) {
    if (!e.target.closest('.searchable-select')) {
      closeDropdown();
    }
  });
});
</script>
@endpush
