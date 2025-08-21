<!-- resources/views/layouts/partials/sidebar.blade.php -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="{{ url('/') }}" class="app-brand-link">
      <span class="app-brand-text demo menu-text fw-bold">Mi Sistema</span>
    </a>
  </div>

  <ul class="menu-inner py-1">
    {{-- Dashboard (Visible para todos) --}}
    <li class="menu-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
      <a href="{{ route('dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home"></i>
        <div>Dashboard</div>
      </a>
    </li>

    {{-- Menús solo para Administrador --}}
    @role('administrador')
      <li class="menu-item {{ request()->is('admin/usuarios*') ? 'active' : '' }}">
        <a href="{{ route('admin.usuarios.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-user"></i>
          <div>Gestión de Usuarios</div>
        </a>
      </li>

      <li class="menu-item {{ request()->is('admin/empresas*') ? 'active' : '' }}">
        <a href="{{ route('admin.empresas.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-buildings"></i>
          <div>Empresas</div>
        </a>
      </li>

      <li class="menu-item">
        <a href="#" class="menu-link">
          <i class="menu-icon tf-icons bx bx-cog"></i>
          <div>Configuración</div>
        </a>
      </li>
    @endrole

    {{-- Menús para otros roles pueden agregarse aquí con else/elseif --}}
    @if(Auth::user()->role_id === 2)
      {{-- Ejemplo para empresa_principal --}}
      <li class="menu-item">
        <a href="#" class="menu-link">
          <i class="menu-icon tf-icons bx bx-folder"></i>
          <div>Mis Contratistas</div>
        </a>
      </li>
    @endif

  </ul>
</aside>
