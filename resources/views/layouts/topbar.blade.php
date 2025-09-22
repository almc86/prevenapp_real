{{-- Topbar --}}
<div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8 transition-colors duration-300">
  {{-- Botón menú móvil --}}
  <button id="mobile-menu-button" type="button" class="-m-2.5 p-2.5 text-gray-700 dark:text-gray-300 lg:hidden">
    <span class="sr-only">Abrir sidebar</span>
    <i class="bx bx-menu text-xl"></i>
  </button>

  {{-- Separador --}}
  <div class="h-6 w-px bg-gray-200 dark:bg-gray-700 lg:hidden" aria-hidden="true"></div>

  <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
    {{-- Breadcrumb / Page title --}}
    <div class="flex items-center">
      <h1 class="text-lg font-semibold leading-6 text-gray-900 dark:text-white">
        @yield('title', 'Panel de Control')
      </h1>
    </div>

    {{-- Right side --}}
    <div class="flex items-center gap-x-4 lg:gap-x-6 ml-auto">
      {{-- Dark mode toggle para móvil --}}
      <button id="mobile-dark-mode-toggle" type="button" class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500 dark:text-gray-300 dark:hover:text-gray-200 lg:hidden">
        <span class="sr-only">Cambiar modo</span>
        <i id="mobile-dark-mode-icon" class="bx bx-moon text-xl"></i>
      </button>

      {{-- Notificaciones --}}
      <button type="button" class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500 dark:text-gray-300 dark:hover:text-gray-200">
        <span class="sr-only">Ver notificaciones</span>
        <i class="bx bx-bell text-xl"></i>
      </button>

      {{-- Separador --}}
      <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-200 dark:bg-gray-700" aria-hidden="true"></div>

      {{-- Profile dropdown (solo en desktop, en móvil está en sidebar) --}}
      <div class="hidden lg:block relative">
        <div class="flex items-center">
          <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-100 dark:bg-primary-800">
            <i class="bx bx-user text-primary-600 dark:text-primary-300"></i>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-gray-900 dark:text-white">
              {{ Auth::user()->name }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
