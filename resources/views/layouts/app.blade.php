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
    <script src="{{ asset('sneat/assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('sneat/assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('sneat/assets/js/config.js') }}"></script>
    <script>
        // Shim para evitar errores si Sneat llama funciones no definidas
        window.Helpers = window.Helpers || {};
        ['initSpeechToText','initPasswordToggle','setAutoUpdate','scrollToActive','isSmallScreen'].forEach(function(fn){
        if (typeof window.Helpers[fn] !== 'function') {
            window.Helpers[fn] = function(){ /* noop */ };
        }
        });

        // Sugeridos mínimos por si main.js los usa
        if (!window.Helpers.isSmallScreen) {
        window.Helpers.isSmallScreen = function(){ return window.innerWidth < 1200; };
        }
        if (!window.Helpers.scrollToActive) {
        window.Helpers.scrollToActive = function(animate){ /* noop */ };
        }
        if (!window.Helpers.initPasswordToggle) {
        window.Helpers.initPasswordToggle = function(){ /* noop */ };
        }
        </script>
    <script src="{{ asset('sneat/assets/js/main.js') }}"></script>

    <script>
  document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.submenu-toggle').forEach(btn => {
      btn.addEventListener('click', function () {
        const submenu = this.nextElementSibling;
        submenu.classList.toggle('hidden');

        // Icono animado
        const icon = this.querySelector('.rotate-icon');
        if (icon) icon.classList.toggle('rotate-180');
      });
    });
  });
</script>
</body>
</html>
