@extends('layouts.app')

@section('title','Feriados')

@section('content')
<div class="space-y-6">
  {{-- Header --}}
  <div class="sm:flex sm:items-center sm:justify-between">
    <div>
      <h1 class="text-2xl font-bold leading-tight text-gray-900 dark:text-white">Feriados</h1>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
        Calendario de feriados organizados por año para validaciones del sistema.
      </p>
    </div>
    <div class="mt-4 sm:mt-0">
      <a href="{{ route('admin.feriados.create') }}" class="btn btn-primary">
        <i class="bx bx-plus mr-2"></i>
        Nuevo feriado empresarial
      </a>
    </div>
  </div>

  {{-- Acordeones por año --}}
  <div class="space-y-4" id="accordionFeriados">
    @foreach ($feriados->groupBy(fn($f) => \Carbon\Carbon::parse($f->fecha_feriado_date)->year) as $anio => $feriadosAnio)
      <div class="bg-white dark:bg-gray-800 shadow-soft rounded-xl overflow-hidden">
        {{-- Header del acordeón --}}
        <button type="button"
                class="accordion-toggle w-full px-6 py-4 text-left font-medium text-gray-900 dark:text-white bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-inset flex items-center justify-between transition-colors"
                data-target="collapse-{{ $anio }}">
          <span class="text-lg font-semibold">{{ $anio }}</span>
          <i class="bx bx-chevron-down text-xl text-gray-500 dark:text-gray-400 transition-transform duration-200 rotate-icon"></i>
        </button>

        {{-- Contenido del acordeón --}}
        <div id="collapse-{{ $anio }}" class="accordion-content hidden">
          <div class="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
              @for ($mes = 1; $mes <= 12; $mes++)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                  <div class="bg-primary-600 text-white text-center py-2 font-semibold">
                    {{ \Carbon\Carbon::create()->month($mes)->locale('es')->isoFormat('MMMM') }}
                  </div>
                  <div class="p-2">
                    <table class="calendario w-full text-center text-sm"
                           data-anio="{{ $anio }}" data-mes="{{ $mes }}">
                      <thead>
                        <tr>
                          <th class="p-1 text-xs font-medium text-gray-500 dark:text-gray-400">Do</th>
                          <th class="p-1 text-xs font-medium text-gray-500 dark:text-gray-400">Lu</th>
                          <th class="p-1 text-xs font-medium text-gray-500 dark:text-gray-400">Ma</th>
                          <th class="p-1 text-xs font-medium text-gray-500 dark:text-gray-400">Mi</th>
                          <th class="p-1 text-xs font-medium text-gray-500 dark:text-gray-400">Ju</th>
                          <th class="p-1 text-xs font-medium text-gray-500 dark:text-gray-400">Vi</th>
                          <th class="p-1 text-xs font-medium text-gray-500 dark:text-gray-400">Sa</th>
                        </tr>
                      </thead>
                      <tbody><!-- JS genera los días --></tbody>
                    </table>
                  </div>
                </div>
              @endfor
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
  // Funcionalidad del acordeón
  document.querySelectorAll('.accordion-toggle').forEach(button => {
    button.addEventListener('click', function() {
      const targetId = this.dataset.target;
      const content = document.getElementById(targetId);
      const icon = this.querySelector('.rotate-icon');

      if (content.classList.contains('hidden')) {
        // Abrir
        content.classList.remove('hidden');
        icon.classList.add('rotate-180');
      } else {
        // Cerrar
        content.classList.add('hidden');
        icon.classList.remove('rotate-180');
      }
    });
  });

  // Mapa YYYY-MM-DD => descripción
  const feriados = new Map(
    @json(
      $feriados->map(fn($f) => [
        \Carbon\Carbon::parse($f->fecha_feriado_date)->format('Y-m-d'),
        $f->descripcion_feriado
      ])->values()
    )
  );

  // Generar calendarios
  document.querySelectorAll(".calendario").forEach(tabla => {
    const y = Number(tabla.dataset.anio);
    const m = Number(tabla.dataset.mes);
    const first = new Date(y, m - 1, 1);
    const last  = new Date(y, m, 0);

    const tbody = tabla.tBodies[0];
    tbody.innerHTML = "";
    let tr = document.createElement("tr");

    // Celdas vacías antes del primer día (0=Dom ... 6=Sáb)
    for (let i = 0; i < first.getDay(); i++) {
      const td = document.createElement("td");
      td.className = "p-1 h-8";
      tr.appendChild(td);
    }

    for (let d = 1; d <= last.getDate(); d++) {
      const dateStr = `${y}-${String(m).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
      const td = document.createElement("td");
      td.textContent = d;
      td.className = "p-1 h-8 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer transition-colors text-gray-900 dark:text-white";

      const desc = feriados.get(dateStr);
      if (desc) {
        td.classList.remove("hover:bg-gray-100", "dark:hover:bg-gray-600");
        td.classList.add("feriado", "bg-red-100", "dark:bg-orange-500", "text-red-800", "dark:text-black", "font-semibold", "rounded", "border", "border-red-300", "dark:border-orange-400");
        td.title = desc;
      }

      tr.appendChild(td);

      if ((first.getDay() + d) % 7 === 0) {
        tbody.appendChild(tr);
        tr = document.createElement("tr");
      }
    }
    if (tr.children.length) tbody.appendChild(tr);
  });
})();
</script>
@endpush
