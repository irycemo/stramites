<?php


use App\Livewire\Admin\Umas;
use App\Livewire\Admin\Roles;
use App\Livewire\Admin\Notarias;
use App\Livewire\Admin\Permisos;
use App\Livewire\Admin\Tramites;
use App\Livewire\Admin\Usuarios;
use App\Livewire\Admin\Auditoria;
use App\Livewire\Admin\Servicios;
use App\Livewire\Entrada\Entrada;
use App\Livewire\Entrega\Entrega;
use App\Livewire\Admin\Categorias;
use App\Livewire\Reportes\Reportes;
use App\Livewire\Admin\Dependencias;
use App\Livewire\Consultas\Consultas;
use App\Livewire\Recepcion\Recepcion;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManualController;
use App\Http\Controllers\TramitesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SetPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});


Route::group(['middleware' => ['auth', 'esta.activo']], function(){

    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::get('roles', Roles::class)->middleware('permission:Lista de roles')->name('roles');

    Route::get('permisos', Permisos::class)->middleware('permission:Lista de permisos')->name('permisos');

    Route::get('usuarios', Usuarios::class)->middleware('permission:Lista de usuarios')->name('usuarios');

    Route::get('servicios', Servicios::class)->middleware('permission:Lista de servicios')->name('servicios');

    Route::get('categorias_servicios', Categorias::class)->middleware('permission:Lista de servicios')->name('categorias_servicios');

    Route::get('umas', Umas::class)->middleware('permission:Lista de umas')->name('umas');

    Route::get('tramites_lista', Tramites::class)->middleware('permission:Lista de trámites')->name('tramites');
    Route::get('tramites/recibo/{tramite}', [TramitesController::class, 'recibo'])->name('tramites.recibo');
    Route::get('tramites/orden/{tramite}', [TramitesController::class, 'orden'])->name('tramites.orden');

    Route::get('dependencias', Dependencias::class)->middleware('permission:Lista de dependencias')->name('dependencias');

    Route::get('notarias', Notarias::class)->middleware('permission:Lista de notarías')->name('notarias');

    Route::get('auditoria', Auditoria::class)->middleware('permission:Auditoria')->name('auditoria');

    Route::get('entrada', Entrada::class)->middleware('permission:Lista de entradas')->name('entrada');

    Route::get('recepcion', Recepcion::class)->middleware('permission:Recepción')->name('recepcion');

    Route::get('entrega', Entrega::class)->middleware('permission:Entrega')->name('entrega');

    Route::get('consultas', Consultas::class)->middleware('permission:Consultas')->name('consultas');

    Route::get('reportes', Reportes::class)->middleware('permission:Reportes')->name('reportes');

});

Route::get('setpassword/{email}', [SetPasswordController::class, 'create'])->name('setpassword');
Route::post('setpassword', [SetPasswordController::class, 'store'])->name('setpassword.store');

Route::get('manual', ManualController::class)->name('manual');
