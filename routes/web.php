<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Ruta principal
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
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