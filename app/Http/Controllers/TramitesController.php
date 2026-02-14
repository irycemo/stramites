<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGeneratorPNG;

class TramitesController extends Controller
{

    public function recibo(Tramite $tramite){

        $tramite->load('servicio');

        $generatorPNG = new BarcodeGeneratorPNG();

        $pdf = Pdf::loadView('tramites.recibo', compact('tramite', 'generatorPNG'));

        return $pdf->stream('recibo.pdf');
    }

    public function orden(Tramite $tramite){

        $tramite->load('servicio');

        $generatorPNG = new BarcodeGeneratorPNG();

        $pdf = Pdf::loadView('tramites.orden', compact('tramite', 'generatorPNG'));

        return $pdf->stream('orden.pdf');

    }

    public function ordenS3(Tramite $tramite){

        $tramite->load('servicio');

        $generatorPNG = new BarcodeGeneratorPNG();

        $pdf = Pdf::loadView('tramites.orden', compact('tramite', 'generatorPNG'));

        $pdfContent = $pdf->output();

        Storage::disk('s3')->put('bienestar/' . $tramite->año . '-' . $tramite->numero_control . '-' . $tramite->usuario . '.pdf', $pdfContent);

    }

}
