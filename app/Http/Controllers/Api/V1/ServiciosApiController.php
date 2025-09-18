<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Tramite;
use App\Models\Servicio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServicioResource;

class ServiciosApiController extends Controller
{

    public function consultarServicios(Request $request){

        $validated = $request->validate(['claves_ingreso' => 'required|array']);

        return ServicioResource::collection(Servicio::where('estado', 'activo')->whereIn('clave_ingreso', $validated['claves_ingreso'])->get())->response()->setStatusCode(200);

    }

    public function consultarServicio(Request $request){

        $validated = $request->validate([
            'ano' => 'required',
            'numero_control' => 'required',
            'usuario' => 'required',
        ]);

        $tramite = Tramite::where('año', $validated['ano'])
                                ->where('numero_control', $validated['numero_control'])
                                ->where('usuario', $validated['usuario'])
                                ->first();

        if($tramite){

            return response()->json([
                'nombre' => $tramite->servicio->nombre
            ], 200);

        }

    }

}
