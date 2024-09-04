<?php

namespace App\Traits\Ventanilla;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Exceptions\SistemaRppServiceException;
use App\Models\Transicion;

trait ConsultaFolioTrait
{

    public function consultarFolioReal(){

        try {

            $response = Http::withToken(env('SISTEMA_RPP_SERVICE_TOKEN'))
                            ->accept('application/json')
                            ->asForm()
                            ->post(env('SISTEMA_RPP_SERVICE_CONSULTAR_FOLIO_REAL'),[
                                'folio_real' => $this->modelo_editar->folio_real,
                                'tomo' => $this->modelo_editar->tomo,
                                'registro' => $this->modelo_editar->registro,
                                'numero_propiedad' => $this->modelo_editar->numero_propiedad,
                                'distrito' => $this->modelo_editar->distrito,
                                'seccion' => $this->modelo_editar->seccion,
                            ]);

        } catch (\Throwable $th) {

            Log::error("Error al consultar folio real al crear trámite " . $th);

            throw new SistemaRppServiceException("Error al comunicar con Sistema RPP.");

        }

        $data = json_decode($response, true);

        if($response->status() == 200){

            $this->modelo_editar->folio_real = $data['data']['folio'];
            $this->modelo_editar->tomo = $data['data']['tomo'];
            $this->modelo_editar->registro = $data['data']['registro'];
            $this->modelo_editar->numero_propiedad = $data['data']['numero_propiedad'];
            $this->modelo_editar->distrito = $data['data']['distrito'];
            $this->modelo_editar->seccion = $data['data']['seccion'];

        }elseif($response->status() == 401){

            throw new Exception($data['error'] ?? "Hubo un error.");

        }elseif($response->status() == 403){

            throw new Exception($data['error'] ?? 'Hubo un error');

        }elseif($response->status() == 404){

            throw new Exception("El folio real no existe.");

        }elseif($response->status() == 500){

            throw new Exception("Hubo un error al consultar el folio real.");

        }

        if($this->modelo_editar->tomo && $this->modelo_editar->registro && $this->modelo_editar->numero_propiedad && $this->modelo_editar->distrito && $this->modelo_editar->seccion){

            $transicion = Transicion::where('tomo', $this->modelo_editar->tomo)
                                        ->where('registro', $this->modelo_editar->registro)
                                        ->where('numero_propiedad', $this->modelo_editar->numero_propiedad)
                                        ->where('distrito', $this->modelo_editar->distrito)
                                        ->where('seccion', $this->modelo_editar->seccion)
                                        ->first();

            if($transicion){

                throw new Exception("La propiedad de encuentra en transición.");

            }

        }

    }


}
