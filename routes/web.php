<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehiculoController;

/*
|--------------------------------------------------------------------------
| Ruta principal
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('auth.login');
});

/*
|--------------------------------------------------------------------------
| Rutas de autenticación (semana 5-6)
| Auth::routes() registra automáticamente todas estas rutas:
|
| GET  /login          → mostrar formulario de login
| POST /login          → procesar login
| POST /logout         → cerrar sesión
| GET  /register       → mostrar formulario de registro
| POST /register       → procesar registro
| GET  /password/reset → formulario de recuperar contraseña
|--------------------------------------------------------------------------
*/
Auth::routes();

/*
|--------------------------------------------------------------------------
| Rutas protegidas — requieren estar autenticado
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard principal después del login
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // Vehículos
    Route::get('vehiculos/cards', [VehiculoController::class, 'indexCards'])->name('vehiculos.indexCards');
    Route::resource('vehiculos', App\Http\Controllers\VehiculoController::class);

    // Imágenes de vehículo
    Route::resource('imagenes-vehiculo', App\Http\Controllers\ImagenVehiculoController::class);

    // Ubicaciones
    Route::resource('ubicaciones', App\Http\Controllers\UbicacionController::class);

    // Usuarios
    Route::resource('usuarios', App\Http\Controllers\UsuarioController::class);

    // Compras
    Route::resource('compras', App\Http\Controllers\CompraController::class);

    // Pagos
    Route::resource('pagos', App\Http\Controllers\PagoController::class);

    // Favoritos
    Route::resource('favoritos', App\Http\Controllers\FavoritoController::class);

    // Reseñas
    Route::resource('resenas', App\Http\Controllers\ResenaController::class);

    // Historial de transacciones
    Route::get('/historial', [App\Http\Controllers\HistorialController::class, 'index'])->name('historial.index');

});

/*
|--------------------------------------------------------------------------
| Rutas del módulo Vue (Fase 6 - semana 8)
| Guard independiente 'vue' - NO usa el guard 'web' de la Fase 2
|--------------------------------------------------------------------------
*/

// Rutas públicas del módulo Vue (sin autenticación)
Route::prefix('vue')->group(function () {

    // Login Vue
    Route::get('/login', [App\Http\Controllers\VueAuthController::class, 'showLogin'])
         ->name('vue.login');

    Route::post('/login', [App\Http\Controllers\VueAuthController::class, 'login'])
         ->name('vue.login.post');

    // Logout Vue
    Route::post('/logout', [App\Http\Controllers\VueAuthController::class, 'logout'])
         ->name('vue.logout');

});

// Rutas protegidas del módulo Vue (requieren auth del guard vue)
Route::prefix('vue')->middleware(['auth.vue'])->group(function () {

    // Vista principal que carga los componentes Vue
    Route::get('/', [App\Http\Controllers\VueAuthController::class, 'index'])
         ->name('vue.index');

    // Endpoints JSON para la exportación CSV (usados por fetch() desde Vue)
    Route::get('/data/usuarios', [App\Http\Controllers\ExportController::class, 'usuarios'])
         ->name('vue.data.usuarios');

    Route::get('/data/vehiculos', [App\Http\Controllers\ExportController::class, 'vehiculos'])
         ->name('vue.data.vehiculos');

    Route::get('/data/imagenes-vehiculo', [App\Http\Controllers\ExportController::class, 'imagenesVehiculo'])
         ->name('vue.data.imagenes');

    Route::get('/data/ubicaciones', [App\Http\Controllers\ExportController::class, 'ubicaciones'])
         ->name('vue.data.ubicaciones');

    Route::get('/data/compras', [App\Http\Controllers\ExportController::class, 'compras'])
         ->name('vue.data.compras');

    Route::get('/data/pagos', [App\Http\Controllers\ExportController::class, 'pagos'])
         ->name('vue.data.pagos');

    Route::get('/data/favoritos', [App\Http\Controllers\ExportController::class, 'favoritos'])
         ->name('vue.data.favoritos');

    Route::get('/data/resenas', [App\Http\Controllers\ExportController::class, 'resenas'])
         ->name('vue.data.resenas');

    // Endpoint para abrir el explorador de Windows
    Route::post('/abrir-explorador', [App\Http\Controllers\ExportController::class, 'abrirExplorador'])
         ->name('vue.abrir.explorador');
});