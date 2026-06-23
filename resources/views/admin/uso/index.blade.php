@extends('layouts.app')

@section('title', 'Uso y almacenamiento')

@php
    if (!function_exists('formatoBytes')) {
        function formatoBytes($bytes) {
            if ($bytes >= 1024 ** 3) return number_format($bytes / (1024 ** 3), 2, ',', '.') . ' GB';
            if ($bytes >= 1024 ** 2) return number_format($bytes / (1024 ** 2), 1, ',', '.') . ' MB';
            if ($bytes >= 1024) return number_format($bytes / 1024, 0, ',', '.') . ' KB';
            return $bytes . ' B';
        }
    }
    $barColor = $pct >= 90 ? 'bg-danger' : ($pct >= 80 ? 'bg-warning' : 'bg-success');
@endphp

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 mb-1 fw-bold">Uso y almacenamiento</h1>
            <p class="text-muted mb-0">Cuánto consumes de tu plan y el estado de tu suscripción.</p>
        </div>
    </div>

    @if (!$suscripcion)
        <div class="alert alert-warning">
            <i class='bx bx-error-circle'></i> Esta cuenta todavía no tiene un plan asignado.
        </div>
    @else
        <div class="row g-4">
            {{-- Almacenamiento --}}
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h2 class="h5 fw-semibold mb-0"><i class='bx bx-hdd me-1'></i> Almacenamiento</h2>
                            <span class="badge {{ $barColor }} rounded-pill">{{ $pct }}%</span>
                        </div>

                        <div class="progress mb-2" style="height: 14px;">
                            <div class="progress-bar {{ $barColor }}" role="progressbar"
                                 style="width: {{ $pct }}%;" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                        <p class="text-muted mb-0">
                            <strong>{{ formatoBytes($usado) }}</strong> usados de
                            <strong>{{ $suscripcion->storage_gb }} GB</strong> de tu plan.
                        </p>

                        @if ($pct >= 80)
                            <div class="alert alert-warning mt-3 mb-0">
                                <i class='bx bx-info-circle'></i>
                                Estás cerca del tope de tu plan. Cuando lo superes, no podrás subir más documentos hasta liberar espacio o cambiar de plan.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Plan / suscripción --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-semibold mb-3"><i class='bx bx-package me-1'></i> Tu plan</h2>

                        <div class="mb-3">
                            <div class="text-muted small">Plan actual</div>
                            <div class="fs-5 fw-bold">{{ $suscripcion->plan_nombre }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="text-muted small">Estado</div>
                            @php
                                $estadoMap = [
                                    'trialing' => ['Prueba', 'bg-info'],
                                    'activa' => ['Activa', 'bg-success'],
                                    'morosa' => ['Pago pendiente', 'bg-warning'],
                                    'solo_lectura' => ['Solo lectura', 'bg-danger'],
                                    'cancelada' => ['Cancelada', 'bg-secondary'],
                                ];
                                [$txt, $cls] = $estadoMap[$suscripcion->estado] ?? [$suscripcion->estado, 'bg-secondary'];
                            @endphp
                            <span class="badge {{ $cls }}">{{ $txt }}</span>
                        </div>

                        @if (!is_null($diasTrial))
                            <div class="mb-3">
                                <div class="text-muted small">Prueba gratis</div>
                                <div class="fw-semibold">
                                    @if ($diasTrial > 0)
                                        Te {{ $diasTrial === 1 ? 'queda 1 día' : "quedan $diasTrial días" }}
                                    @else
                                        Vence hoy
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="mb-0">
                            <div class="text-muted small">Límites del plan</div>
                            <div class="small">
                                {{ $suscripcion->storage_gb }} GB ·
                                {{ $suscripcion->max_trabajadores ? $suscripcion->max_trabajadores.' trabajadores' : 'Trabajadores ilimitados' }} ·
                                {{ $suscripcion->max_carpetas ? $suscripcion->max_carpetas.' carpetas' : 'Carpetas ilimitadas' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
