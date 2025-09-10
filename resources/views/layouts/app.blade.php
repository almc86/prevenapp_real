<!DOCTYPE html>
<html lang="es" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Panel de Control')</title>

  <link rel="stylesheet" href="{{ asset('sneat/assets/vendor/css/core.css') }}">
  <link rel="stylesheet" href="{{ asset('sneat/assets/css/demo.css') }}">
  <link rel="stylesheet" href="{{ asset('sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

  {{-- Permitir a las vistas inyectar cosas en <head> --}}
  @stack('head')
</head>
<body>
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      @include('layouts.sidebar')
      <div class="layout-page">
        @include('layouts.topbar')
        <div class="content-wrapper">
          <div class="container-xxl flex-grow-1 container-p-y">
            @yield('content')
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- JS base de Sneat (seguros) --}}
  <script src="{{ asset('sneat/assets/vendor/js/helpers.js') }}"></script>
  <script src="{{ asset('sneat/assets/vendor/js/menu.js') }}"></script>
  <script src="{{ asset('sneat/assets/js/config.js') }}"></script>

  {{-- Airbag: define funciones mínimas si no existen (incluye setCollapsed) --}}
  <script>
    window.Helpers = window.Helpers || {};
    ['initSpeechToText','initPasswordToggle','setAutoUpdate','scrollToActive','isSmallScreen','setCollapsed']
      .forEach(function(fn){
        if (typeof window.Helpers[fn] !== 'function') {
          window.Helpers[fn] = function(){ /* noop */ };
        }
      });
    if (!window.Helpers.isSmallScreen) {
      window.Helpers.isSmallScreen = function(){ return window.innerWidth < 1200; };
    }
    if (!window.Helpers.scrollToActive) {
      window.Helpers.scrollToActive = function(){ /* noop */ };
    }
  </script>

  {{-- ✅ Bootstrap JS: necesario para acordeón/collapse --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  {{-- Si prefieres local, cambia por:
  <script src="{{ asset('sneat/assets/vendor/js/bootstrap.js') }}"></script>
  --}}

  {{-- Cargar main.js SOLO si la vista NO pidió saltarlo --}}
  @hasSection('skip-template-js')
    {{-- Saltamos sneat/assets/js/main.js en esta vista --}}
  @else
    <script src="{{ asset('sneat/assets/js/main.js') }}"></script>
  @endif

  {{-- JS propio de cada vista (donde se hace @push('scripts')) --}}
  @stack('scripts')

  {{-- Tu script del submenu (opcional) --}}
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      document.querySelectorAll('.submenu-toggle').forEach(btn => {
        btn.addEventListener('click', function () {
          const submenu = this.nextElementSibling;
          if (submenu) submenu.classList.toggle('hidden');
          const icon = this.querySelector('.rotate-icon');
          if (icon) icon.classList.toggle('rotate-180');
        });
      });
    });
  </script>
</body>
</html>
