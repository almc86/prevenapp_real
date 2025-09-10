@extends('layouts.app')

@section('title','Feriados')

{{-- Esta vista NO necesita el JS del template --}}
@section('skip-template-js', true)

@push('head')
<script>
  // Fallback por si algún layout antiguo cargara main.js antes:
  window.Helpers = window.Helpers || {};
  window.Helpers.setCollapsed = window.Helpers.setCollapsed || function(){};
</script>
@endpush

@section('content')
<div class="container">
  <!-- <h2 class="mb-4">Feriados</h2> -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Feriados</h2>
    <a href="{{ route('admin.feriados.create') }}" class="btn btn-primary">
      <i class="bx bx-plus"></i> Nuevo feriado empresarial
    </a>
  </div>

  <div class="accordion" id="accordionFeriados">
    @foreach ($feriados->groupBy(fn($f) => \Carbon\Carbon::parse($f->fecha_feriado_date)->year) as $anio => $feriadosAnio)
      <div class="accordion-item">
        <h2 class="accordion-header" id="heading-{{ $anio }}">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                  data-bs-target="#collapse-{{ $anio }}" aria-expanded="false"
                  aria-controls="collapse-{{ $anio }}">
            {{ $anio }}
          </button>
        </h2>
        <div id="collapse-{{ $anio }}" class="accordion-collapse collapse"
             aria-labelledby="heading-{{ $anio }}" data-bs-parent="#accordionFeriados">
          <div class="accordion-body">
            <div class="row">
              @for ($mes = 1; $mes <= 12; $mes++)
                <div class="col-md-3 mb-3">
                  <div class="card">
                    <div class="card-header text-center fw-bold">
                      {{ \Carbon\Carbon::create()->month($mes)->locale('es')->isoFormat('MMMM') }}
                    </div>
                    <div class="card-body p-2">
                      <table class="table table-sm text-center mb-0 calendario"
                             data-anio="{{ $anio }}" data-mes="{{ $mes }}">
                        <thead>
                          <tr>
                            <th>Do</th><th>Lu</th><th>Ma</th><th>Mi</th>
                            <th>Ju</th><th>Vi</th><th>Sa</th>
                          </tr>
                        </thead>
                        <tbody><!-- JS genera los días --></tbody>
                      </table>
                    </div>
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
  // Mapa YYYY-MM-DD => descripción
  const feriados = new Map(
    @json(
      $feriados->map(fn($f) => [
        \Carbon\Carbon::parse($f->fecha_feriado_date)->format('Y-m-d'),
        $f->descripcion_feriado
      ])->values()
    )
  );

  document.querySelectorAll(".calendario").forEach(tabla => {
    const y = Number(tabla.dataset.anio);
    const m = Number(tabla.dataset.mes);
    const first = new Date(y, m - 1, 1);
    const last  = new Date(y, m, 0);

    const tbody = tabla.tBodies[0];
    tbody.innerHTML = "";
    let tr = document.createElement("tr");

    // Celdas vacías antes del primer día (0=Dom ... 6=Sáb)
    for (let i = 0; i < first.getDay(); i++) tr.appendChild(document.createElement("td"));

    for (let d = 1; d <= last.getDate(); d++) {
      const dateStr = `${y}-${String(m).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
      const td = document.createElement("td");
      td.textContent = d;

      const desc = feriados.get(dateStr);
      if (desc) {
        td.classList.add("feriado");
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

<style>
  .card-header{ background:#f6f8fb; }
  .calendario{ table-layout:fixed; width:100%; }
  .calendario th, .calendario td{
    padding:.35rem; font-size:.85rem; height:34px; vertical-align:middle;
  }
  .calendario td.feriado{
    background:#ffe6e6; color:#a10000; font-weight:600; border-radius:4px;
  }
</style>
@endpush
