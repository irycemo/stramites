<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Services\Cobol\CobolService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Services\Tramites\TramiteService;
use App\Models\Tramite;

class RevisarPagoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'revisar-pago';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tarea programada para checar pago en sap para tramties del Sistema de Tramites en Linea';

    public function handle()
    {

        $tramites = Tramite::with('servicio.categoria', 'adicionaAlTramite')
                                ->where('estado', 'nuevo')
                                ->get();

        try {

            foreach($tramites as $tramite){

                $data = $this->validarLineaDeCaptura($tramite->linea_de_captura);

                if(isset($data['FEC_PAGO'])){

                    (new TramiteService($tramite))->procesarPago();

                    info('Tramite validado mediante tarea programada: ' . $tramite->año . '-' . $tramite->numero_control . '-' . $tramite->usuario);

                }

            }

            Log::info("Tarea programada para checar pago de tramites finalizada con éxito.");

        } catch (\Throwable $th) {

            Log::error("Error al revisar pago de tramites en tarea programada. " . $th);

        }

    }

    public function validarLineaDeCaptura($linea_captura){

        $url = config('services.sap.SAP_VALIDAR_LINEA_DE_CAPTURA_URL');

        $response = Http::withBasicAuth(config('services.sap.SAP_USUARIO_API'), config('services.sap.SAP_CONTRASENA_API'))->get($url .'/' . $linea_captura);

        $data = json_decode($response, true);

        return $data;

    }

}
