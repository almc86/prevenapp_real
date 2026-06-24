{{--
  Recorrido guiado del dashboard (onboarding) con Driver.js.

  - Se muestra automáticamente la primera vez (cuando users.tour_dashboard_visto_at
    es NULL) y al entrar con ?tour=1 (link "Ayuda" del sidebar).
  - Al cerrar/terminar marca el tour como visto vía POST onboarding.dashboard.
  - Los pasos cuyo elemento no exista en la página se filtran solos (p.ej. un rol
    sin accesos rápidos verá una versión más corta).
--}}

@push('head')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.3.1/dist/driver.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/driver.js@1.3.1/dist/driver.js.iife.js"></script>
<script>
(function () {
  function iniciarTour() {
    if (!window.driver || !window.driver.js) return;
    const { driver } = window.driver.js;

    // El menú: en móvil resaltamos el botón hamburguesa (visible); en escritorio
    // el sidebar ya está a la vista.
    const hamburguesa = document.querySelector('#mobile-menu-button');
    const menuSel = (hamburguesa && hamburguesa.offsetParent !== null)
      ? '#mobile-menu-button'
      : '#sidebar';

    const pasos = [
      { element: '#tour-welcome', popover: {
        title: '¡Bienvenido a PrevenApp! 👋',
        description: 'Este es tu panel principal. Acá ves de un vistazo cómo está tu cuenta. Te muestro lo importante en un minuto.'
      }},
      { element: '#tour-stats', popover: {
        title: 'Tu resumen',
        description: 'Cuántas empresas, documentos, usuarios, categorías y cargos tenés cargados. Hoy está casi vacío: lo vamos a llenar.'
      }},
      { element: '#tour-quick', popover: {
        title: 'Accesos rápidos',
        description: 'Los atajos para crear lo esencial sin perderte en el menú. Te recomiendo seguir el orden que te marco.'
      }},
      { element: '#tour-nueva-empresa', popover: {
        title: 'Paso 1 — Empresa',
        description: 'Empezá creando tu(s) empresa(s). Es la base sobre la que se arma todo lo demás.'
      }},
      { element: '#tour-nuevo-documento', popover: {
        title: 'Paso 2 — Documentos',
        description: 'Definí los documentos que vas a exigir (contrato, exámenes, certificados, etc.).'
      }},
      { element: '#tour-config', popover: {
        title: 'Paso 3 — Configuración',
        description: 'Acá creás categorías y cargos, y configurás qué documentos pide cada empresa y cada cargo.'
      }},
      { element: '#tour-nuevo-usuario', popover: {
        title: 'Paso 4 — Usuarios (¡clave!)',
        description: 'Creá los usuarios del sistema. OJO: el rol PREVENCIONISTA es quien firma y valida los documentos. Sin un prevencionista cargado no vas a poder operar. Vos, como ADMIN, cargás toda la base.'
      }},
      { element: menuSel, popover: {
        title: 'El menú completo',
        description: 'Todo lo demás (uso y almacenamiento, configuración avanzada, etc.) lo encontrás acá. ¡Listo, ya podés arrancar!'
      }},
    ];

    // Sólo pasos cuyo elemento exista realmente en esta página.
    const pasosValidos = pasos.filter(function (p) {
      return document.querySelector(p.element);
    });
    if (pasosValidos.length === 0) return;

    let marcado = false;
    function marcarVisto() {
      if (marcado) return;
      marcado = true;
      const token = document.querySelector('meta[name="csrf-token"]');
      fetch(@json(route('onboarding.dashboard')), {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': token ? token.content : '',
          'Accept': 'application/json',
        },
        credentials: 'same-origin',
      }).catch(function () {});
    }

    const tour = driver({
      showProgress: true,
      progressText: 'Paso @{{current}} de @{{total}}',
      nextBtnText: 'Siguiente →',
      prevBtnText: '← Anterior',
      doneBtnText: '¡Entendido!',
      steps: pasosValidos,
      onDestroyed: marcarVisto,
    });

    tour.drive();
  }

  // Disponible para volver a lanzarlo manualmente.
  window.startDashboardTour = iniciarTour;

  document.addEventListener('DOMContentLoaded', function () {
    const forzar = new URLSearchParams(location.search).has('tour');
    const yaVisto = @json((bool) (auth()->user()?->tour_dashboard_visto_at));
    if (forzar || !yaVisto) {
      // Pequeño delay para que el layout termine de acomodarse.
      setTimeout(iniciarTour, 500);
    }
  });
})();
</script>
@endpush
