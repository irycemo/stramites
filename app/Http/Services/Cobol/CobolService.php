<?php

namespace App\Http\Services\Cobol;

use App\Models\Tramite;
use Illuminate\Support\Facades\Storage;


class CobolService{

    public function insertarS3(Tramite $tramite){

        $recibo_pago = (string) $tramite->documento_de_pago;

        if(strlen($recibo_pago) < 10){

            $recibo_pago = str_pad($recibo_pago, 10, '0', STR_PAD_LEFT);

            $recibo_pago[0] = "1";

        }

        $fecha_pago = $tramite->fecha_pago->format('Ymd');

        $usuario = str_pad($tramite->creadoPor->name , 30, ' ');

        $monto = str_pad($tramite->monto * 100, 14, '0', STR_PAD_LEFT);

        $fecha_entrega = $tramite->fecha_entrega->format('Ymd');

        $string = $recibo_pago . $fecha_pago . $usuario . $monto . $fecha_entrega;

        $nombre = $tramite->procedencia . $recibo_pago;

        Storage::disk('s3')->put('cobol/salida/'. $nombre, $string);

    }

}
