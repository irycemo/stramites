<?php

namespace App\Http\Controllers\Api;

use App\Models\Tramite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TramiteRequest;
use App\Http\Services\Tramites\TramiteService;

class TramitesController extends Controller
{

    public function finalizar(TramiteRequest $request){

        try {

            $data = $request->validated();

            $tramite = Tramite::where('año', $data['año'])->where('numero_control', $data['tramite'])->firstOrFail();

            (new TramiteService($tramite))->cambiarEstado($data['estado']);

            return response()->json([
                'result' => 'success',
                'data' => []
            ], 200);

        } catch (\Throwable $th) {

            return response()->json([
                'result' => 'error',
                'data' => $th->getMessage(),
            ], 500);

        }

    }

    public function rechazar(TramiteRequest $request){

        try {

            $data = $request->validated();

            $tramite = Tramite::where('año', $data['año'])->where('numero_control', $data['tramite'])->firstOrFail();

            $tramite->update([
                        'estado' => 'rechazado',
                        'observaciones' => $tramite->observaciones . '<|>' . $data['observaciones']
                    ]);

            (new TramiteService($tramite))->cambiarEstado('rechazado');

            return response()->json([
                'result' => 'success',
                'data' => []
            ], 200);

        } catch (\Throwable $th) {

            return response()->json([
                'result' => 'error',
                'data' => $th->getMessage(),
            ], 500);

        }

    }

}
