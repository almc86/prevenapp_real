'use strict';

window.Helpers = {
  getCssVar: function (varName) {
    return getComputedStyle(document.documentElement).getPropertyValue(varName).trim();
  },

  scrollToActive: function (animate = false) {
    const activeItem = document.querySelector('#layout-menu .menu-item.active');
    if (activeItem) {
      const scrollOptions = {
        behavior: animate ? 'smooth' : 'auto',
        block: 'center'
      };
      activeItem.scrollIntoView(scrollOptions);
    }
  },

  setAutoUpdate: function (enable = true) {
    window.Helpers._autoUpdateEnabled = enable;
  },

  isSmallScreen: function () {
    return window.innerWidth < 1200;
  },

  initPasswordToggle: function () {
    // Detecta inputs con toggle de contraseña
    document.querySelectorAll('.password-toggle .input-group-text').forEach(function (toggle) {
      toggle.addEventListener('click', function () {
        const input = this.closest('.input-group').querySelector('input[type="password"], input[type="text"]');
        if (input) {
          if (input.type === 'password') {
            input.type = 'text';
            this.classList.add('show-password');
          } else {
            input.type = 'password';
            this.classList.remove('show-password');
          }
        }
      });
    });
  },

  initMenu: function () {
    const layoutMenuEl = document.querySelectorAll('#layout-menu');
    layoutMenuEl.forEach(function (element) {
      if (typeof Menu !== 'undefined') {
        new Menu(element, {
          orientation: 'vertical',
          closeChildren: false
        });
      }
    });

    window.Helpers.scrollToActive(false);
    window.Helpers.setAutoUpdate(true);
    window.Helpers.initPasswordToggle();
  }
};

var Helpers = window.Helpers;

document.addEventListener('DOMContentLoaded', function () {
  Helpers.initMenu();

  // Manejo seguro del .layout-menu-toggle
  const menuToggleElems = document.querySelectorAll('.layout-menu-toggle');
  menuToggleElems.forEach(function (elem) {
    let timeout;

    elem.onmouseenter = function () {
      if (!Helpers.isSmallScreen()) {
        timeout = setTimeout(() => {
          if (elem && elem.classList) elem.classList.add('d-block');
        }, 300);
      } else {
        timeout = setTimeout(() => {
          if (elem && elem.classList) elem.classList.add('d-block');
        }, 0);
      }
    };

    elem.onmouseleave = function () {
      const toggleEl = document.querySelector('.layout-menu-toggle');
      if (toggleEl && toggleEl.classList) {
        toggleEl.classList.remove('d-block');
      }
      clearTimeout(timeout);
    };
  });
});
