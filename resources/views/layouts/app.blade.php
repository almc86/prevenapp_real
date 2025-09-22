<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
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

  {{-- Dark mode script - debe estar en head para evitar flash --}}
  <script>
    // Detectar preferencia del sistema o la guardada en localStorage
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark')
    } else {
      document.documentElement.classList.remove('dark')
    }
  </script>
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
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

      // Dark mode toggle functionality
      const darkModeToggle = document.getElementById('dark-mode-toggle');
      const darkModeIcon = document.getElementById('dark-mode-icon');
      const darkModeText = document.getElementById('dark-mode-text');
      const mobileDarkModeToggle = document.getElementById('mobile-dark-mode-toggle');
      const mobileDarkModeIcon = document.getElementById('mobile-dark-mode-icon');

      // Función para actualizar el estado visual del toggle
      function updateDarkModeUI() {
        const isDark = document.documentElement.classList.contains('dark');

        if (darkModeIcon && darkModeText) {
          if (isDark) {
            darkModeIcon.className = 'bx bx-sun text-lg mr-3';
            darkModeText.textContent = 'Modo Claro';
          } else {
            darkModeIcon.className = 'bx bx-moon text-lg mr-3';
            darkModeText.textContent = 'Modo Oscuro';
          }
        }

        if (mobileDarkModeIcon) {
          if (isDark) {
            mobileDarkModeIcon.className = 'bx bx-sun text-xl';
          } else {
            mobileDarkModeIcon.className = 'bx bx-moon text-xl';
          }
        }
      }

      // Función para toggle del modo oscuro
      function toggleDarkMode() {
        const html = document.documentElement;
        const isDark = html.classList.contains('dark');

        if (isDark) {
          html.classList.remove('dark');
          localStorage.theme = 'light';
        } else {
          html.classList.add('dark');
          localStorage.theme = 'dark';
        }

        updateDarkModeUI();
      }

      // Agregar event listeners para ambos botones
      if (darkModeToggle) {
        darkModeToggle.addEventListener('click', toggleDarkMode);
      }

      if (mobileDarkModeToggle) {
        mobileDarkModeToggle.addEventListener('click', toggleDarkMode);
      }

      // Establecer estado inicial del UI
      updateDarkModeUI();
    });
  </script>

  {{-- JS propio de cada vista --}}
  @stack('scripts')
</body>
</html>
