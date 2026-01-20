<?php

namespace App\Http\Services\SistemaRPP;

use Illuminate\Support\Facades\Log;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Http;

class SistemaRppService{

    public function insertarSistemaRpp($tramite){

        $response = Http::withToken(config('services.sistema_rpp.token'))
                            ->accept('application/json')
                            ->asForm()
                            ->post(
                                config('services.sistema_rpp.insertar_movimiento_registral'),
                                [
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
                                ]
                            );

        if($response->status() !== 200){

            Log::error("Error al insertar en Sistema RPP el trámite: " . $tramite->año . '-' . $tramite->numero_control . '-' . $tramite->usuario . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $response);

            $data = json_decode($response, true);

            if(isset($data['error'])){

                throw new GeneralException($data['error']);

            }

            throw new GeneralException("Error al insertar trámite en Sistema RPP.");

        }else{

            $data = json_decode($response, true)['data'];

            $tramite->update(['movimiento_registral' => $data['id']]);

            if($tramite->adicionaAlTramite && $tramite->adicionaAlTramite->servicio->clave_ingreso == 'DC93'){

                $tramite->adicionaAlTramite->update(['movimiento_registral' => $data['id']]);

            }

            return $data['usuario_asignado'];

        }

    }

    public function actualizarSistemaRpp($tramite){

        $response = Http::withToken(config('services.sistema_rpp.token'))
                            ->accept('application/json')
                            ->asForm()
                            ->post(
                                config('services.sistema_rpp.actualizar_movimiento_registral'),
                                [
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
                                ]
                            );

        if($response->status() !== 200){

            Log::error("Error al actualizar en Sistema RPP el trámite: " . $tramite->año . '-' . $tramite->numero_control . '-' . $tramite->usuario . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $response);

            $data = json_decode($response, true);

            if(isset($data['error'])){

                throw new GeneralException($data['error']);

            }

            throw new GeneralException("Error al actualizar trámite en Sistema RPP.");

        }

    }

    public function cambiarTipoServicio($tramite){

        $response = Http::withToken(config('services.sistema_rpp.token'))
                            ->accept('application/json')
                            ->asForm()
                            ->post(
                                config('services.sistema_rpp.actualizar_movimiento_registral'),
                                [
                                    'categoria_servicio' => $tramite->servicio->categoria->nombre,
                                    'tipo_servicio' => $tramite->tipo_servicio,
                                    'monto' => $tramite->monto,
                                    'movimiento_registral' => $tramite->movimiento_registral,
                                ]
                            );

        if($response->status() !== 200){

            Log::error("Error al actualizar tipo de servicio en Sistema RPP el trámite: " . $tramite->año . '-' . $tramite->numero_control . '-' . $tramite->usuario . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $response);

            $data = json_decode($response, true);

            if(isset($data['error'])){

                throw new GeneralException($data['error']);

            }

            throw new GeneralException("Error al actualizar tipo de servicio trámite en Sistema RPP.");

        }

    }

    public function actualizarPaginas($tramite){

        $response = Http::withToken(config('services.sistema_rpp.token'))
                            ->accept('application/json')
                            ->asForm()
                            ->post(
                                config('services.sistema_rpp.actualizar_paginas'),
                                [
                                    'numero_paginas' => $tramite->cantidad,
                                    'monto' => $tramite->monto,
                                    'movimiento_registral' => $tramite->movimiento_registral,
                                    'tipo_servicio' => $tramite->tipo_servicio,
                                ]
                            );

        if($response->status() !== 200){

            Log::error("Error al actualizar páginas en Sistema RPP el trámite: " . $tramite->año . '-' . $tramite->numero_control . '-' . $tramite->usuario . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $response);

            $data = json_decode($response, true);

            if(isset($data['error'])){

                throw new GeneralException($data['error']);

            }

            throw new GeneralException("Error al actualizar páginas trámite en Sistema RPP.");

        }

    }

    public function consultarGravamen($tramite){

        $response = Http::withToken(config('services.sistema_rpp.token'))
                            ->accept('application/json')
                            ->asForm()
                            ->post(
                                config('services.sistema_rpp.consultar_gravamen'),
                                [
                                    'folio_real' => $tramite->folio_real,
                                    'folio' => $tramite->asiento_registral,
                                    'tomo_gravamen' => $tramite->tomo_gravamen,
                                    'registro_gravamen' => $tramite->registro_gravamen,
                                    'distrito' => $tramite->distrito,
                                    'seccion' => $tramite->seccion,
                                ]
                            );

        if($response->status() !== 200){

            Log::error("Error al consultar gravamen en Sistema RPP el trámite: " . $tramite->año . '-' . $tramite->numero_control . '-' . $tramite->usuario . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $response);

            $data = json_decode($response, true);

            if(isset($data['error'])){

                throw new GeneralException($data['error']);

            }

            throw new GeneralException("Error al consultar gravamen trámite en Sistema RPP.");

        }else{

            $data = json_decode($response, true);

            return $data;

        }

    }

    public function consultarFolioReal($tramite){

        $response = Http::withToken(config('services.sistema_rpp.token'))
                            ->accept('application/json')
                            ->asForm()
                            ->post(
                                config('services.sistema_rpp.consultar_folio_real'),
                                [
                                    'folio_real' => $tramite->folio_real,
                                    'tomo' => $tramite->tomo,
                                    'registro' => $tramite->registro,
                                    'numero_propiedad' => $tramite->numero_propiedad,
                                    'distrito' => $tramite->distrito,
                                    'seccion' => $tramite->seccion,
                                ]
                            );

        if($response->status() !== 200){

            Log::error("Error al consultar folio real en Sistema RPP el trámite: " . $tramite->año . '-' . $tramite->numero_control . '-' . $tramite->usuario . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $response);

            $data = json_decode($response, true);

            if(isset($data['error'])){

                throw new GeneralException($data['error']);

            }

            throw new GeneralException("Error al consultar folio real trámite en Sistema RPP.");

        }else{

            $data = json_decode($response, true);

            return $data;

        }

    }

    public function consultarFolioRealPersonaMoral($tramite){

        $response = Http::withToken(config('services.sistema_rpp.token'))
                            ->accept('application/json')
                            ->asForm()
                            ->post(
                                config('services.sistema_rpp.consultar_folio_real_peresona_moral'),
                                [
                                    'folio_real' => $tramite->folio_real_persona_moral,
                                    'tomo' => $tramite->tomo,
                                    'registro' => $tramite->registro,
                                    'distrito' => $tramite->distrito,
                                ]
                            );

        if($response->status() !== 200){

            Log::error("Error al consultar folio real de persona moral en Sistema RPP el trámite: " . $tramite->año . '-' . $tramite->numero_control . '-' . $tramite->usuario . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $response);

            $data = json_decode($response, true);

            if(isset($data['error'])){

                throw new GeneralException($data['error']);

            }

            throw new GeneralException("Error al consultar folio real de persona moral trámite en Sistema RPP.");

        }else{

            $data = json_decode($response, true);

            return $data;

        }

    }

    public function consultarFolioMovimiento($tramite){

        $response = Http::withToken(config('services.sistema_rpp.token'))
                            ->accept('application/json')
                            ->asForm()
                            ->post(
                                config('services.sistema_rpp.consultar_folio_movimiento'),
                                [
                                    'folio_real' => $tramite->folio_real,
                                    'asiento_registral' => $tramite->asiento_registral,
                                ]
                            );

        if($response->status() !== 200){

            Log::error("Error al consultar folio de movimiento registral en Sistema RPP el trámite: " . $tramite->año . '-' . $tramite->numero_control . '-' . $tramite->usuario . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $response);

            $data = json_decode($response, true);

            if(isset($data['error'])){

                throw new GeneralException($data['error']);

            }

            throw new GeneralException("Error al consultar folio de movimiento registral trámite en Sistema RPP.");

        }else{

            $data = json_decode($response, true);

            return $data;

        }

    }

    public function consultarAntecedentes($tramite){

        $response = Http::withToken(config('services.sistema_rpp.token'))
                            ->accept('application/json')
                            ->asForm()
                            ->post(
                                config('services.sistema_rpp.consultar_antecedentes'),
                                [
                                    'tomo' => $tramite->tomo,
                                    'registro' => $tramite->registro,
                                    'distrito' => $tramite->distrito,
                                ]
                            );

        if($response->status() !== 200){

            Log::error("Error al consultar antecedentes en Sistema RPP el trámite: " . $tramite->año . '-' . $tramite->numero_control . '-' . $tramite->usuario . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $response);

            $data = json_decode($response, true);

            if(isset($data['error'])){

                throw new GeneralException($data['error']);

            }

            throw new GeneralException("Error al consultar antecedentes trámite en Sistema RPP.");

        }else{

            $data = json_decode($response, true);

            return $data;

        }

    }

    public function consultarPrimerAvisoPreventivo($tramite){

        $response = Http::withToken(config('services.sistema_rpp.token'))
                            ->accept('application/json')
                            ->asForm()
                            ->post(
                                config('services.sistema_rpp.consultar_primer_aviso_preventivo'),
                                [
                                    'folio_real' => $tramite->folio_real,
                                    'folio' => $tramite->asiento_registral
                                ]
                            );

        if($response->status() !== 200){

            Log::error("Error al consultar primer aviso preventivo en Sistema RPP el trámite: " . $tramite->año . '-' . $tramite->numero_control . '-' . $tramite->usuario . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $response);

            $data = json_decode($response, true);

            if(isset($data['error'])){

                throw new GeneralException($data['error']);

            }

            throw new GeneralException("Error al consultar primer aviso preventivo trámite en Sistema RPP.");

        }

    }

    public function consultarSegundoAvisoPreventivo($tramite){

        $response = Http::withToken(config('services.sistema_rpp.token'))
                            ->accept('application/json')
                            ->asForm()
                            ->post(
                                config('services.sistema_rpp.consultar_primer_aviso_preventivo'),
                                [
                                    'folio_real' => $tramite->folio_real,
                                    'folio' => $tramite->asiento_registral
                                ]
                            );

        if($response->status() !== 200){

            Log::error("Error al consultar segundo aviso preventivo en Sistema RPP el trámite: " . $tramite->año . '-' . $tramite->numero_control . '-' . $tramite->usuario . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $response);

            $data = json_decode($response, true);

            if(isset($data['error'])){

                throw new GeneralException($data['error']);

            }

            throw new GeneralException("Error al consultar segundo aviso preventivo trámite en Sistema RPP.");

        }

    }

}
