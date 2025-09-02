@extends('layouts.app')

@section('title','Configuración')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">Configuración</h3>
  </div>

  <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">

    {{-- Tipo de documento --}}
    <div class="col">
      <a href="{{ route('admin.tipos-documento.index') }}" class="text-decoration-none text-reset">
        <div class="card shadow-sm h-100 border-0 card-hover">
          <div class="card-body d-flex align-items-start gap-3">
            <i class="bx bx-file fs-1"></i>
            <div>
              <h5 class="card-title mb-1">Crear Tipo de Documento</h5>
              <p class="text-muted mb-0">Define nuevos tipos para clasificar documentos.</p>
            </div>
          </div>
        </div>
      </a>
    </div>

    {{-- Documento --}}
    <div class="col">
      <a href="{{ route('admin.documentos.create') }}" class="text-decoration-none text-reset">
        <div class="card shadow-sm h-100 border-0 card-hover">
          <div class="card-body d-flex align-items-start gap-3">
            <i class="bx bx-folder fs-1"></i>
            <div>
              <h5 class="card-title mb-1">Crear Documento</h5>
              <p class="text-muted mb-0">Sube o registra documentos del repositorio.</p>
            </div>
          </div>
        </div>
      </a>
    </div>

    {{-- Categoría --}}
    <div class="col">
      <a href="{{ route('admin.categorias.create') }}" class="text-decoration-none text-reset">
        <div class="card shadow-sm h-100 border-0 card-hover">
          <div class="card-body d-flex align-items-start gap-3">
            <i class="bx bx-category fs-1"></i>
            <div>
              <h5 class="card-title mb-1">Crear Categoría</h5>
              <p class="text-muted mb-0">Organiza los documentos por categorías.</p>
            </div>
          </div>
        </div>
      </a>
    </div>

    {{-- Cargo --}}
    <div class="col">
      <a href="{{ route('admin.cargos.index') }}" class="text-decoration-none text-reset">
        <div class="card shadow-sm h-100 border-0 card-hover">
          <div class="card-body d-flex align-items-start gap-3">
            <i class="bx bx-id-card fs-1"></i>
            <div>
              <h5 class="card-title mb-1">Crear Cargo</h5>
              <p class="text-muted mb-0">Mantén el maestro de cargos actualizado.</p>
            </div>
          </div>
        </div>
      </a>
    </div>

    {{-- Marca Flota --}}
    <div class="col">
      <a href="{{ route('admin.marcas-flota.create') }}" class="text-decoration-none text-reset">
        <div class="card shadow-sm h-100 border-0 card-hover">
          <div class="card-body d-flex align-items-start gap-3">
            <i class="bx bx-car fs-1"></i>
            <div>
              <h5 class="card-title mb-1">Crear Marca de Flota</h5>
              <p class="text-muted mb-0">Maestro de marcas para vehículos/equipos.</p>
            </div>
          </div>
        </div>
      </a>
    </div>

    {{-- Feriados --}}
    <div class="col">
      <a href="{{ route('admin.feriados.create') }}" class="text-decoration-none text-reset">
        <div class="card shadow-sm h-100 border-0 card-hover">
          <div class="card-body d-flex align-items-start gap-3">
            <i class="bx bx-calendar-event fs-1"></i>
            <div>
              <h5 class="card-title mb-1">Crear Feriado</h5>
              <p class="text-muted mb-0">Calendario de feriados para validaciones.</p>
            </div>
          </div>
        </div>
      </a>
    </div>

    {{-- Configurar Empresas --}}
    <div class="col">
        <a href="{{ route('admin.empresas.index') }}" class="text-decoration-none text-reset">
            <div class="card shadow-sm h-100 border-0 card-hover">
                <div class="card-body d-flex align-items-start gap-3">
                    <i class="bx bx-buildings fs-1"></i>
                    <div>
                    <h5 class="card-title mb-1">Configurar Empresas</h5>
                    <p class="text-muted mb-0">Crear y administrar empresas y sus datos.</p>
                    </div>
                </div>
            </div>
        </a>
    </div>



  </div>
</div>

{{-- Estilo hover suave --}}
<style>
  .card-hover { transition: transform .1s ease, box-shadow .1s ease; }
  .card-hover:hover { transform: translateY(-2px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.08)!important; }
</style>
@endsection
