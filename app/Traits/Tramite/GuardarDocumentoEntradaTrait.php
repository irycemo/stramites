<?php

namespace App\Traits\Tramite;

use Illuminate\Support\Str;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

trait GuardarDocumentoEntradaTrait
{

    public function guardarDocumentoEntrada($tramite_id, $data){

        $pdfContent = file_get_contents($data['documento_entrada_url']);

        $nombre_temp = Str::random(40) . '.pdf';

        if(app()->isProduction()){

                Storage::disk('s3')->put(config('services.ses.ruta_documento_entrada') . $nombre_temp, $pdfContent);

            }else{

                Storage::put('tramites/' . $nombre_temp, $pdfContent);

            }

        File::create([
            'fileable_id' => $tramite_id,
            'fileable_type' => 'App\Models\Tramite',
            'url' => $nombre_temp
        ]);

    }

}
