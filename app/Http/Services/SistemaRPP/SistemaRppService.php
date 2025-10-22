<?php

namespace App\Http\Services\SistemaRPP;

use App\Models\Tramite;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Exceptions\SistemaRppServiceException;
use Illuminate\Http\Client\ConnectionException;

class SistemaRppService{

    public $token;

    public function __construct()
    {

        $this->token = env('SISTEMA_RPP_SERVICE_TOKEN');

    }

    public function insertarSistemaRpp($tramite){

        $url = env('SISTEMA_RPP_SERVICE_INSERT');

        try {

            $response = Http::withToken($this->token)->accept('application/json')->asForm()->post($url,[
                'monto' => $tramite->monto,
                'solicitante' => $tramite->solicitante,
                'nombre_solicitante' => $tramite->nombre_solicitante,
                'año' => $tramite->año,
                'tramite' => $tramite->numero_control,
                'usuario' => $tramite->usuario,
                'fecha_prelacion' => $tramite->fecha_prelacion,
                'tipo_servicio' => $tramite->tipo_servicio,
                'tipo_tramite' => $tramite->tipo_tramite,
                'seccion' => $tramite->seccion,
                'distrito' => $tramite->distrito,
                'fecha_entrega' => $tramite->fecha_entrega->toDateString(),
                'fecha_pago' => $tramite->fecha_pago?->toDateString(),
                'categoria_servicio' => $tramite->servicio->categoria->nombre,
                'servicio' => $tramite->servicio->clave_ingreso,
                'servicio_nombre' => $tramite->servicio->nombre,
                'numero_oficio' => $tramite->numero_oficio,
                'folio_real' => $tramite->folio_real,
                'folio_real_persona_moral' => $tramite->folio_real_persona_moral,
                'tomo' => $tramite->tomo,
                'tomo_bis' => $tramite->tomo_bis,
                'registro' => $tramite->registro,
                'registro_bis' => $tramite->registro_bis,
                'tomo_gravamen' => $tramite->tomo_gravamen,
                'registro_gravamen' => $tramite->registro_gravamen,
                'observaciones' => $tramite->observaciones,
                'numero_paginas' => $tramite->cantidad,
                'numero_inmuebles' => $tramite->numero_inmuebles,
                'numero_propiedad' => $tramite->numero_propiedad,
                'numero_escritura' => $tramite->numero_escritura,
                'numero_notaria' => $tramite->numero_notaria,
                'valor_propiedad' => $tramite->valor_propiedad,
                'tipo_documento' => $tramite->tipo_documento,
                'autoridad_cargo' => $tramite->autoridad_cargo,
                'autoridad_nombre' => $tramite->nombre_autoridad,
                'numero_documento' => $tramite->numero_documento,
                'fecha_emision' => $tramite->fecha_emision,
                'procedencia' => $tramite->procedencia,
                'asiento_registral' => $tramite->asiento_registral,
                'usuario_tramites_linea_id' => $tramite->usuario_tramites_linea_id
            ]);

        } catch (ConnectionException $th) {

            Log::error($th);

            throw new SistemaRppServiceException("Error al comunicar con SistemaRPP.");

            return;

        }

        $data = json_decode($response, true);

        if($response->status() == 200){

            $tramite = Tramite::find($tramite->id);

            if(!$tramite){

                throw new SistemaRppServiceException('Error al actualizar movimiento registral del tramite: ' . $tramite->año . '-' . $tramite->numero_control . '-' . $tramite->usuario . '.');

            }

            $tramite->update(['movimiento_registral' => $data['data']['id']]);

            if($tramite->adicionaAlTramite && $tramite->adicionaAlTramite->servicio->clave_ingreso == 'DC93')
                $tramite->adicionaAlTramite->update(['movimiento_registral' => $data['data']['id']]);

            return $data['usuario_asignado'];

        }else{

            Log::error("Error al insertar trámite en Sistema RPP. por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". Trámite: " . $tramite->año . '-' . $tramite->numero_control . '-' . $tramite->usuario . '. ' . $response);

            throw new SistemaRppServiceException("Error al insertar trámite en Sistema RPP.");

        }

    }

    public function actualizarSistemaRpp($tramite){

        $url = env('SISTEMA_RPP_SERVICE_UPDATE');

        try {

            $response = Http::withToken($this->token)->accept('application/json')->asForm()->post($url,[
                'solicitante' => $tramite->solicitante,
                'nombre_solicitante' => $tramite->nombre_solicitante,
                'tipo_servicio' => $tramite->tipo_servicio,
                'tipo_tramite' => $tramite->tipo_tramite,
                'seccion' => $tramite->seccion,
                'observaciones' => $tramite->observaciones,
                'distrito' => $tramite->distrito,
                'categoria_servicio' => $tramite->servicio->categoria->nombre,
                'servicio' => $tramite->servicio->clave_ingreso,
                'numero_oficio' => $tramite->numero_oficio,
                'folio_real' => $tramite->folio_real,
                'tomo' => $tramite->tomo,
                'tomo_bis' => $tramite->tomo_bis,
                'registro' => $tramite->registro,
                'registro_bis' => $tramite->registro_bis,
                'numero_paginas' => $tramite->cantidad,
                'numero_inmuebles' => $tramite->numero_inmuebles,
                'numero_propiedad' => $tramite->numero_propiedad,
                'numero_escritura' => $tramite->numero_escritura,
                'numero_notaria' => $tramite->numero_notaria,
                'valor_propiedad' => $tramite->valor_propiedad,
                'movimiento_registral' => $tramite->movimiento_registral,
                'tipo_documento' => $tramite->tipo_documento,
                'autoridad_cargo' => $tramite->autoridad_cargo,
                'autoridad_nombre' => $tramite->nombre_autoridad,
                'numero_documento' => $tramite->numero_documento,
                'fecha_emision' => $tramite->fecha_emision,
                'procedencia' => $tramite->procedencia,
                'tomo_gravamen' => $tramite->tomo_gravamen,
                'registro_gravamen' => $tramite->registro_gravamen,
                'asiento_registral' => $tramite->asiento_registral
            ]);

        } catch (\Throwable $th) {

            Log::error("Error al actualizar en Sistema RPP el trámite: " . $tramite->año . '-' . $tramite->numero_control . '-' . $tramite->usuario . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

            throw new SistemaRppServiceException("Error al comunicar con SistemaRPP.");

        }

        if($response->status() != 200){

            Log::error("Error al actualizar en Sistema RPP el trámite: " . $tramite->año . '-' . $tramite->numero_control . '-' . $tramite->usuario . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $response);

            throw new SistemaRppServiceException("Error al actualizar información en Sistema RPP");

        }

    }

    public function cambiarTipoServicio($tramite){

        $url = env('SISTEMA_RPP_SERVICE_UPDATE_SERVICE');

        try {

            $response = Http::withToken($this->token)->accept('application/json')->asForm()->post($url,[
                'tipo_servicio' => $tramite->tipo_servicio,
                'monto' => $tramite->monto,
                'movimiento_registral' => $tramite->movimiento_registral,
            ]);

        } catch (\Throwable $th) {

            Log::error("Error al actualizar tipo de servicio en Sistema RPP el trámite: " . $tramite->año . '-' . $tramite->numero_control . '-' . $tramite->usuario . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

            throw new SistemaRppServiceException("Error al comunicar con SistemaRPP.");

        }

        if($response->status() != 200){

            Log::error("Error al actualizar tipo de servicio en Sistema RPP el trámite: " . $tramite->año . '-' . $tramite->numero_control . '-' . $tramite->usuario . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". ". $response);

            throw new SistemaRppServiceException("Error al cambiar tipo de servicio en Sistema RPP.");

        }

    }

    public function actualizarPaginas($tramite){

        $url = env('SISTEMA_RPP_SERVICE_UPDATE_PAGES');

        try {

            $response = Http::withToken($this->token)->accept('application/json')->asForm()->post($url,[
                'numero_paginas' => $tramite->cantidad,
                'monto' => $tramite->monto,
                'movimiento_registral' => $tramite->movimiento_registral,
                'tipo_servicio' => $tramite->tipo_servicio,
            ]);

        } catch (\Throwable $th) {

            Log::error("Error al actualizar páginas en Sistema RPP el trámite: " . $tramite->año . '-' . $tramite->numero_control . '-' . $tramite->usuario . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

            throw new SistemaRppServiceException("Error al comunicar con SistemaRPP.");

        }

        if($response->status() != 200){

            Log::error("Error al actualizar páginas en Sistema RPP el trámite: " . $tramite->año . '-' . $tramite->numero_control . '-' . $tramite->usuario . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". ". $response);

            throw new SistemaRppServiceException("Error al actualizar número de páginas en Sistema RPP.");

        }

    }

}
