@extends('layouts.app')
@section('title','Configurar: '.$empresa->nombre_empresa)

@section('content')
<div class="container">
  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">Empresa: {{ $empresa->nombre_empresa }}</h3>
    <a href="{{ route('admin.config-empresas.index') }}" class="btn btn-secondary">Cambiar empresa</a>
  </div>

  {{-- Agregar categoría --}}
  <div class="card card-body mb-3">
    <form method="POST" action="{{ route('admin.config-empresas.categoria.store',$empresa) }}">
      @csrf
      <div class="row g-2 align-items-end">
        <div class="col-md-8">
          <label class="form-label">Agregar categoría</label>
          <select name="categoria_id" class="form-select" required>
            <option value="">Seleccione...</option>
            @foreach($categoriasDisponibles as $cat)
              <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-4 d-grid">
          <button class="btn btn-primary">Añadir</button>
        </div>
      </div>
    </form>
  </div>

  {{-- Listado de categorías ya asociadas --}}
  @forelse($categoriasSeleccionadas as $cat)
    <div class="card mb-3">
      <div class="card-header d-flex justify-content-between align-items-center">
        <strong>{{ $cat->nombre }}</strong>
        <form method="POST" action="{{ route('admin.config-empresas.categoria.destroy', [$empresa, $cat->id]) }}"
              onsubmit="return confirm('Quitar categoría de la empresa?')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger">Quitar</button>
        </form>
      </div>

      <div class="card-body">
        {{-- Form para agregar documento a esta categoría --}}
        <form method="POST" action="{{ route('admin.config-empresas.documento.store', [$empresa, $cat->id]) }}" enctype="multipart/form-data" class="mb-3">
          @csrf
          <div class="row g-2">
            <div class="col-md-4">
              <label class="form-label">Documento</label>
              <select name="documento_id" class="form-select" required>
                <option value="">Seleccione...</option>
                @foreach($documentos as $doc)
                  <option value="{{ $doc->id }}">{{ $doc->nombre }} ({{ ucfirst(optional($doc->tipo)->nombre) }})</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-2">
              <label class="form-label">Obligatorio</label>
              <select name="obligatorio" class="form-select">
                <option value="0">No</option>
                <option value="1">Sí</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Vencimiento</label>
              <select name="vencimiento_modo" class="form-select" id="modo-{{ $cat->id }}">
                <option value="por_documento">Por documento</option>
                <option value="por_meses">Por meses</option>
                <option value="sin_vencimiento">Sin vencimiento</option>
              </select>
            </div>
            <div class="col-md-1">
              <label class="form-label">Meses</label>
              <input type="number" min="1" max="120" name="meses_vencimiento" class="form-control" placeholder="12">
            </div>
            <div class="col-md-2">
              <label class="form-label">Plantilla</label>
              <input type="file" name="plantilla" class="form-control" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg,.webp">
            </div>
            <div class="col-12 d-grid">
              <button class="btn btn-success">Agregar documento</button>
            </div>
          </div>
        </form>

        {{-- Tabla de documentos configurados --}}
        @php
          $configs = \App\Models\EmpresaCategoriaDocumento::with(['documento.tipo','items'])
              ->where('empresa_id',$empresa->id)
              ->where('categoria_id',$cat->id)
              ->orderBy('id','desc')
              ->get();
        @endphp

        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead>
              <tr>
                <th>Documento</th>
                <th>Ámbito</th>
                <th>Obligatorio</th>
                <th>Vencimiento</th>
                <th>Plantilla</th>
                <th>Items</th>
                <th>Estado</th>
                <th style="width:210px;">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @forelse($configs as $cfg)
                <tr>
                  <td>{{ $cfg->documento->nombre }}</td>
                  <td>{{ ucfirst(optional($cfg->documento->tipo)->nombre) }}</td>
                  <td>{{ $cfg->obligatorio ? 'Sí' : 'No' }}</td>
                  <td>
                    @switch($cfg->vencimiento_modo)
                      @case('por_documento') Por documento @break
                      @case('por_meses') {{ $cfg->meses_vencimiento }} meses @break
                      @default Sin vencimiento
                    @endswitch
                  </td>
                  <td>
                    @if($cfg->plantilla_path)
                      <a href="{{ asset('storage/'.$cfg->plantilla_path) }}" target="_blank">Ver</a>
                    @else — @endif
                  </td>
                  <td>{{ $cfg->items->count() }}</td>
                  <td>
                    <span class="badge {{ $cfg->estado ? 'bg-success' : 'bg-secondary' }}">{{ $cfg->estado ? 'Activo' : 'Inactivo' }}</span>
                  </td>
                  <td class="d-flex flex-wrap gap-1">
                    {{-- Activar/Desactivar --}}
                    <form method="POST" action="{{ route('admin.config-empresas.documento.destroy', [$empresa, $cat->id, $cfg->id]) }}"
                          onsubmit="return confirm('¿Cambiar estado de este documento?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm {{ $cfg->estado ? 'btn-outline-danger' : 'btn-outline-success' }}">
                        {{ $cfg->estado ? 'Desactivar' : 'Activar' }}
                      </button>
                    </form>

                    {{-- Agregar item rápido --}}
                    <form method="POST" action="{{ route('admin.config-empresas.items.store', $cfg) }}" class="d-flex gap-1">
                      @csrf
                      <input type="text" name="item" class="form-control form-control-sm" placeholder="Nuevo ítem" required>
                      <button class="btn btn-sm btn-secondary">+</button>
                    </form>
                  </td>
                </tr>
                {{-- Items listados --}}
                @if($cfg->items->count())
                <tr>
                  <td colspan="8">
                    <ul class="mb-0 small">
                      @foreach($cfg->items->sortBy('orden') as $it)
                        <li>
                          {{ $it->item }} {!! $it->obligatorio ? '<span class="text-danger">*</span>' : '' !!}
                          <form method="POST" action="{{ route('admin.config-empresas.items.destroy', [$cfg, $it]) }}"
                                class="d-inline" onsubmit="return confirm('¿Eliminar ítem?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-link btn-sm text-danger p-0">Eliminar</button>
                          </form>
                        </li>
                      @endforeach
                    </ul>
                  </td>
                </tr>
                @endif
              @empty
                <tr><td colspan="8" class="text-muted text-center">Sin documentos configurados.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @empty
    <div class="alert alert-info">No hay categorías asociadas todavía.</div>
  @endforelse
</div>
@endsection
