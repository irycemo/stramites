<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Tramite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\TramiteResource;
use App\Http\Requests\TramiteListRequest;
use App\Http\Requests\CrearTramiteRequest;
use App\Http\Services\Tramites\TramiteService;

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

    public function crearTramtie(CrearTramiteRequest $request){

        $validated = $request->validated();

        $tramite = Tramite::make();

        $tramite->tipo_tramite = $validated['tipo_tramite'];
        $tramite->tipo_servicio = $validated['tipo_servicio'];
        $tramite->id_servicio = $validated['servicio_id'];
        $tramite->solicitante = $validated['solicitante'];
        $tramite->nombre_solicitante = $validated['nombre_solicitante'];
        $tramite->monto = $validated['monto'];
        $tramite->cantidad = $validated['cantidad'];
        $tramite->usuario_tramites_linea_id = $validated['usuario_tramites_linea_id'];

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

}
