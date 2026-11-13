<?php

namespace App\Http\Services\SistemaTramitesLinea;

use Illuminate\Support\Facades\Http;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Log;

class SistemaTramitesLineaService{

    public function consultarAvisoPreventivo(int $tramite_id, string $tipo_inscripcion):array
    {

        $response = Http::withToken(config('services.sistema_tramites_linea.token'))
                            ->accept('application/json')
                            ->asForm()
                            ->post(
                                config('services.sistema_tramites_linea.consultar_aviso_preventivo'),
                                [
                                    'tramite_id' => $tramite_id,
                                    'tipo_inscripcion' => $tipo_inscripcion,
                                ]
                            );

        if($response->status() !== 200){

            Log::info("Error al consultar documento de entrada desde sistema trámites en línea. " . $response);

            $data = json_decode($response, true);

            if(isset($data['error'])){

                throw new GeneralException($data['error']);

            }

            throw new GeneralException("Error al consultar documento de entrada desde sistema trámites en línea.");

        }else{

            return json_decode($response, true)['data'];

        }

    }

    public function actualizarMovimientoRegistral(int $tramite_id, int $movimiento_registral_id):array
    {

        $response = Http::withToken(config('services.sistema_tramites_linea.token'))
                            ->accept('application/json')
                            ->asForm()
                            ->post(
                                config('services.sistema_tramites_linea.actualizar_movimiento_registral'),
                                [
                                    'movimiento_registral_id' => $movimiento_registral_id,
                                    'tramite_id' => $tramite_id
                                ]
                            );

        if($response->status() !== 200){

            Log::info("Error al actualizar movimiento registral en sistema trámites en línea. " . $response);

            $data = json_decode($response, true);

            if(isset($data['error'])){

                throw new GeneralException($data['error']);

            }

            throw new GeneralException("Error al actualizar movimiento registral en sistema trámites en línea.");

        }else{

            return json_decode($response, true)['data'];

        }

    }

}
