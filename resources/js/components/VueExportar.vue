<template>
  <div class="card shadow-sm">

    <div class="card-header bg-dark text-white">
      <h5 class="mb-0">Exportar datos a CSV</h5>
    </div>

    <div class="card-body">

      <p class="text-muted mb-4">
        Al presionar el botón se exportarán los datos de las
        <strong>8 tablas</strong> del sistema. Cada tabla generará
        un archivo CSV independiente con el formato
        <code>NombreTabla_FechaHora.csv</code>.
      </p>

      <!-- Mensaje de confirmación -->
      <div v-if="mensajeExito" class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>¡Exportación exitosa!</strong> {{ mensajeExito }}
        <button type="button" class="btn-close" @click="mensajeExito = ''"></button>
      </div>

      <!-- Mensaje de error -->
      <div v-if="mensajeError" class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error:</strong> {{ mensajeError }}
        <button type="button" class="btn-close" @click="mensajeError = ''"></button>
      </div>

      <!-- Lista de tablas -->
      <div class="row mb-4">
        <div class="col-md-6">
          <ul class="list-group list-group-flush">
            <li
              v-for="tabla in tablas.slice(0, 4)"
              :key="tabla.nombre"
              class="list-group-item d-flex justify-content-between align-items-center"
            >
              {{ tabla.nombre }}
              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-dark">CSV</span>

                <!-- Botón descarga individual -->
                <button
                  class="btn btn-sm btn-outline-dark p-1"
                  @click="exportarTablaIndividual(tabla)"
                  :disabled="tabla.descargando"
                  title="Descargar esta tabla"
                >
                  <span v-if="tabla.descargando">
                    <span class="spinner-border spinner-border-sm"></span>
                  </span>
                  <span v-else>
                    <img
                      :src="iconoDescarga"
                      alt="Descargar"
                      style="width: 18px; height: 15px; object-fit: contain;"
                    >
                  </span>
                </button>
              </div>
            </li>
          </ul>
        </div>

        <div class="col-md-6">
          <ul class="list-group list-group-flush">
            <li
              v-for="tabla in tablas.slice(4, 8)"
              :key="tabla.nombre"
              class="list-group-item d-flex justify-content-between align-items-center"
            >
              {{ tabla.nombre }}
              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-dark">CSV</span>

                <!-- Botón descarga individual -->
                <button
                  class="btn btn-sm btn-outline-dark p-1"
                  @click="exportarTablaIndividual(tabla)"
                  :disabled="tabla.descargando"
                  title="Descargar esta tabla"
                >
                  <span v-if="tabla.descargando">
                    <span class="spinner-border spinner-border-sm"></span>
                  </span>
                  <span v-else>
                    <img
                      src="/public/assets/descargas.png"
                      alt="Descargar"
                      style="width: 18px; height: 18px; object-fit: contain;"
                    >
                  </span>
                </button>
              </div>
            </li>
          </ul>
        </div>
      </div>

      <!-- Botón exportar todas -->
      <div class="d-grid">
        <button
          class="btn btn-dark btn-lg"
          @click="exportarCSV"
          :disabled="exportando"
        >
          <span v-if="exportando">
            <span class="spinner-border spinner-border-sm me-2"></span>
            Exportando...
          </span>
          <span v-else>
            Exportar todas las tablas a CSV
          </span>
        </button>
      </div>

    </div>
  </div>
</template>

<script>
import iconoDescarga from '/public/assets/descargas.png'
export default {
  name: 'VueExportar',

  data() {
    return {
      iconoDescarga,
      exportando: false,
      mensajeExito: '',
      mensajeError: '',

      tablas: [
        { nombre: 'usuarios',          url: '/vue/data/usuarios',          descargando: false },
        { nombre: 'vehiculos',         url: '/vue/data/vehiculos',         descargando: false },
        { nombre: 'imagenes_vehiculo', url: '/vue/data/imagenes-vehiculo', descargando: false },
        { nombre: 'ubicaciones',       url: '/vue/data/ubicaciones',       descargando: false },
        { nombre: 'compras',           url: '/vue/data/compras',           descargando: false },
        { nombre: 'pagos',             url: '/vue/data/pagos',             descargando: false },
        { nombre: 'favoritos',         url: '/vue/data/favoritos',         descargando: false },
        { nombre: 'resenas',           url: '/vue/data/resenas',           descargando: false },
      ]
    }
  },

  methods: {

    // Exportar una sola tabla individualmente
    async exportarTablaIndividual(tabla) {
      tabla.descargando = true
      this.mensajeExito = ''
      this.mensajeError = ''

      try {
        const response = await axios.get(tabla.url)

        if (response.data && response.data.length > 0) {
          this.generarCSV(tabla.nombre, response.data)
          this.mensajeExito = `Tabla "${tabla.nombre}" exportada correctamente.`
        } else {
          this.mensajeError = `La tabla "${tabla.nombre}" no tiene datos para exportar.`
        }

      } catch (error) {
        console.error(`Error al exportar ${tabla.nombre}:`, error)
        this.mensajeError = `Error al exportar la tabla "${tabla.nombre}". Intente de nuevo.`
      } finally {
        tabla.descargando = false
      }
    },

    // Exportar todas las tablas
    async exportarCSV() {
      this.exportando   = true
      this.mensajeExito = ''
      this.mensajeError = ''

      try {
        const promesas = this.tablas.map(tabla =>
          axios.get(tabla.url)
            .then(response => ({
              nombre:   tabla.nombre,
              datos:    response.data,
              correcto: true
            }))
            .catch(() => ({
              nombre:   tabla.nombre,
              datos:    [],
              correcto: false
            }))
        )

        const resultados = await Promise.all(promesas)

        let archivosDescargados = 0
        let tablasConError = []

        resultados.forEach(resultado => {
          if (resultado.correcto) {
            if (resultado.datos.length > 0) {
              this.generarCSV(resultado.nombre, resultado.datos)
              archivosDescargados++
            }
          } else {
            tablasConError.push(resultado.nombre)
          }
        })

        await this.abrirExplorador()

        if (tablasConError.length > 0) {
          this.mensajeExito = `Se exportaron ${archivosDescargados} archivos correctamente.`
          this.mensajeError = `No se pudieron exportar: ${tablasConError.join(', ')}.`
        } else {
          this.mensajeExito = `¡Exportación exitosa! Se exportaron las ${archivosDescargados} tablas correctamente.`
        }

      } catch (error) {
        console.error('Error crítico al exportar:', error)
        this.mensajeError = 'Ocurrió un error inesperado en el sistema.'
      } finally {
        this.exportando = false
      }
    },

    // Genera y descarga el CSV
    generarCSV(nombreTabla, datos) {
      if (!datos || datos.length === 0) return

      const encabezados = Object.keys(datos[0])

      const filas = datos.map(fila =>
        encabezados.map(col => {
          const valor = fila[col] ?? ''
          return typeof valor === 'string' && (valor.includes(',') || valor.includes('"'))
            ? `"${valor.replace(/"/g, '""')}"`
            : valor
        }).join(',')
      )

      const contenidoCSV = [encabezados.join(','), ...filas].join('\n')

      const ahora         = new Date()
      const fecha         = ahora.toISOString().slice(0, 10)
      const hora          = ahora.toTimeString().slice(0, 8).replace(/:/g, '')
      const nombreArchivo = `${nombreTabla}_${fecha}_${hora}.csv`

      const blob = new Blob([contenidoCSV], { type: 'text/csv;charset=utf-8;' })
      const url  = URL.createObjectURL(blob)
      const link = document.createElement('a')

      link.setAttribute('href', url)
      link.setAttribute('download', nombreArchivo)
      link.style.display = 'none'

      document.body.appendChild(link)
      link.click()
      document.body.removeChild(link)
      URL.revokeObjectURL(url)
    },

    async abrirExplorador() {
      await axios.post('/vue/abrir-explorador')
    }
  }
}
</script>