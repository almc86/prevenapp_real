{{--
  Combobox con buscador (Alpine.js).

  Uso:
    <x-searchable-select
        name="documento_id"
        :options="$documentos->map(fn($d) => ['value' => $d->id, 'label' => $d->nombre, 'sublabel' => optional($d->tipo)->nombre])"
        :exclude="$usedIds"
        placeholder="Buscar documento..."
        empty-text="Todos los documentos ya están agregados a esta categoría."
        required
    />

  Props:
    - name        (string) name del input que se envía
    - options     (Collection|array) cada item: ['value' => mixed, 'label' => string, 'sublabel' => ?string]
    - exclude     (array) ids a excluir del listado
    - selected    (mixed) valor pre-seleccionado
    - placeholder (string) placeholder del input search
    - emptyText   (string) mensaje cuando no hay opciones disponibles (post-filtrado)
    - required    (bool)
    - size        ('sm'|'md') tamaño del control
--}}
@props([
  'name' => 'value',
  'options' => [],
  'exclude' => [],
  'selected' => null,
  'placeholder' => 'Buscar...',
  'emptyText' => 'No hay opciones disponibles.',
  'required' => false,
  'size' => 'md',
])

@php
  $excludeIds = collect($exclude)->map(fn($v) => (string) $v)->all();
  $items = collect($options)
      ->map(fn($o) => is_array($o) ? $o : (array) $o)
      ->reject(fn($o) => in_array((string) ($o['value'] ?? ''), $excludeIds, true))
      ->values()
      ->all();
  $selectedItem = collect($items)->first(fn($o) => (string) ($o['value'] ?? '') === (string) $selected);
  $compId = 'ssel_' . uniqid();
  $sizeClasses = $size === 'sm' ? 'text-sm py-1.5' : 'py-2';
@endphp

@if(count($items) === 0)
  <div class="text-sm text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-md px-3 py-2">
    <i class="bx bx-info-circle mr-1"></i>
    {{ $emptyText }}
  </div>
  {{-- Aún así renderizamos un input hidden vacío para que el form no se rompa --}}
  <input type="hidden" name="{{ $name }}" value="">
@else
  <div
    x-data='{
      open: false,
      query: "",
      selected: @json($selectedItem ?: null),
      items: @json($items),
      get filtered() {
        const q = this.query.trim().toLowerCase();
        if (!q) return this.items;
        return this.items.filter(o => {
          const hay = (o.label || "").toLowerCase() + " " + (o.sublabel || "").toLowerCase();
          return hay.includes(q);
        });
      },
      select(item) {
        this.selected = item;
        this.open = false;
        this.query = "";
        this.$nextTick(() => this.$refs.hidden.dispatchEvent(new Event("change", { bubbles: true })));
      },
      clear() {
        this.selected = null;
        this.$nextTick(() => this.$refs.hidden.dispatchEvent(new Event("change", { bubbles: true })));
      },
    }'
    @click.outside="open = false"
    @keydown.escape.window="open = false"
    class="relative"
  >
    {{-- Input hidden con el valor real que se envía --}}
    <input
      type="hidden"
      name="{{ $name }}"
      x-ref="hidden"
      :value="selected ? selected.value : ''"
      @if($required) required @endif
    >

    {{-- Trigger / display --}}
    <button
      type="button"
      @click="open = !open; if(open) $nextTick(() => $refs.search.focus())"
      class="form-select w-full text-left flex items-center justify-between {{ $sizeClasses }}"
    >
      <span x-show="selected" class="truncate">
        <span x-text="selected ? selected.label : ''"></span>
        <template x-if="selected && selected.sublabel">
          <span class="text-gray-500 text-xs ml-1" x-text="'(' + selected.sublabel + ')'"></span>
        </template>
      </span>
      <span x-show="!selected" class="text-gray-400">{{ $placeholder }}</span>
      <i class="bx bx-chevron-down text-gray-400 ml-2 flex-shrink-0"></i>
    </button>

    {{-- Dropdown --}}
    <div
      x-show="open"
      x-transition.opacity.duration.100ms
      x-cloak
      class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg overflow-hidden"
    >
      {{-- Search input --}}
      <div class="p-2 border-b border-gray-200 dark:border-gray-700">
        <div class="relative">
          <i class="bx bx-search absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
          <input
            type="text"
            x-ref="search"
            x-model="query"
            placeholder="Buscar..."
            class="form-control pl-8 py-1.5 text-sm w-full"
            @keydown.enter.prevent="if (filtered.length > 0) select(filtered[0])"
          >
        </div>
      </div>

      {{-- Options list --}}
      <ul class="max-h-60 overflow-y-auto" role="listbox">
        <template x-for="opt in filtered" :key="opt.value">
          <li
            @click="select(opt)"
            :class="selected && selected.value === opt.value ? 'bg-primary-50 dark:bg-primary-900/30' : ''"
            class="px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer flex items-center justify-between"
            role="option"
          >
            <div class="flex flex-col min-w-0">
              <span class="text-gray-900 dark:text-white truncate" x-text="opt.label"></span>
              <template x-if="opt.sublabel">
                <span class="text-xs text-gray-500" x-text="opt.sublabel"></span>
              </template>
            </div>
            <i
              x-show="selected && selected.value === opt.value"
              class="bx bx-check text-primary-600 dark:text-primary-400 ml-2 flex-shrink-0"
            ></i>
          </li>
        </template>

        <li x-show="filtered.length === 0" class="px-3 py-3 text-sm text-gray-500 text-center">
          Sin resultados para "<span x-text="query"></span>"
        </li>
      </ul>

      {{-- Footer con contador y opción de limpiar --}}
      <div class="px-3 py-2 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30 flex items-center justify-between text-xs text-gray-500">
        <span><span x-text="filtered.length"></span> de <span x-text="items.length"></span></span>
        <button
          type="button"
          x-show="selected"
          @click="clear()"
          class="text-red-600 hover:underline"
        >
          Limpiar
        </button>
      </div>
    </div>
  </div>
@endif
