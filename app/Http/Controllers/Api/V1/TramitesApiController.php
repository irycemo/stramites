<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Tramite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\TramiteRequest;
use App\Http\Resources\TramiteResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\TramiteListRequest;
use App\Http\Requests\CrearTramiteRequest;
use App\Http\Services\Tramites\TramiteService;
use App\Exceptions\ErrorAlValidarLineaDeCaptura;

class TramitesApiController extends Controller
{

    public function consultarTramites(TramiteListRequest $request){

        $validated = $request->validated();

        $tramites = Tramite::with('servicio')
                                ->where('usuario', 67)
                                ->where('usuario_tramites_linea_id', $validated['entidad'])
                                ->when(isset($validated['año']), fn($q) => $q->where('año', $validated['año']))
                                ->when(isset($validated['folio']), fn($q) => $q->where('folio', $validated['folio']))
                                ->when(isset($validated['estado']), fn($q) => $q->where('estado', $validated['estado']))
                                ->when(isset($validated['tipo_servicio']), fn($q) => $q->where('tipo_servicio', $validated['tipo_servicio']))
                                ->when(isset($validated['servicio']), fn($q) => $q->where('id_servicio', $validated['servicio']))
                                ->orderBy('id', 'desc')
                                ->paginate($validated['pagination'], ['*'], 'page', $validated['pagina']);

        return TramiteResource::collection($tramites)->response()->setStatusCode(200);

    }

    public function crearTramite(CrearTramiteRequest $request){

        $validated = $request->validated();

        $tramite = Tramite::make();

        $tramite->tipo_tramite = 'normal';
        $tramite->tipo_servicio = $validated['tipo_servicio'];
        $tramite->id_servicio = $validated['servicio_id'];
        $tramite->solicitante = $validated['solicitante'];
        $tramite->nombre_solicitante = $validated['nombre_solicitante'];
        $tramite->monto = $validated['monto'];
        $tramite->cantidad = 1;
        $tramite->usuario_tramites_linea_id = $validated['usuario_tramites_linea_id'];

        if(isset($validated['predio'])){

            if(isset($validated['predio']['folio_real'])){

                $tramite->folio_real = $validated['predio']['folio_real'];

            }

            if(isset($validated['predio']['tomo'])){

                $tramite->tomo = $validated['predio']['tomo'];

            }

            if(isset($validated['predio']['registro'])){

                $tramite->registro = $validated['predio']['registro'];

            }

            if(isset($validated['predio']['numero_propiedad'])){

                $tramite->numero_propiedad = $validated['predio']['numero_propiedad'];

            }

            $tramite->distrito = $validated['predio']['distrito'];
            $tramite->seccion = 'Propiedad';

        }

        $nuevo_tramite = null;

        try {

            DB::transaction(function () use($tramite, &$nuevo_tramite){

                $nuevo_tramite = (new TramiteService($tramite))->crear();

            }, 10);

            return (new TramiteResource($nuevo_tramite))->response()->setStatusCode(200);

        } catch (\Throwable $th) {

            Log::error("Error al crear trámite por el Sistema de trámties en linea" . $th);

            return response()->json([
                'error' => "No se pudo crear el trámite.",
            ], 500);

        }

    }

    public function acreditarTramite(Request $request){

        $validated = $request->validate(['linea_captura' => 'required']);

        $tramite = Tramite::where('linea_de_captura', $validated['linea_captura'])->first();

        if(!$tramite){

            return response()->json([
                'error' => "Trámite no encontrado.",
            ], 404);

        }

        try {

            (new TramiteService($tramite))->procesarPago();

            return (new TramiteResource($tramite))->response()->setStatusCode(200);

        } catch (ErrorAlValidarLineaDeCaptura $th) {

            if(!$tramite){

                return response()->json([
                    'error' => $th->getMessage(),
                ], 500);

            }

        } catch (\Throwable $th) {

            Log::error("Error al acreditar pago api. " . $th);

            return response()->json([
                'error' => 'Error al acreditar pago.',
            ], 500);

        }

    }

    public function finalizarTramite(TramiteRequest $request){

        try {

            $data = $request->validated();

            $tramite = Tramite::where('año', $data['año'])->where('numero_control', $data['tramite'])->where('usuario', $data['usuario'])->firstOrFail();

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

    public function rechazarTramite(TramiteRequest $request){

        try {

            $data = $request->validated();

            $tramite = Tramite::where('año', $data['año'])->where('numero_control', $data['tramite'])->where('usuario', $data['usuario'])->first();

            if(!$tramite){

                return response()->json([
                    'result' => 'error',
                    'data' => 'El trámite no existe',
                ], 404);

            }

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

            Log::error('Error al rechazar tramite desde sistema RPP.' . $th);

            return response()->json([
                'result' => 'error',
                'data' => $th->getMessage(),
            ], 500);

        }

    }

    public function consultarArchivo(Request $request){

        $tramite = Tramite::where('año', $request['año'])
                            ->where('numero_control', $request['tramite'])
                            ->where('usuario', $request['usuario'])
                            ->first();

        if(!$tramite){

            abort(404, 'Page not found');

        }

        if(!$tramite->file){

            abort(404, 'Page not found');

        }

        if(env('LOCAL') === "0" || env('LOCAL') === "2"){

            return response()->json([
                'url' => Storage::disk('tramites')->url($tramite->file->url)
            ], 200);

        }elseif(env('LOCAL') === "1"){

            return response()->json([
                'url' => Storage::disk('s3')->url('tramites/' . $tramite->file->url)
            ], 200);

        }

    }

}
