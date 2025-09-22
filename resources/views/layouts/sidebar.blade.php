{{-- Overlay para móvil --}}
<div id="sidebar-overlay" class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden hidden"></div>

{{-- Sidebar --}}
<div id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0">
  <div class="flex h-full flex-col">
    {{-- Brand --}}
    <div class="flex h-16 flex-shrink-0 items-center border-b border-gray-200 px-6">
      <a href="{{ url('/') }}" class="flex items-center">
        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-600">
          <i class="bx bx-shield text-xl text-white"></i>
        </div>
        <span class="ml-3 text-xl font-bold text-gray-900">PrevenApp</span>
      </a>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 space-y-1 bg-white px-3 py-4 overflow-y-auto">
      {{-- Dashboard (Visible para todos) --}}
      <a href="{{ route('dashboard') }}"
         class="nav-link {{ request()->is('admin/dashboard') ? 'nav-link-active' : 'nav-link-inactive' }}">
        <i class="bx bx-home text-lg mr-3"></i>
        <span>Dashboard</span>
      </a>

      {{-- Menús solo para Administrador --}}
      @role('administrador')
        <a href="{{ route('admin.usuarios.index') }}"
           class="nav-link {{ request()->is('admin/usuarios*') ? 'nav-link-active' : 'nav-link-inactive' }}">
          <i class="bx bx-user text-lg mr-3"></i>
          <span>Gestión de Usuarios</span>
        </a>

        <a href="{{ route('admin.empresas.index') }}"
           class="nav-link {{ request()->is('admin/empresas*') ? 'nav-link-active' : 'nav-link-inactive' }}">
          <i class="bx bx-buildings text-lg mr-3"></i>
          <span>Empresas</span>
        </a>

        <a href="{{ route('admin.config.index') }}"
           class="nav-link {{ request()->routeIs('admin.config.index') ? 'nav-link-active' : 'nav-link-inactive' }}">
          <i class="bx bx-cog text-lg mr-3"></i>
          <span>Configuración</span>
        </a>
      @endrole

      {{-- Menús para otros roles --}}
      @if(Auth::user()->role_id === 2)
        <a href="#" class="nav-link nav-link-inactive">
          <i class="bx bx-folder text-lg mr-3"></i>
          <span>Mis Contratistas</span>
        </a>
      @endif

      {{-- Separador --}}
      <div class="border-t border-gray-200 my-4"></div>

      {{-- Enlaces adicionales --}}
      <div class="space-y-1">
        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
          Soporte
        </h3>
        <a href="#" class="nav-link nav-link-inactive">
          <i class="bx bx-help-circle text-lg mr-3"></i>
          <span>Ayuda</span>
        </a>
        <a href="#" class="nav-link nav-link-inactive">
          <i class="bx bx-phone text-lg mr-3"></i>
          <span>Contacto</span>
        </a>
      </div>
    </nav>

    {{-- User section --}}
    <div class="flex flex-shrink-0 border-t border-gray-200 p-4">
      <div class="flex items-center w-full">
        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-100">
          <i class="bx bx-user text-primary-600"></i>
        </div>
        <div class="ml-3 flex-1 min-w-0">
          <p class="text-sm font-medium text-gray-900 truncate">
            {{ Auth::user()->name }}
          </p>
          <p class="text-xs text-gray-500 truncate">
            {{ Auth::user()->email }}
          </p>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="ml-2">
          @csrf
          <button type="submit" class="text-gray-400 hover:text-gray-500 transition-colors">
            <i class="bx bx-log-out text-lg"></i>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
