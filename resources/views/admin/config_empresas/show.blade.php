@extends('layouts.app')
@section('title','Configuración: '.$config->nombre.' - '.$empresa->nombre_empresa)

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
        <i class="bx bx-cog mr-2"></i>
        {{ $config->nombre }}
      </h2>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
        <strong>{{ $empresa->nombre_empresa }}</strong> - {{ $config->descripcion ?: 'Configura las categorías, documentos e ítems específicos.' }}
      </p>
    </div>
    <div class="mt-4 flex md:mt-0 space-x-3">
      <a href="{{ route('admin.config-empresas.configs.index', $empresa) }}" class="btn btn-secondary">
        <i class="bx bx-arrow-back mr-2"></i>
        Volver a configuraciones
      </a>
      <a href="{{ route('admin.config-empresas.index') }}" class="btn btn-outline-secondary">
        <i class="bx bx-buildings mr-2"></i>
        Cambiar empresa
      </a>
    </div>
  </div>

  {{-- Botones de configuración por ámbito --}}
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    {{-- Botón Configurar Globales (Empresa) --}}
    <a href="{{ route('admin.config-empresas.globales', [$empresa, $config]) }}" class="block bg-white dark:bg-gray-800 shadow-soft rounded-xl overflow-hidden hover:shadow-md transition-shadow">
      <div class="px-6 py-6 flex items-center justify-between">
        <div class="flex items-center">
          <div class="flex h-12 w-12 items-center justify-center rounded-lg {{ $countEmpresa > 0 ? 'bg-green-100 dark:bg-green-900' : 'bg-red-100 dark:bg-red-900' }}">
            <i class="bx bx-buildings text-2xl {{ $countEmpresa > 0 ? 'text-green-600 dark:text-green-300' : 'text-red-600 dark:text-red-300' }}"></i>
          </div>
          <div class="ml-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Configurar Globales</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">
              {{ $countEmpresa > 0 ? $countEmpresa . ' documento(s) configurado(s)' : 'Sin documentos configurados' }}
            </p>
          </div>
        </div>
        <div class="flex items-center space-x-2">
          <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium {{ $countEmpresa > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
            <i class="bx {{ $countEmpresa > 0 ? 'bx-check-circle' : 'bx-x-circle' }} mr-1"></i>
            {{ $countEmpresa > 0 ? 'Configurado' : 'Pendiente' }}
          </span>
          <i class="bx bx-chevron-right text-2xl text-gray-400"></i>
        </div>
      </div>
    </a>

    {{-- Botón Configurar Flota --}}
    <a href="{{ route('admin.config-empresas.flota', [$empresa, $config]) }}" class="block bg-white dark:bg-gray-800 shadow-soft rounded-xl overflow-hidden hover:shadow-md transition-shadow">
      <div class="px-6 py-6 flex items-center justify-between">
        <div class="flex items-center">
          <div class="flex h-12 w-12 items-center justify-center rounded-lg {{ $countFlota > 0 ? 'bg-green-100 dark:bg-green-900' : 'bg-red-100 dark:bg-red-900' }}">
            <i class="bx bx-car text-2xl {{ $countFlota > 0 ? 'text-green-600 dark:text-green-300' : 'text-red-600 dark:text-red-300' }}"></i>
          </div>
          <div class="ml-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Configurar Flota</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">
              {{ $countFlota > 0 ? $countFlota . ' documento(s) configurado(s)' : 'Sin documentos configurados' }}
            </p>
          </div>
        </div>
        <div class="flex items-center space-x-2">
          <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium {{ $countFlota > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
            <i class="bx {{ $countFlota > 0 ? 'bx-check-circle' : 'bx-x-circle' }} mr-1"></i>
            {{ $countFlota > 0 ? 'Configurado' : 'Pendiente' }}
          </span>
          <i class="bx bx-chevron-right text-2xl text-gray-400"></i>
        </div>
      </div>
    </a>
  </div>

  {{-- Selector de modo de trabajadores --}}
  <div class="bg-white dark:bg-gray-800 shadow-soft rounded-xl overflow-hidden">
    <div class="px-6 py-5">
      <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
          <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
            <i class="bx bx-user-check mr-2 text-primary-600"></i>
            Modo de documentos de trabajadores
          </h3>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Define cómo se asignan los documentos requeridos a los trabajadores
          </p>
        </div>
        <form method="POST" action="{{ route('admin.config-empresas.modo-trabajador.update', [$empresa, $config]) }}" class="flex items-center gap-3">
          @csrf @method('PATCH')
          <div class="flex rounded-lg border border-gray-300 dark:border-gray-600 overflow-hidden">
            <button type="submit" name="modo_trabajador" value="por_categoria"
              class="px-4 py-2.5 text-sm font-medium transition-colors flex items-center gap-2
              {{ $config->modo_trabajador === 'por_categoria' ? 'bg-primary-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600' }}">
              <i class="bx bx-category"></i>
              Por Categoría
            </button>
            <button type="submit" name="modo_trabajador" value="por_cargo"
              class="px-4 py-2.5 text-sm font-medium transition-colors flex items-center gap-2 border-l border-gray-300 dark:border-gray-600
              {{ $config->modo_trabajador === 'por_cargo' ? 'bg-primary-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600' }}">
              <i class="bx bx-hard-hat"></i>
              Por Cargo
            </button>
          </div>
        </form>
      </div>

      @if($config->modo_trabajador === 'por_categoria')
        <div class="mt-3 flex items-center gap-2 text-sm text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 rounded-lg px-3 py-2">
          <i class="bx bx-info-circle"></i>
          <span>Todos los trabajadores comparten los mismos documentos requeridos, agrupados por categoría.</span>
        </div>
      @else
        <div class="mt-3 flex items-center gap-2 text-sm text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/30 rounded-lg px-3 py-2">
          <i class="bx bx-info-circle"></i>
          <span>Cada cargo tiene su propia pila de documentos requeridos. Selecciona un cargo abajo para configurarlo.</span>
        </div>
      @endif
    </div>
  </div>

  @if($config->modo_trabajador === 'por_cargo')
    {{-- Lista de cargos para configurar --}}
    @php
      $countPorCargo = \App\Models\ConfiguracionCategoriaDocumento::where('configuracion_id', $config->id)
          ->whereNotNull('cargo_id')
          ->where('estado', 1)
          ->select('cargo_id')
          ->selectRaw('COUNT(*) as total')
          ->groupBy('cargo_id')
          ->pluck('total', 'cargo_id');
      $cargos = \App\Models\Cargo::orderBy('nombre')->get()
          ->sortBy([fn($a, $b) => ($countPorCargo->get($b->id, 0) <=> $countPorCargo->get($a->id, 0)) ?: strcmp($a->nombre, $b->nombre)])
          ->values();
    @endphp
    <div class="bg-white dark:bg-gray-800 shadow-soft rounded-xl overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
          <i class="bx bx-hard-hat mr-2 text-amber-600"></i>
          Configurar Documentos por Cargo
        </h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Selecciona un cargo para asignar los documentos que requiere</p>
      </div>
      <div class="p-6">
        @if($cargos->isEmpty())
          <div class="text-center py-8 text-gray-500 dark:text-gray-400">
            <i class="bx bx-hard-hat text-4xl mb-2"></i>
            <p>No hay cargos registrados. Crea cargos primero en el módulo de cargos.</p>
          </div>
        @else
          {{-- Buscador de cargos --}}
          <div class="mb-4">
            <div class="relative">
              <i class="bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg pointer-events-none"></i>
              <input type="text" id="buscadorCargos" placeholder="Buscar cargo..." class="block w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" autocomplete="off">
            </div>
            <p id="cargosCount" class="mt-2 text-xs text-gray-500 dark:text-gray-400">Mostrando {{ $cargos->count() }} cargos</p>
          </div>

          @php
            if (!function_exists('getCargoIcon')) {
              function getCargoIcon($nombre) {
                $n = mb_strtolower($nombre);
                $map = [
                  ['keys' => ['albañil','alarife','obra','construcción','hormigon','excavador','demolición'], 'icon' => 'bx-building-house', 'bg' => '#fef3c7', 'fg' => '#d97706'],
                  ['keys' => ['electri','eléctri','voltaje','energía'], 'icon' => 'bx-bolt-circle', 'bg' => '#fef9c3', 'fg' => '#ca8a04'],
                  ['keys' => ['soldad','calderero','metal','fierr','herrer'], 'icon' => 'bxs-flame', 'bg' => '#ffedd5', 'fg' => '#ea580c'],
                  ['keys' => ['mecánic','mecanico','manten','mantenc','técnic','tecnico','lubricad','gasfiter'], 'icon' => 'bx-wrench', 'bg' => '#dbeafe', 'fg' => '#2563eb'],
                  ['keys' => ['conduct','chofer','operador','grúa','grua','camion','transporte','tracto','rigger','maquinar'], 'icon' => 'bx-car', 'bg' => '#e0e7ff', 'fg' => '#4f46e5'],
                  ['keys' => ['administra','secretar','recepcion','oficin','contab','finanz','tesor','adquisic'], 'icon' => 'bx-briefcase', 'bg' => '#f1f5f9', 'fg' => '#475569'],
                  ['keys' => ['preven','seguridad','guardia','vigilant','bombero','emergenc','rescate','sso','hse'], 'icon' => 'bx-shield-quarter', 'bg' => '#dcfce7', 'fg' => '#16a34a'],
                  ['keys' => ['enfermer','médic','medico','paramed','salud','kinesio','nutric'], 'icon' => 'bx-plus-medical', 'bg' => '#fee2e2', 'fg' => '#dc2626'],
                  ['keys' => ['aseo','aseador','limpiez','junior','jardin'], 'icon' => 'bx-spray-can', 'bg' => '#ccfbf1', 'fg' => '#0d9488'],
                  ['keys' => ['cocin','chef','aliment','casino','garzón','garzon','manipulad'], 'icon' => 'bxs-dish', 'bg' => '#ffe4e6', 'fg' => '#e11d48'],
                  ['keys' => ['analista','ingenier','consultor','asesor','especialista','profesional','coordinad','planific'], 'icon' => 'bx-line-chart', 'bg' => '#ede9fe', 'fg' => '#7c3aed'],
                  ['keys' => ['supervis','jefe','gerente','director','encargad','capata','maestr','lider','líder','subgerente'], 'icon' => 'bx-user-check', 'bg' => '#f3e8ff', 'fg' => '#9333ea'],
                  ['keys' => ['bodeg','almacen','logíst','logist','despach','estibad','carga','pañol'], 'icon' => 'bx-package', 'bg' => '#cffafe', 'fg' => '#0891b2'],
                  ['keys' => ['pintor','pintura','revestim','estucador'], 'icon' => 'bx-paint-roll', 'bg' => '#fae8ff', 'fg' => '#c026d3'],
                  ['keys' => ['carpint','muebl','ebanist'], 'icon' => 'bx-cuboid', 'bg' => '#fef3c7', 'fg' => '#b45309'],
                  ['keys' => ['call center','comunic','telefon','contact'], 'icon' => 'bx-phone-call', 'bg' => '#e0f2fe', 'fg' => '#0284c7'],
                  ['keys' => ['informátic','informatic','sistema','programad','desarroll','software','soporte','redes'], 'icon' => 'bx-code-alt', 'bg' => '#d1fae5', 'fg' => '#059669'],
                  ['keys' => ['asistente','auxiliar','ayudante','aprendiz','practicante'], 'icon' => 'bx-user', 'bg' => '#f3f4f6', 'fg' => '#6b7280'],
                  ['keys' => ['topógraf','topograf','agrimensor','geodesta'], 'icon' => 'bx-target-lock', 'bg' => '#ecfccb', 'fg' => '#65a30d'],
                  ['keys' => ['visita','apr visita'], 'icon' => 'bx-walk', 'bg' => '#e0f2fe', 'fg' => '#0ea5e9'],
                  ['keys' => ['estacion','porter','conserje','control acceso'], 'icon' => 'bx-door-open', 'bg' => '#f5f5f4', 'fg' => '#78716c'],
                  ['keys' => ['anfitrión','anfitrion'], 'icon' => 'bx-home-smile', 'bg' => '#fce7f3', 'fg' => '#db2777'],
                ];
                foreach ($map as $m) {
                  foreach ($m['keys'] as $key) {
                    if (str_contains($n, $key)) return $m;
                  }
                }
                return ['icon' => 'bx-hard-hat', 'bg' => '#f3f4f6', 'fg' => '#9ca3af'];
              }
            }
          @endphp

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3" id="cargosGrid">
            @foreach($cargos as $cargo)
              @php
                $count = $countPorCargo->get($cargo->id, 0);
                $ci = getCargoIcon($cargo->nombre);
              @endphp
              <a href="{{ route('admin.config-empresas.cargo.show', [$empresa, $config, $cargo]) }}"
                 class="cargo-card block border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-md hover:border-primary-300 dark:hover:border-primary-600 transition-all group"
                 data-nombre="{{ mb_strtolower($cargo->nombre) }}">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg" style="background-color: {{ $count > 0 ? $ci['bg'] : '' }}; {{ $count <= 0 ? 'background-color: rgba(107,114,128,0.1)' : '' }}">
                      <i class="bx {{ $ci['icon'] }} text-lg" style="color: {{ $count > 0 ? $ci['fg'] : '#9ca3af' }}"></i>
                    </div>
                    <div>
                      <p class="font-medium text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ $cargo->nombre }}</p>
                      <p class="text-xs {{ $count > 0 ? 'text-green-600 dark:text-green-400 font-medium' : 'text-gray-500 dark:text-gray-400' }}">
                        {{ $count > 0 ? $count . ' documento(s) configurado(s)' : 'Sin configurar' }}
                      </p>
                    </div>
                  </div>
                  <i class="bx bx-chevron-right text-gray-400 group-hover:text-primary-600 transition-colors"></i>
                </div>
              </a>
            @endforeach
          </div>
        @endif
      </div>

      @push('scripts')
      <script>
        document.getElementById('buscadorCargos')?.addEventListener('input', function() {
          const query = this.value.toLowerCase().trim();
          const cards = document.querySelectorAll('.cargo-card');
          let visible = 0;
          cards.forEach(card => {
            const nombre = card.getAttribute('data-nombre');
            const match = !query || nombre.includes(query);
            card.style.display = match ? '' : 'none';
            if (match) visible++;
          });
          document.getElementById('cargosCount').textContent = visible === cards.length
            ? `Mostrando ${cards.length} cargos`
            : `Mostrando ${visible} de ${cards.length} cargos`;
        });
      </script>
      @endpush
    </div>
  @endif

  @if($config->modo_trabajador === 'por_categoria')
  {{-- Agregar categoría --}}
  <div class="bg-white dark:bg-gray-800 shadow-soft rounded-xl overflow-visible">
    <div class="px-6 py-6">
      <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-6">
        <i class="bx bx-plus-circle mr-2"></i>
        Agregar Categoría
      </h3>
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
            <p class="form-help">Selecciona una categoría para asociar a esta empresa</p>
          </div>
          <div class="sm:col-span-1">
            <button type="submit" class="btn btn-primary w-full">
              <i class="bx bx-plus mr-2"></i>
              Añadir
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  {{-- Listado de categorías ya asociadas --}}
  @forelse($catsSel as $cat)
    @php
      // Documentos ya configurados en esta categoría (para excluirlos del select)
      $docs_config = \App\Models\ConfiguracionCategoriaDocumento::with(['documento.tipo','items'])
          ->where('configuracion_id',$config->id)
          ->where('categoria_id',$cat->id)
          ->orderBy('id','desc')
          ->get();
      $usedDocIds = $docs_config->pluck('documento_id')->all();
      $docsActivos = $docs_config->where('estado', 1)->count();
      $docsObligatorios = $docs_config->where('obligatorio', 1)->count();
    @endphp
    <div class="bg-white dark:bg-gray-800 shadow-soft rounded-xl overflow-hidden category-card" data-category-id="{{ $cat->id }}">
      {{-- Header de la categoría (clickable para expandir/colapsar) --}}
      <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 flex items-center justify-between gap-3">
        <button type="button" class="category-toggle flex items-center flex-1 text-left min-w-0" aria-expanded="false">
          <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-100 dark:bg-primary-800 flex-shrink-0">
            <i class="bx bx-category text-primary-600 dark:text-primary-300"></i>
          </div>
          <div class="ml-3 flex-1 min-w-0">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white truncate">{{ $cat->nombre }}</h3>
            <div class="flex items-center flex-wrap gap-2 mt-1">
              <span class="inline-flex items-center text-xs px-2 py-0.5 rounded-full {{ $docs_config->count() > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">
                <i class="bx bx-file mr-1"></i>
                {{ $docs_config->count() }} documento(s)
              </span>
              @if($docsObligatorios > 0)
                <span class="inline-flex items-center text-xs px-2 py-0.5 rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                  <i class="bx bx-error-circle mr-1"></i>
                  {{ $docsObligatorios }} obligatorio(s)
                </span>
              @endif
              @if($docs_config->count() > 0 && $docsActivos < $docs_config->count())
                <span class="inline-flex items-center text-xs px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-300">
                  <i class="bx bx-pause-circle mr-1"></i>
                  {{ $docs_config->count() - $docsActivos }} inactivo(s)
                </span>
              @endif
            </div>
          </div>
          <i class="bx bx-chevron-down text-2xl text-gray-400 transition-transform duration-200 category-chevron flex-shrink-0 ml-2"></i>
        </button>
        <form method="POST" action="{{ route('admin.config-empresas.categoria.destroy', [$empresa, $config, $cat->id]) }}"
              onsubmit="return confirm('¿Quitar categoría de la empresa?')" class="flex-shrink-0">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-sm btn-outline-danger">
            <i class="bx bx-trash mr-2"></i>
            Quitar
          </button>
        </form>
      </div>

      <div class="category-content hidden border-t border-gray-200 dark:border-gray-600 p-6">
        {{-- Form para agregar documento a esta categoría --}}
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
          <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">
            <i class="bx bx-file-plus mr-2"></i>
            Agregar Documento
          </h4>
          <form method="POST" action="{{ route('admin.config-empresas.documento.store', [$empresa, $config, $cat->id]) }}" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-6">
              <div class="lg:col-span-2">
                <label class="form-label">Documento *</label>
                <x-searchable-select
                  name="documento_id"
                  :options="$documentos->map(fn($d) => [
                      'value' => $d->id,
                      'label' => $d->nombre,
                      'sublabel' => ucfirst(optional($d->tipo)->nombre),
                  ])"
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
                <select name="vencimiento_modo" class="form-select" id="modo-{{ $cat->id }}">
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
        </div>

        {{-- Documentos configurados (la query se hizo arriba como $docs_config) --}}

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
                  {{-- Activar/Desactivar --}}
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
        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-800">
          <i class="bx bx-info-circle text-blue-600 dark:text-blue-300 text-2xl"></i>
        </div>
      </div>
      <h3 class="text-lg font-medium text-blue-900 dark:text-blue-200 mb-2">No hay categorías asociadas</h3>
      <p class="text-blue-700 dark:text-blue-300 text-sm">
        Usa el formulario de arriba para agregar categorías a esta empresa y comenzar a configurar sus documentos.
      </p>
    </div>
  @endforelse
  @endif {{-- fin modo por_categoria --}}
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

  // Toggle colapsable de cards de categorías
  const storageKey = 'configEmpresas:openCategories:{{ $config->id }}';
  let openIds = [];
  try { openIds = JSON.parse(localStorage.getItem(storageKey)) || []; } catch (e) { openIds = []; }

  function persistOpenIds() {
    try { localStorage.setItem(storageKey, JSON.stringify(openIds)); } catch (e) {}
  }

  document.querySelectorAll('.category-card').forEach(function(card) {
    const id = card.getAttribute('data-category-id');
    const toggle = card.querySelector('.category-toggle');
    const content = card.querySelector('.category-content');
    const chevron = card.querySelector('.category-chevron');
    if (!toggle || !content) return;

    function setOpen(open) {
      content.classList.toggle('hidden', !open);
      chevron?.classList.toggle('rotate-180', open);
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    if (openIds.includes(id)) setOpen(true);

    toggle.addEventListener('click', function() {
      const willOpen = content.classList.contains('hidden');
      setOpen(willOpen);
      openIds = openIds.filter(x => x !== id);
      if (willOpen) openIds.push(id);
      persistOpenIds();
    });
  });
});
</script>
@endpush
