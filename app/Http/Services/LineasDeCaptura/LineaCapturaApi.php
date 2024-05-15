<?php

namespace App\Http\Services\LineasDeCaptura;

use App\Models\Tramite;
use App\Constantes\Constantes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Exceptions\ErrorAlGenerarLineaDeCaptura;
use App\Exceptions\ErrorAlValidarLineaDeCaptura;

class LineaCapturaApi
{

    public $soapUserApi;
    public $soapPasswordApi;
    public $tramite;

    public function __construct(Tramite $tramite)
    {

        $this->soapUserApi = env('SAP_USUARIO_API');

        $this->soapPasswordApi = env('SAP_CONTRASENA_API');

        $this->tramite = $tramite;

    }

    public function generarLineaDeCaptura(){

        $url = env('SAP_GENERAR_LINEA_DE_CAPTURA_URL');

        if($this->tramite->servicio->categoria->nombre === 'Comercio Inscripciones'){

            $observaciones = "Número de control: " . $this->tramite->año . '-' . $this->tramite->numero_control . '-' . $this->tramite->usuario .  " Tipo de servicio: " . $this->tramite->tipo_servicio  . ' Fecha de entrega: ' . $this->tramite->fecha_entrega . ' pagando el ' . now()->format('d-m-Y');

        }else{

            $observaciones = "Número de control: " . $this->tramite->año . '-' . $this->tramite->numero_control . '-' . $this->tramite->usuario . " Tomo: " . $this->tramite->tomo . " Registro: " . $this->tramite->registro . " Distrito: " . Constantes::DISTRITOS[$this->tramite->distrito] . " Sección: " . $this->tramite->seccion . " Tipo de servicio: " . $this->tramite->tipo_servicio  . ' Fecha de entrega: ' . $this->tramite->fecha_entrega . ' pagando el ' . now()->format('d-m-Y');
        }

        try {

            $response = Http::withBasicAuth($this->soapUserApi, $this->soapPasswordApi)->post($url, [
                "MT_ServGralLC_PI_Sender" => [
                    "ES_GEN_DATA" => [
                        "TP_PROCESAMIENTO" => "2",
                        "TP_DATOMAESTRO" => "E",
                        "TP_DIVERSO" => "RPP",
                        "RFC" => "XXXX0001XXX",
                        "NOMBRE_RAZON" => $this->tramite->nombre_solicitante,
                        "OBSERVACIONES" => $observaciones,
                    ],
                    "TB_CONCEPTOS" => [
                        "TP_INGRESO" => $this->tramite->servicio->clave_ingreso,
                        "CANTIDAD" => $this->tramite->cantidad,
                        "IMPORTE" => round($this->tramite->monto, 2)
                    ]
                ]
            ]);

        } catch (\Throwable $th) {

            Log::error($th);

            throw new ErrorAlGenerarLineaDeCaptura("Error de comunicación con SAP.");

            return;

        }

        $data = json_decode($response, true);

        if(isset($data['ES_MSJ']['TpMens'])){

            Log::error($data['ES_MSJ']['V1Mens'] . ' EN SAP');

            throw new ErrorAlGenerarLineaDeCaptura("Error de comunicación con SAP.");

            return;

        }

        if(isset($data['ERROR'])){

            Log::error($data['ERROR'] . ' SAP');

            throw new ErrorAlGenerarLineaDeCaptura("Error de comunicación con SAP.");

            return;

        }

        return $data;

    }

    public function validarLineaDeCaptura(){

        $url = env('SAP_VALIDAR_LINEA_DE_CAPTURA_URL');

        try {

            $response = Http::withBasicAuth($this->soapUserApi, $this->soapPasswordApi)->get($url .'/' . $this->tramite->linea_de_captura);

        } catch (\Throwable $th) {

            Log::error($th);

            throw new ErrorAlValidarLineaDeCaptura("Error de comunicación con SAP.");

            return;

        }

        $data = json_decode($response, true);

        if(isset($data['ES_MSJ'])){

            Log::error($data['ES_MSJ']['V1_MENS'] . ' EN SAP');

            throw new ErrorAlValidarLineaDeCaptura($data['ES_MSJ']['V1_MENS'] ." en SAP.");

            return;

        }

        if(isset($data['ERROR'])){

            Log::error($data['ERROR'] . ' SAP');

            throw new ErrorAlValidarLineaDeCaptura("Error de comunicación con SAP.");

            return;

        }

        return $data;

    }

}
