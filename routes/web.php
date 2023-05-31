<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/perfil', [UserController::class, 'perfil'])->name('perfil');
    Route::get('/historial', [HistorialController::class, 'historial'])->name('historial');
    Route::get('/ventas', [HomeController::class, 'ventas'])->name('ventas');
    Route::get('/usuarios', [UserController::class, 'usuarios'])->name('usuarios');
    Route::get('/comandas', [OrdenController::class, 'comandas'])->name('comandas');

    Route::post('comandas/crear', [OrdenController::class, 'comandas_crear'])->name('comandas.crear');
    Route::get('comandas/editar/{id}', [OrdenController::class, 'comandas_editar'])->name('comandas.editar');
    Route::post('comandas/productos',[OrdenController::class,'comandas_productos'])->name('comandas.productos');
    Route::post('comandas/finalizar',[OrdenController::class,'comandas_finalizar'])->name('comandas.finalizar');
    Route::post('comandas/recibo',[OrdenController::class,'comandas_recibo'])->name('comandas.recibo');
    
    Route::post('comandas/opciones',[OrdenController::class,'comandas_opciones'])->name('comandas.opciones');
    Route::post('comandas/detalles',[OrdenController::class,'comandas_detalles'])->name('comandas.detalles');
    Route::post('comandas/agregar',[OrdenController::class,'comandas_agregar'])->name('comandas.agregar');
    Route::post('comandas/opciones/detalles',[OrdenController::class,'comandas_opciones_detalles'])->name('comandas.opcionesdetalles');
    Route::post('comandas/opciones/suma',[OrdenController::class,'comandas_opciones_suma'])->name('comandas.opcionesuma');
    
    Route::post('historial/eliminar', [HistorialController::class, 'historial_eliminar'])->name('historial.eliminar');
    Route::post('historial/editar', [HistorialController::class, 'historial_editar'])->name('historial.editar');
    Route::post('historial/detalles',[HistorialController::class,'historial_detalles'])->name('historial.detalles');

    Route::post('usuarios/crear', [UserController::class, 'usuarios_crear'])->name('usuarios.crear');
    Route::post('usuarios/verificar', [UserController::class, 'usuarios_verificar'])->name('usuarios.verificar');
    Route::post('usuarios/detalles',[UserController::class,'usuarios_detalles'])->name('usuarios.detalles');

});

?>
