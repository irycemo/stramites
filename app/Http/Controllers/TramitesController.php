<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\TramiteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGeneratorPNG;

class TramitesController extends Controller
{

    public function recibo(Tramite $tramite){

        $tramite->load('servicio');

        $pdf = Pdf::loadView('tramites.recibo', compact('tramite'));

        return $pdf->stream('recibo.pdf');
    }

    public function orden(Tramite $tramite){

        $tramite->load('servicio');

        $generatorPNG = new BarcodeGeneratorPNG();

        $pdf = Pdf::loadView('tramites.orden', compact('tramite', 'generatorPNG'));

        return $pdf->stream('orden.pdf');

    }

}
