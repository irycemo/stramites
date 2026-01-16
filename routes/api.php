<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\TramitesApiController;
use App\Http\Controllers\Api\V1\ServiciosApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::post('acredita_pago', [TramitesApiController::class, 'acreditarTramite']);

    Route::post('finalizar_tramite', [TramitesApiController::class, 'finalizarTramite']);

    Route::post('rechazar_tramite', [TramitesApiController::class, 'rechazarTramite']);

    Route::post('consultar_servicios', [ServiciosApiController::class, 'consultarServicios']);

    Route::get('consultar_servicio', [ServiciosApiController::class, 'consultarServicio']);

    Route::post('crear_tramite', [TramitesApiController::class, 'crearTramite']);

    Route::post('consultar_tramites', [TramitesApiController::class, 'consultarTramites']);

    Route::post('acreditar_tramite', [TramitesApiController::class, 'acreditarTramite']);

    Route::post('consultar_archivo', [TramitesApiController::class, 'consultarArchivo']);

});

/* Route::fallback(function(){
    return response()->json([
            'result' => 'error',
            'data' => 'Página no encontradass.']
        , 404);
}); */

