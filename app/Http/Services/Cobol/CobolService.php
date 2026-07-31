<?php

namespace App\Http\Services\Cobol;

use App\Models\Tramite;
use Illuminate\Support\Facades\Storage;


class CobolService{

    public function insertarS3(Tramite $tramite){

        $usuario = str_pad($tramite->creadoPor->name , 30, ' ');

        $string = $tramite->orden_de_pago . $tramite->fecha_pago->format('Ymd') . $usuario . ($tramite->monto * 100) . $tramite->fecha_entrega->format('Ymd');

        $nombre = $tramite->procedencia . $tramite->orden_de_pago . '.TXT';

        Storage::disk('s3')->put('cobol/salida/'. $nombre, $string);

    }

}