<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Servicio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServicioResource;

class ServiciosApiController extends Controller
{

    public function consultarServicios(Request $request){

        $validated = $request->validate(['ids' => 'required|array']);

        return ServicioResource::collection(Servicio::find($validated['ids']))->response()->setStatusCode(200);

    }

}
