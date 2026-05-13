<?php

namespace App\Http\Controllers\Api;

use App\Models\Tramite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Services\Tramites\TramiteService;
use App\Http\Requests\SapActualizarPagoRequest;

class SapControllerApi extends Controller
{

    public function __invoke(SapActualizarPagoRequest $request){

        $validated = $request->validated();

        try {

            DB::transaction(function () use($validated){

                $tramite = Tramite::where('linea_de_captura', $validated['linea_de_captura'])->firstOrFail();

                (new TramiteService($tramite))->procesarPago($validated['fecha'], $validated['documento_pago']);

                return response()->json([
                    'result' => 'success',
                ], 200);

            });

        } catch (\Throwable $th) {

            Log::error("Error al actualizar estado del trámite desde SAP " . $th);

            return response()->json([
                'result' => 'error',
            ], 500);
        }


     }

}
