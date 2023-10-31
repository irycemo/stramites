<?php

namespace App\Http\Services\Tramites;

use App\Models\Tramite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Exceptions\TramiteServiceException;
use App\Exceptions\SistemaRppServiceException;
use App\Exceptions\ErrorAlGenerarLineaDeCaptura;
use App\Exceptions\ErrorAlValidarLineaDeCaptura;
use App\Http\Services\LineasDeCaptura\LineaCaptura;
use App\Http\Services\SistemaRPP\SistemaRppService;

class TramiteService{

    public $tramite;
    public $fecha_vencimiento;
    public $orden_de_pago;
    public $linea;

    public function __construct(Tramite $tramite)
    {

        $this->tramite = $tramite;

    }

    public function crear():Tramite
    {

        try {

            $this->procesarLineaCaptura();

            $this->tramite->limite_de_pago = $this->fecha_vencimiento;
            $this->tramite->orden_de_pago = $this->orden_de_pago;
            $this->tramite->linea_de_captura = $this->linea;
            $this->tramite->estado = 'nuevo';
            $this->tramite->fecha_entrega = $this->calcularFechaEntrega();
            $this->tramite->monto = $this->calcularMonto();

            $this->tramite->save();

            return $this->tramite;

        } catch (ErrorAlGenerarLineaDeCaptura $th) {

            throw new TramiteServiceException($th->getMessage());

        } catch (\Throwable $th) {

            Log::error("Error al crear trámite por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". Trámite: " . $this->tramite->año . '-' . $this->tramite->numero_control . '. ' . $th);

            throw new TramiteServiceException("Error al crear trámite");
        }

    }

    public function actualizar():void
    {

        try {

            $this->tramite->actualizado_por = auth()->user()->id;
            $this->tramite->save();

            if($this->tramite->fecha_pago || $this->tramite->solicitante == 'Oficialia de partes')
                (new SistemaRppService())->actualizarSistemaRpp($this->tramite);

        } catch (SistemaRppServiceException $th) {

            throw new TramiteServiceException($th->getMessage());

        } catch (\Throwable $th) {

            Log::error("Error al actualizar trámite por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". Trámite: " . $this->tramite->año . '-' . $this->tramite->numero_control . '. ' . $th);

            throw new TramiteServiceException("Error al actualizar trámite");

        }


    }

    public function procesarLineaCaptura():void
    {

        if($this->tramite->solicitante == 'Oficialia de partes'){

            $this->orden_de_pago = 0;

            $this->linea = 0;

            $this->fecha_vencimiento = now()->toDateString();

            $this->tramite->fecha_prelacion = now()->toDateString();

            return;

        }

        $array = (new LineaCaptura($this->tramite))->generarLineaDeCaptura();

        $this->orden_de_pago = $array['SOAPBody']['ns0MT_ServGralLC_PI_Receiver']['ES_OPAG']['NRO_ORD_PAGO'];

        $this->linea = $array['SOAPBody']['ns0MT_ServGralLC_PI_Receiver']['ES_OPAG']['LINEA_CAPTURA'];

        $this->fecha_vencimiento = $this->convertirFecha($array['SOAPBody']['ns0MT_ServGralLC_PI_Receiver']['ES_OPAG']['FECHA_VENCIMIENTO']);

        /* $this->oxxo_cod = $array['SOAPBody']['ns0MT_ServGralLC_PI_Receiver']['TB_CONV_BANCARIOS'][1]['COD_BANCO'];

        $this->oxxo_conv = $array['SOAPBody']['ns0MT_ServGralLC_PI_Receiver']['TB_CONV_BANCARIOS'][1]['COD_CONVENIO']; */

    }

    public function convertirFecha($fecha):string
    {

        if(Str::length($fecha) == 10)
            return $fecha;

        return Str::substr($fecha, 0, 4) . '-' . Str::substr($fecha, 4, 2) . '-' . Str::substr($fecha, 6, 2);

    }

    public function calcularMonto():float
    {

        $monto = 0;

        if($this->tramite->foraneo){

            $monto = 1865 + (float)$this->tramite->monto;

        }elseif($this->tramite->solicitante == 'Oficialia de partes'){

            $monto = 0;

        }else{

            $monto = (float)$this->tramite->monto;

        }

        return $monto;

    }

    public function calcularFechaEntrega():string
    {

        if($this->tramite->tipo_servicio == 'ordinario'){

            $actual = now();

            for ($i=0; $i < 5; $i++) {

                $actual->addDays(1);

                while($actual->isWeekend()){

                    $actual->addDay();

                }

            }

            return $actual->toDateString();

        }elseif($this->tramite->tipo_servicio == 'urgente'){

            $actual = now()->addDays(1);

            while($actual->isWeekend()){

                $actual->addDay();

            }

            return $actual->toDateString();

        }else{

            return now()->toDateString();

        }

    }

    public function procesarPago():void
    {

        try {

            $array = (new LineaCaptura($this->tramite))->validarLineaDeCaptura();

            $fecha = $array['SOAPBody']['n0MT_ValidarLinCaptura_ECC_Sender']['FEC_PAGO'];

            $documento = $array['SOAPBody']['n0MT_ValidarLinCaptura_ECC_Sender']['DOC_PAGO'];

            $this->tramite->update([
                'estado' => 'pagado',
                'fecha_pago' => $this->convertirFecha($fecha),
                'fecha_prelacion' => $this->convertirFecha($fecha),
                'documento_de_pago' => $documento,
                'fecha_entrega' => $this->calcularFechaEntrega()
            ]);

            if($this->tramite->tipo_tramite == 'complemento'){

                (new SistemaRppService())->cambiarTipoServicio($this->tramite);

                return;

            }

            if($this->tramite->adiciona){

                /* COPIAS */
                if($this->tramite->adicionaAlTramite->servicio->clave_ingreso == 'DC93'){

                    if($this->tramite->adicionaAlTramite->servicio->estado != 'pagado'){

                        $this->tramite->adicionaAlTramite->update([
                            'estado' => 'pagado',
                            'fecha_pago' => $this->convertirFecha($fecha),
                            'fecha_prelacion' => $this->convertirFecha($fecha),
                            'documento_de_pago' => $documento
                        ]);
                    }

                    /* Caso de agregar copias a la consulta */
                    (new SistemaRppService())->insertarSistemaRpp($this->tramite);

                }elseif($this->tramite->adicionaAlTramite->servicio->clave_ingreso == 'DL13' || $this->tramite->adicionaAlTramite->servicio->clave_ingreso == 'DL14'){

                    /* Caso de agregar numero de paginas a un tramite de copias existente */
                    (new SistemaRppService())->actualizarPaginas($this->tramite);

                }

            /* CONSULTAS */
            }else{

                /* Consultas */
                (new SistemaRppService())->insertarSistemaRpp($this->tramite);

            }

        } catch (ErrorAlValidarLineaDeCaptura $th) {

            throw new TramiteServiceException($th->getMessage());

        } catch (SistemaRppServiceException $th) {

            throw new TramiteServiceException($th->getMessage());

        } catch (\Throwable $th) {

            Log::error("Error al procesar pago de trámite por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". Trámite: " . $this->tramite->año . '-' . $this->tramite->numero_control . '. ' . $th);

            throw new TramiteServiceException("Error al procesar pago del trámite");

        }

    }

    public function cambiarEstado($estado):void
    {

        try {

            $this->tramite->update(['estado' => $estado]);

            $this->tramite->load('adicionadoPor');

            if($estado == 'concluido'){

                foreach($this->tramite->adicionadoPor as $tramite){

                    $tramite->update(['estado' => $estado]);

                }

            }

        } catch (\Throwable $th) {

            Log::error("Error al cambiar estado de trámite por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". Trámite: " . $this->tramite->año . '-' . $this->tramite->numero_control . '. ' . $th);

            throw new TramiteServiceException("Error al cambiar estado del trámite.");

        }

    }

}
