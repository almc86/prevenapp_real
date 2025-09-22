@extends('layouts.app')

@section('title','Nuevo feriado empresarial')

@section('content')
<div class="space-y-6">
  {{-- Header --}}
  <div class="md:flex md:items-center md:justify-between">
    <div class="min-w-0 flex-1">
      <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
        Nuevo Feriado Empresarial
      </h2>
      <p class="mt-1 text-sm text-gray-500">
        Agrega un nuevo feriado específico de la empresa al calendario.
      </p>
    </div>
    <div class="mt-4 flex md:mt-0">
      <a href="{{ route('admin.feriados.index') }}" class="btn btn-secondary">
        <i class="bx bx-arrow-back mr-2"></i>
        Volver
      </a>
    </div>
  </div>

  {{-- Formulario --}}
  <div class="bg-white shadow-soft rounded-xl overflow-hidden max-w-2xl mx-auto">
    <div class="px-6 py-6">
      <form method="POST" action="{{ route('admin.feriados.store') }}" novalidate>
        @csrf
        <input type="hidden" name="es_empresarial" value="1">

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
          <div>
            <label for="fecha_feriado_date" class="form-label">Fecha *</label>
            <input type="date"
                   id="fecha_feriado_date"
                   name="fecha_feriado_date"
                   class="form-control @error('fecha_feriado_date') border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                   value="{{ old('fecha_feriado_date') }}"
                   required>
            @error('fecha_feriado_date')
              <div class="form-error">{{ $message }}</div>
            @enderror
          </div>

          <div class="sm:col-span-2">
            <label for="descripcion_feriado" class="form-label">Descripción *</label>
            <input type="text"
                   id="descripcion_feriado"
                   name="descripcion_feriado"
                   class="form-control @error('descripcion_feriado') border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                   value="{{ old('descripcion_feriado') }}"
                   placeholder="Ej: Cierre corporativo por inventario"
                   maxlength="255"
                   required>
            @error('descripcion_feriado')
              <div class="form-error">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="mt-6 flex items-center justify-end space-x-3">
          <a href="{{ route('admin.feriados.index') }}" class="btn btn-secondary">
            Cancelar
          </a>
          <button type="submit" class="btn btn-primary">
            <i class="bx bx-save mr-2"></i>
            Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
