@extends('layouts.app')
@section('title','Configurar Empresas')

@section('content')
<div class="container">
  <h3>Configurar Empresas</h3>

  <div class="card card-body">
    <form method="GET"
        action="{{ route('admin.config-empresas.show', ['empresa' => '__ID__']) }}"
        onsubmit="this.action=this.action.replace('__ID__', document.getElementById('empresa_id').value)">
        <div class="row g-2 align-items-end">
            <div class="col-md-8">
            <label class="form-label">Empresa</label>
            <select id="empresa_id" class="form-select" required>
                <option value="">Seleccione...</option>
                @foreach($empresas as $e)
                <option value="{{ $e->id }}">{{ $e->nombre_empresa }}</option>
                @endforeach
            </select>
            </div>
            <div class="col-md-4 d-grid">
            <button class="btn btn-primary">Configurar</button>
            </div>
        </div>
    </form>
  </div>
</div>
@endsection
