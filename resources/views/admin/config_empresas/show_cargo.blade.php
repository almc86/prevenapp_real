@extends('layouts.app')
@section('title','Configurar Cargo: '.$cargo->nombre.' - '.$config->nombre)

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
        <i class="bx bx-hard-hat mr-2 text-amber-600"></i>
        {{ $cargo->nombre }}
      </h2>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
        Configuración: <strong>{{ $config->nombre }}</strong> &mdash; {{ $empresa->nombre_empresa }}
      </p>
    </div>
    <div class="mt-4 flex md:mt-0 space-x-3">
      <a href="{{ route('admin.config-empresas.show', [$empresa, $config]) }}" class="btn btn-secondary">
        <i class="bx bx-arrow-back mr-2"></i>
        Volver a configuración
      </a>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    {{-- Sidebar de cargos --}}
    <div class="lg:col-span-1">
      <div class="bg-white dark:bg-gray-800 shadow-soft rounded-xl overflow-hidden sticky top-4">
        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
          <h3 class="text-sm font-medium text-gray-900 dark:text-white flex items-center">
            <i class="bx bx-list-ul mr-2"></i>
            Cargos
          </h3>
        </div>
        <div class="p-2 border-b border-gray-200 dark:border-gray-600">
          <div class="relative">
            <i class="bx bx-search absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
            <input type="text" id="buscadorCargosSidebar" placeholder="Filtrar..." class="block w-full pl-8 pr-3 py-1.5 text-xs border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-1 focus:ring-primary-500 focus:border-primary-500" autocomplete="off">
          </div>
        </div>
        <div class="max-h-96 overflow-y-auto" id="cargosSidebarList">
          @foreach($cargos as $c)
            @php
              $count = $countPorCargo->get($c->id, 0);
              $catsCount = $catsPorCargo->get($c->id, 0);
            @endphp
            <a href="{{ route('admin.config-empresas.cargo.show', [$empresa, $config, $c]) }}"
               class="cargo-sidebar-item flex items-center justify-between px-4 py-3 text-sm border-b border-gray-100 dark:border-gray-700 transition-colors
               {{ $c->id === $cargo->id ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 font-medium border-l-4 border-l-primary-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}"
               data-nombre="{{ mb_strtolower($c->nombre) }}">
              <span class="truncate">{{ $c->nombre }}</span>
              @if($count > 0)
                <span class="flex-shrink-0 ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300" title="Documentos configurados">
                  {{ $count }}
                </span>
              @elseif($catsCount > 0)
                <span class="flex-shrink-0 ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300" title="Categorías asignadas (sin documentos)">
                  {{ $catsCount }}
                </span>
              @endif
            </a>
          @endforeach
        </div>
      </div>
    </div>

    {{-- Contenido principal --}}
    @php $docsGrouped = $docsConfig->groupBy('categoria_id'); @endphp
    <div class="lg:col-span-3 space-y-4">
      {{-- Selector para agregar categorías al cargo --}}
      <div class="bg-white dark:bg-gray-800 shadow-soft rounded-xl overflow-hidden">
        <div class="px-5 py-3 flex items-center justify-between flex-wrap gap-3">
          <div class="flex items-center gap-2">
            <i class="bx bx-category text-amber-600"></i>
            <span class="text-sm font-medium text-gray-900 dark:text-white">Categorías del cargo</span>
            <span class="text-xs text-gray-500 dark:text-gray-400">({{ $catsSel->count() }})</span>
          </div>
          @if($catsDisponibles->count() > 0)
            <form method="POST" action="{{ route('admin.config-empresas.cargo-categoria.store', [$empresa, $config, $cargo]) }}" class="flex items-center gap-2">
              @csrf
              <select name="categoria_id" class="form-select form-select-sm" required style="min-width: 200px;">
                <option value="">Agregar categoría...</option>
                @foreach($catsDisponibles as $catDisp)
                  <option value="{{ $catDisp->id }}">{{ $catDisp->nombre }}</option>
                @endforeach
              </select>
              <button type="submit" class="btn btn-sm btn-primary whitespace-nowrap">
                <i class="bx bx-plus mr-1"></i> Agregar
              </button>
            </form>
          @else
            <span class="text-xs text-gray-400">Todas las categorías están asignadas</span>
          @endif
        </div>
      </div>

      @if($catsSel->isEmpty())
        <div class="bg-white dark:bg-gray-800 shadow-soft rounded-xl p-8 text-center">
          <i class="bx bx-category text-4xl text-gray-400 mb-3"></i>
          <p class="text-gray-900 dark:text-white font-medium">Sin categorías asignadas</p>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Usa el selector de arriba para agregar categorías a este cargo.</p>
        </div>
      @else
        @foreach($catsSel as $cat)
          @php $catDocs = $docsGrouped->get($cat->id, collect()); @endphp
          <div class="bg-white dark:bg-gray-800 shadow-soft rounded-xl">
            {{-- Header de categoría --}}
            <div class="px-5 py-3 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors cursor-pointer rounded-t-xl" onclick="toggleCat({{ $cat->id }})">
              <div class="flex items-center gap-3 flex-1 min-w-0">
                <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg {{ $catDocs->count() > 0 ? 'bg-green-100 dark:bg-green-900/40' : 'bg-gray-100 dark:bg-gray-700' }}">
                  <i class="bx bx-category {{ $catDocs->count() > 0 ? 'text-green-600 dark:text-green-400' : 'text-gray-400' }}"></i>
                </div>
                <div class="min-w-0">
                  <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $cat->nombre }}</h3>
                  <p class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $catDocs->count() > 0 ? $catDocs->count() . ' documento(s)' : 'Sin documentos' }}
                  </p>
                </div>
              </div>
              <div class="flex items-center gap-1.5 flex-shrink-0 ml-3">
                @if($catDocs->count() > 0)
                  <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                    {{ $catDocs->count() }}
                  </span>
                @endif
                <form method="POST" action="{{ route('admin.config-empresas.cargo-categoria.destroy', [$empresa, $config, $cargo, $cat]) }}"
                      onsubmit="event.stopPropagation(); return confirm('¿Quitar esta categoría{{ $catDocs->count() > 0 ? " y sus " . $catDocs->count() . " documento(s)" : "" }}?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="p-1.5 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors" title="Quitar categoría" onclick="event.stopPropagation()">
                    <i class="bx bx-trash text-gray-400 hover:text-red-500"></i>
                  </button>
                </form>
                <i class="bx bx-chevron-down text-gray-400 text-xl transition-transform" id="chevron-{{ $cat->id }}"></i>
              </div>
            </div>

            {{-- Contenido colapsable --}}
            <div id="cat-{{ $cat->id }}" class="{{ $catDocs->count() > 0 ? '' : 'hidden' }} border-t border-gray-200 dark:border-gray-600">
              {{-- Documentos existentes --}}
              @if($catDocs->count() > 0)
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                  @foreach($catDocs as $cfg)
                    <div class="px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                      <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                          <i class="bx bx-file text-gray-400 text-lg flex-shrink-0"></i>
                          <div class="min-w-0">
                            <div class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $cfg->documento->nombre }}</div>
                            <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                              <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium {{ $cfg->obligatorio ? 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                                {{ $cfg->obligatorio ? 'Obligatorio' : 'Opcional' }}
                              </span>
                              <span class="text-[10px] text-gray-500 dark:text-gray-400">
                                @switch($cfg->vencimiento_modo)
                                  @case('por_documento') Venc. por documento @break
                                  @case('por_meses') Venc. {{ $cfg->meses_vencimiento }} meses @break
                                  @default Sin vencimiento
                                @endswitch
                              </span>
                            </div>
                          </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                          <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $cfg->estado ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                            {{ $cfg->estado ? 'Activo' : 'Inactivo' }}
                          </span>
                          <form method="POST" action="{{ route('admin.config-empresas.documento.destroy', [$empresa, $config, $cfg->categoria_id, $cfg->id]) }}"
                                onsubmit="return confirm('¿Cambiar estado?')" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                              <i class="bx {{ $cfg->estado ? 'bx-pause text-red-500' : 'bx-play text-green-500' }} text-lg"></i>
                            </button>
                          </form>
                        </div>
                      </div>
                      @if($cfg->items->count())
                        <div class="mt-2 ml-8 flex flex-wrap gap-1.5">
                          @foreach($cfg->items->sortBy('orden') as $it)
                            <div class="inline-flex items-center gap-1 bg-gray-100 dark:bg-gray-700 rounded-full px-2.5 py-0.5 text-xs text-gray-700 dark:text-gray-300">
                              {{ $it->item }}@if($it->obligatorio)<span class="text-red-500">*</span>@endif
                              <form method="POST" action="{{ route('admin.config-empresas.items.destroy', [$cfg, $it]) }}" class="inline" onsubmit="return confirm('¿Eliminar?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-500"><i class="bx bx-x text-sm"></i></button>
                              </form>
                            </div>
                          @endforeach
                        </div>
                      @endif
                      <div class="mt-2 ml-8">
                        <form method="POST" action="{{ route('admin.config-empresas.items.store', $cfg) }}" class="flex gap-1.5 max-w-xs">
                          @csrf
                          <input type="text" name="item" class="form-control form-control-sm text-xs" placeholder="Agregar ítem..." required>
                          <button type="submit" class="btn btn-sm btn-outline-secondary px-2"><i class="bx bx-plus text-xs"></i></button>
                        </form>
                      </div>
                    </div>
                  @endforeach
                </div>
              @endif

              {{-- Formulario agregar documento (siempre visible dentro de la categoría) --}}
              <div class="px-5 py-3 bg-gray-50 dark:bg-gray-700/30 rounded-b-xl {{ $catDocs->count() > 0 ? 'border-t border-gray-200 dark:border-gray-600' : '' }}">
                <form method="POST" action="{{ route('admin.config-empresas.documento-cargo.store', [$empresa, $config, $cargo]) }}" enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" name="categoria_id" value="{{ $cat->id }}">
                  <div class="flex items-end gap-2 flex-wrap">
                    <div class="flex-1 min-w-[160px]">
                      <label class="form-label text-xs mb-1">Documento</label>
                      <x-searchable-select
                        name="documento_id"
                        :options="$documentos->map(fn($d) => ['value' => $d->id, 'label' => $d->nombre])"
                        :exclude="$catDocs->pluck('documento_id')->all()"
                        placeholder="Seleccione..."
                        empty-text="Ya agregaste todos los documentos a esta categoría."
                        size="sm"
                        required
                      />
                    </div>
                    <div class="w-24">
                      <label class="form-label text-xs mb-1">Obligatorio</label>
                      <select name="obligatorio" class="form-select form-select-sm">
                        <option value="0">No</option>
                        <option value="1">Sí</option>
                      </select>
                    </div>
                    <div class="w-32">
                      <label class="form-label text-xs mb-1">Vencimiento</label>
                      <select name="vencimiento_modo" class="form-select form-select-sm">
                        <option value="por_documento">Por documento</option>
                        <option value="por_meses">Por meses</option>
                        <option value="sin_vencimiento">Sin venc.</option>
                      </select>
                    </div>
                    <div class="w-16">
                      <label class="form-label text-xs mb-1">Meses</label>
                      <input type="number" min="1" max="120" name="meses_vencimiento" class="form-control form-control-sm" placeholder="12">
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">
                      <i class="bx bx-plus mr-1"></i> Agregar
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        @endforeach
      @endif
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  function toggleCat(id) {
    const content = document.getElementById('cat-' + id);
    const chevron = document.getElementById('chevron-' + id);
    if (content.classList.contains('hidden')) {
      content.classList.remove('hidden');
      chevron.style.transform = 'rotate(180deg)';
    } else {
      content.classList.add('hidden');
      chevron.style.transform = '';
    }
  }

  document.getElementById('buscadorCargosSidebar')?.addEventListener('input', function() {
    const query = this.value.toLowerCase().trim();
    document.querySelectorAll('.cargo-sidebar-item').forEach(item => {
      const nombre = item.getAttribute('data-nombre');
      item.style.display = (!query || nombre.includes(query)) ? '' : 'none';
    });
  });

</script>
@endpush
