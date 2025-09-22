<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Panel de Control') - {{ config('app.name', 'PrevenApp') }}</title>

  {{-- Fonts --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  {{-- Icons --}}
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

  {{-- Scripts --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  {{-- Permitir a las vistas inyectar cosas en <head> --}}
  @stack('head')
</head>
<body class="h-full bg-gray-50">
  <div class="min-h-full">
    {{-- Sidebar para desktop --}}
    @include('layouts.sidebar')

    {{-- Layout principal --}}
    <div class="lg:pl-72">
      {{-- Topbar --}}
      @include('layouts.topbar')

      {{-- Contenido principal --}}
      <main class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          @yield('content')
        </div>
      </main>
    </div>
  </div>

  {{-- Scripts de la aplicación --}}
  <script>
    // Mobile sidebar toggle
    document.addEventListener('DOMContentLoaded', function() {
      const mobileMenuButton = document.getElementById('mobile-menu-button');
      const sidebar = document.getElementById('sidebar');
      const overlay = document.getElementById('sidebar-overlay');

      if (mobileMenuButton && sidebar && overlay) {
        function toggleSidebar() {
          sidebar.classList.toggle('-translate-x-full');
          overlay.classList.toggle('hidden');
        }

        mobileMenuButton.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);
      }

      // Submenu toggles
      document.querySelectorAll('.submenu-toggle').forEach(btn => {
        btn.addEventListener('click', function () {
          const submenu = this.nextElementSibling;
          if (submenu) {
            submenu.classList.toggle('hidden');
            const icon = this.querySelector('.rotate-icon');
            if (icon) icon.classList.toggle('rotate-180');
          }
        });
      });

      // Auto-close mobile sidebar on larger screens
      function handleResize() {
        if (window.innerWidth >= 1024) { // lg breakpoint
          // En desktop, el sidebar debe estar visible
          sidebar.classList.remove('-translate-x-full');
          overlay.classList.add('hidden');
        } else {
          // En móvil, mantener cerrado por defecto
          sidebar.classList.add('-translate-x-full');
        }
      }

      window.addEventListener('resize', handleResize);

      // Establecer estado inicial correcto
      handleResize();
    });
  </script>

  {{-- JS propio de cada vista --}}
  @stack('scripts')
</body>
</html>
