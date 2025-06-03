<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SeccionController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ConfCorreoController;
use App\Http\Controllers\CorreoController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ConfiguracionCredencialesController;
use App\Http\Controllers\DocumentoCompetenciaController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\CompetenciaController;


Route::get('/', function () {
    return redirect('/login');
});



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::middleware(['auth', 'can:Administración de Usuarios'])->group(function () {

    Route::get('/usuarios', [UserController::class, 'index'])
        ->name('users.index')
        ->middleware('can:usuarios.ver');

    Route::get('/usuarios/crear', [UserController::class, 'create'])
        ->name('users.create')
        ->middleware('can:usuarios.crear');

    Route::post('/usuarios', [UserController::class, 'store'])
        ->name('users.store')
        ->middleware('can:usuarios.crear');

    Route::get('/usuarios/{user}', [UserController::class, 'show'])
        ->name('users.show')
        ->middleware('can:usuarios.ver');

    Route::get('/usuarios/edit/{id}', [UserController::class, 'edit'])
        ->name('users.edit')
        ->middleware('can:usuarios.editar');

    Route::put('/usuarios/{id}/{perfil}', [UserController::class, 'update'])
        ->name('users.update')
        ->middleware('can:usuarios.editar');

    Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])
        ->name('users.destroy')
        ->middleware('can:usuarios.eliminar');

    Route::get('/datos/usuario/{id}', [UserController::class, 'GetUsuario'])
        ->name('users.get')
        ->middleware('can:usuarios.ver');

    Route::get('/usuarios/exportar/excel', [UserController::class, 'exportExcel'])->name('usuarios.exportar_excel')->middleware(middleware: 'can:usuarios.exportar_excel');
    Route::get('/usuarios/exportar/pdf', [UserController::class, 'exportPDF'])->name('usuarios.exportar_pdf')->middleware('can:usuarios.exportar_pdf');


});



//Rutas para secciones
Route::resource('secciones', SeccionController::class)->except([
    'show',




])->middleware(['auth', 'role:admin']);

Route::post('/api/sugerir-icono', [SeccionController::class, 'SugerirIcono']);

Route::post('obtener/dato/menu', [SeccionController::class, 'cambiarSeccion'])->middleware(['auth', 'role:admin']);
//Rutas para Menus
Route::resource('menus', MenuController::class)->except([
    'show',
])->middleware(['auth', 'role:admin']);


// Rutas para la configuracion de correo

Route::middleware(['auth', 'can:Configuración'])->group(function () {

    Route::get('/configuracion/correo', [ConfCorreoController::class, 'index'])
        ->name('configuracion.correo.index')
        ->middleware('can:configuracion_correo.ver');

    Route::post('/configuracion/correo/guardar', [ConfCorreoController::class, 'store'])
        ->name('configuracion.correo.store')
        ->middleware('can:configuracion_correo.actualizar');

    Route::get('/correo/prueba', [ConfCorreoController::class, 'enviarPrueba'])
        ->name('correo.prueba')
        ->middleware('can:configuracion.correo');

    Route::get('/correos/plantillas', [CorreoController::class, 'index'])
        ->name('correos.index')
        ->middleware('can:plantillas.ver');

    Route::put('/editar/plantilla/{id}', [CorreoController::class, 'update_plantilla'])
        ->name('plantilla.update')
        ->middleware('can:plantillas.actualizar');

    Route::get('/obtener/plantilla/{id}', [CorreoController::class, 'GetPlantilla'])
        ->name('obtener.correo');

});

//cambio de contraseña
Route::middleware(['auth'])->group(function () {

    Route::get('/usuario/contraseña', [PasswordController::class, 'ActualizarContraseña'])->name('user.actualizar.contraseña');
    Route::put('password/update', [PasswordController::class, 'update'])->name('password.actualizar');

    Route::get('/usuario/perfil', [UserController::class, 'Perfil'])
        ->name('perfil');
});



Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/roles', [RoleController::class, 'index'])
        ->name('roles.index')
        ->middleware('can:roles.inicio');

    Route::get('/roles/create', [RoleController::class, 'create'])
        ->name('roles.create')
        ->middleware('can:roles.crear');

    Route::post('/roles', [RoleController::class, 'store'])
        ->name('roles.store')
        ->middleware('can:roles.guardar');

    Route::get('/roles/edit/{id}', [RoleController::class, 'edit'])
        ->name('roles.edit')
        ->middleware('can:roles.editar');

    Route::put('/roles/{id}', [RoleController::class, 'update'])
        ->name('roles.update')
        ->middleware('can:roles.actualizar');

    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])
        ->name('roles.destroy')
        ->middleware('can:roles.eliminar');

    Route::get('/permissions', [PermissionController::class, 'index'])
        ->name('permissions.index')
        ->middleware('can:permisos.inicio');

    Route::get('/permissions/create', [PermissionController::class, 'create'])
        ->name('permissions.create')
        ->middleware('can:permisos.crear');

    Route::post('/permissions', [PermissionController::class, 'store'])
        ->name('permissions.store')
        ->middleware('can:permisos.guardar');

    Route::get('/permissions/edit/{id}', [PermissionController::class, 'edit'])
        ->name('permissions.edit')
        ->middleware('can:permisos.editar');

    Route::put('/permissions/{id}', [PermissionController::class, 'update'])
        ->name('permissions.update')
        ->middleware('can:permisos.actualizar');

    Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])
        ->name('permissions.destroy')
        ->middleware('can:permisos.eliminar');

    Route::get('/permissions/cargar/menu/{id}/{rol_id}', [RoleController::class, 'get_permisos_menu'])
        ->name('permissions.menu');

});




//Rutas configuracion general

Route::middleware(['auth', 'role:admin', 'can:Configuración General'])->group(function () {

    Route::get('/admin/configuracion', [ConfiguracionController::class, 'edit'])
        ->name('admin.configuracion.edit')
        ->middleware('can:configuracion.inicio');

    Route::put('/admin/configuracion', [ConfiguracionController::class, 'update'])
        ->name('admin.configuracion.update')
        ->middleware('can:configuracion.actualizar');

});

Route::middleware(['auth', 'role:admin', 'can:Configuración Credenciales'])->group(function () {

    Route::get('/configuracion/credenciales', [ConfiguracionCredencialesController::class, 'index'])->name('configuracion.credenciales.index')->middleware('can:configuracion.credenciales_ver');
    Route::post('/configuracion/credenciales/actualizar', [ConfiguracionCredencialesController::class, 'actualizar'])->name('configuracion.credenciales.actualizar')->middleware('can:configuracion.credenciales_actualizar');

});


//doble factor de autenticacion
Route::get('/2fa/verify', [TwoFactorController::class, 'index'])->name('verify.index');
Route::post('/2fa/verify', [TwoFactorController::class, 'store'])->name('verify.store');
Route::post('/2fa/resend', [TwoFactorController::class, 'resend'])->name('verify.resend');

//Catalogo


Route::middleware(['auth', 'role:admin', 'can:Configuración General'])->group(function () {


});
Route::middleware(['auth', 'can:Administración y Parametrización'])->group(function () {

    // Rutas para catalogos
    Route::get('/catalogos', [CatalogoController::class, 'index'])->name('catalogos.index')->middleware('can:catalogo.ver');
    Route::get('/catalogos/create', [CatalogoController::class, 'create'])->name('catalogos.create')->middleware('can:catalogo.crear');
    Route::post('/catalogos', [CatalogoController::class, 'store'])->name('catalogos.store')->middleware('can:catalogo.guardar');
    Route::get('/catalogos/{id}', [CatalogoController::class, 'show'])->name('catalogos.show')->middleware('can:catalogo.ver_detalle');
    Route::get('/catalogos/{id}/edit', [CatalogoController::class, 'edit'])->name('catalogos.edit')->middleware('can:catalogo.editar');
    Route::put('/catalogos/{id}', [CatalogoController::class, 'update'])->name('catalogos.update')->middleware('can:catalogo.actualizar');
    Route::delete('/catalogos/{id}', [CatalogoController::class, 'destroy'])->name('catalogos.destroy')->middleware('can:catalogo.eliminar');

    // Rutas para categorias
    Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index')->middleware('can:categoria.ver');
    Route::get('/categorias/create', [CategoriaController::class, 'create'])->name('categorias.create')->middleware('can:categoria.crear');
    Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store')->middleware('can:categoria.guardar');
    Route::get('/categorias/{id}', [CategoriaController::class, 'show'])->name('categorias.show')->middleware('can:categoria.ver_detalle');
    Route::get('/categorias/{id}/edit', [CategoriaController::class, 'edit'])->name('categorias.edit')->middleware('can:categoria.editar');
    Route::put('/categorias/{id}', [CategoriaController::class, 'update'])->name('categorias.update')->middleware('can:categoria.actualizar');
    Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy'])->name('categorias.destroy')->middleware('can:categoria.eliminar');
});



Route::middleware(['auth'])->group(function () {

    Route::get('/competencias', [CompetenciaController::class, 'index'])->name('competencias.index')->middleware('can:competencias.ver');
    Route::get('/competencias/create', [CompetenciaController::class, 'create'])->name('competencias.create')->middleware('can:competencias.crear');
    Route::post('/competencias', [CompetenciaController::class, 'store'])->name('competencias.store')->middleware('can:competencias.guardar');
    Route::get('/competencias/{id}', [CompetenciaController::class, 'show'])->name('competencias.show')->middleware('can:competencias.ver');
    Route::get('/competencias/{id}/edit', [CompetenciaController::class, 'edit'])->name('competencias.edit')->middleware('can:competencias.editar');
    Route::put('/competencias/{id}', [CompetenciaController::class, 'update'])->name('competencias.update')->middleware('can:competencias.actualizar');
    Route::delete('/competencias/{id}', [CompetenciaController::class, 'destroy'])->name('competencias.destroy')->middleware('can:competencias.eliminar');


    Route::get('/documentos', [DocumentoCompetenciaController::class, 'index'])->name('documento_competencia.index')->middleware('can:documento_competencia.ver');
    Route::get('/documentos/crear/{competencia}', [DocumentoCompetenciaController::class, 'create'])->name('documento_competencia.create')->middleware('can:documento_competencia.crear');
    Route::post('/documentos/{competencia}', [DocumentoCompetenciaController::class, 'store'])->name('documento_competencia.store')->middleware('can:documento_competencia.guardar');
    Route::get('/documentos/{id}/editar', [DocumentoCompetenciaController::class, 'edit'])->name('documento_competencia.edit')->middleware('can:documento_competencia.editar');
    Route::put('/documentos/{id}', [DocumentoCompetenciaController::class, 'update'])->name('documento_competencia.update')->middleware('can:documento_competencia.actualizar');
    Route::delete('/documentos/{id}', [DocumentoCompetenciaController::class, 'destroy'])->name('documento_competencia.destroy')->middleware('can:documento_competencia.eliminar');
    Route::get('documentos/{archivo}', [DocumentoCompetenciaController::class, 'mostrarArchivo'])->name('documentos.mostrar');



    Route::get('eventos', [EventoController::class, 'index'])->name('eventos.index');
    Route::get('eventos/create', [EventoController::class, 'create'])->name('eventos.create');
    Route::post('eventos', [EventoController::class, 'store'])->name('eventos.store');
    Route::get('eventos/{evento}', [EventoController::class, 'show'])->name('eventos.show');
    Route::get('eventos/{evento}/edit', [EventoController::class, 'edit'])->name('eventos.edit');
    Route::put('eventos/{evento}', [EventoController::class, 'update'])->name('eventos.update');
    Route::delete('eventos/{evento}', [EventoController::class, 'destroy'])->name('eventos.destroy');


    Route::get('/maps', [MapController::class, 'index'])->name('maps.index');
    Route::get('/maps/create', [MapController::class, 'create'])->name('maps.create');
    Route::post('/maps/{tipo}', [MapController::class, 'store'])->name('maps.store');
    Route::get('/maps/{map}/edit', [MapController::class, 'edit'])->name('maps.edit');
    Route::put('/maps/{map}', [MapController::class, 'update'])->name('maps.update');
    Route::delete('/maps/{map}', [MapController::class, 'destroy'])->name('maps.destroy');
    Route::get('/ubicaciones/{id}', [MapController::class, 'showJSON']);

    Route::get('/ubicaciones/json', [MapController::class, 'getAllJson'])->name('ubicaciones.json');

});




