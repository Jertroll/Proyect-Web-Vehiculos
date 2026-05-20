<template>
  <div class="container mt-4">

    <!-- Navbar del módulo Vue -->
    <nav class="navbar navbar-dark bg-dark rounded mb-4 px-3">
      <span class="navbar-brand mb-0 h5">
        Módulo Vue — Proyecto Vehículos
      </span>
      <div class="d-flex align-items-center gap-3">
        <span class="text-white small">
          Sesión Vue activa
        </span>

        <!-- Botón logout Vue (punto 6.3 del enunciado) -->
        <!-- Este logout es independiente del logout de Laravel (Fase 2) -->
        <form method="POST" :action="logoutUrl">
          <input type="hidden" name="_token" :value="csrfToken">
          <button type="submit" class="btn btn-outline-light btn-sm">
            Cerrar sesión Vue
          </button>
        </form>
      </div>
    </nav>

    <!-- Información del módulo -->
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <h5 class="card-title">Panel de exportación</h5>
        <p class="card-text text-muted mb-0">
          Desde este módulo podés exportar los datos de todas las tablas
          del sistema a archivos CSV.
        </p>
      </div>
    </div>

    <!-- Componente hijo anidado VueExportar (punto 6.1 del enunciado) -->
    <!-- VuePrincipal (padre) anida a VueExportar (hijo) -->
    <VueExportar />

  </div>
</template>

<script>
import VueExportar from './VueExportar.vue'

export default {
  name: 'VuePrincipal',

  components: {
    VueExportar
  },

  data() {
    return {
      // URL del logout Vue - apunta al VueAuthController
      logoutUrl: '/vue/logout',

      // Token CSRF tomado del meta tag del blade
      csrfToken: document.querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content')
    }
  }
}
</script>

<style>
body {
  background-color: #f8f9fa;
  font-family: Arial, sans-serif;
}
</style>