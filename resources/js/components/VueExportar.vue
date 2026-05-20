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

      <!-- Mensaje de confirmación (punto 6.4 del enunciado) -->
      <div v-if="mensajeExito" class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>¡Exportación exitosa!</strong> {{ mensajeExito }}
        <button type="button" class="btn-close" @click="mensajeExito = ''"></button>
      </div>

      <!-- Mensaje de error (punto 6.4 del enunciado) -->
      <div v-if="mensajeError" class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error:</strong> {{ mensajeError }}
        <button type="button" class="btn-close" @click="mensajeError = ''"></button>
      </div>

      <!-- Lista de tablas que se exportarán -->
      <div class="row mb-4">
        <div class="col-md-6">
          <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-center">
              usuarios
              <span class="badge bg-dark">CSV</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              vehiculos
              <span class="badge bg-dark">CSV</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              imagenes_vehiculo
              <span class="badge bg-dark">CSV</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              ubicaciones
              <span class="badge bg-dark">CSV</span>
            </li>
          </ul>
        </div>
        <div class="col-md-6">
          <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-center">
              compras
              <span class="badge bg-dark">CSV</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              pagos
              <span class="badge bg-dark">CSV</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              favoritos
              <span class="badge bg-dark">CSV</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              reseñas
              <span class="badge bg-dark">CSV</span>
            </li>
          </ul>
        </div>
      </div>

      <!-- Botón exportar (punto 6.1 del enunciado) -->
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
            Exportar datos a CSV
          </span>
        </button>
      </div>

    </div>
  </div>
</template>

<script>
export default {
  name: 'VueExportar',

  data() {
    return {
      exportando: false,
      mensajeExito: '',
      mensajeError: '',

      // Tablas a exportar con sus endpoints
      tablas: [
        { nombre: 'usuarios',          url: '/vue/data/usuarios'         },
        { nombre: 'vehiculos',         url: '/vue/data/vehiculos'        },
        { nombre: 'imagenes_vehiculo', url: '/vue/data/imagenes-vehiculo'},
        { nombre: 'ubicaciones',       url: '/vue/data/ubicaciones'      },
        { nombre: 'compras',           url: '/vue/data/compras'          },
        { nombre: 'pagos',             url: '/vue/data/pagos'            },
        { nombre: 'favoritos',         url: '/vue/data/favoritos'        },
        { nombre: 'resenas',           url: '/vue/data/resenas'          },
      ]
    }
  },

  methods: {

    // Método principal que exporta todas las tablas
    async exportarCSV() {
      this.exportando   = true
      this.mensajeExito = ''
      this.mensajeError = ''

      try {
        // Obtener datos de cada tabla usando axios
        // Patrón tomado del proyecto semana 12
        const promesas = this.tablas.map(tabla =>
          axios.get(tabla.url).then(response => ({
            nombre: tabla.nombre,
            datos:  response.data
          }))
        )

        const resultados = await Promise.all(promesas)

        // Generar y descargar un CSV por cada tabla
        resultados.forEach(resultado => {
          this.generarCSV(resultado.nombre, resultado.datos)
        })

        // Abrir explorador de Windows en la carpeta de descargas
        await this.abrirExplorador()

        this.mensajeExito = 'Se exportaron 8 archivos CSV correctamente en la carpeta de Descargas.'

      } catch (error) {
        console.error('Error al exportar:', error)
        this.mensajeError = 'Error al exportar los archivos. Intente de nuevo.'

      } finally {
        this.exportando = false
      }
    },

    // Genera y descarga un archivo CSV desde los datos JSON
    generarCSV(nombreTabla, datos) {
      if (!datos || datos.length === 0) return

      // Encabezados tomados de las keys del primer objeto
      const encabezados = Object.keys(datos[0])

      // Filas de datos
      const filas = datos.map(fila =>
        encabezados.map(col => {
          const valor = fila[col] ?? ''
          // Si el valor contiene comas o comillas, envolverlo en comillas
          return typeof valor === 'string' && (valor.includes(',') || valor.includes('"'))
            ? `"${valor.replace(/"/g, '""')}"`
            : valor
        }).join(',')
      )

      // Armar contenido CSV
      const contenidoCSV = [encabezados.join(','), ...filas].join('\n')

      // Generar nombre del archivo: NombreTabla_FechaHora.csv
      const ahora      = new Date()
      const fecha      = ahora.toISOString().slice(0, 10)             // 2026-04-14
      const hora       = ahora.toTimeString().slice(0, 8).replace(/:/g, '') // 153022
      const nombreArchivo = `${nombreTabla}_${fecha}_${hora}.csv`

      // Crear blob y disparar descarga
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

    // Llama al endpoint Laravel que abre el explorador de Windows
    async abrirExplorador() {
      await axios.post('/vue/abrir-explorador')
    }

  }
}
</script>