<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SapControllerApi;
use App\Http\Controllers\Api\TramitesController;
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

    Route::post('finalizar_tramite', [TramitesController::class, 'finalizar']);

    Route::post('rechazar_tramite', [TramitesController::class, 'rechazar']);

    Route::post('acredita_pago', SapControllerApi::class);

    Route::get('consultar_servicios', [ServiciosApiController::class, 'consultarServicios']);

    Route::post('craer_tramite', [TramitesApiController::class, 'crearTramtie']);

    Route::get('consultar_tramites', [TramitesApiController::class, 'consultarTramites']);

});

Route::fallback(function(){
    return response()->json([
            'result' => 'error',
            'data' => 'Página no encontrada.']
        , 404);
});
