<?php

namespace App\Http\Services\Tramites;

use App\Models\Tramite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Exceptions\TramiteServiceException;
use App\Exceptions\SistemaRppServiceException;
use App\Exceptions\ErrorAlGenerarLineaDeCaptura;
use App\Exceptions\ErrorAlValidarLineaDeCaptura;
use App\Http\Services\SistemaRPP\SistemaRppService;
use App\Http\Services\LineasDeCaptura\LineaCapturaApi;
use App\Models\AlertaInmobiliaria;

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

            $this->tramite->estado = 'nuevo';
            $this->tramite->fecha_entrega = $this->calcularFechaEntrega();
            $this->tramite->monto = $this->calcularMonto();
            $this->tramite->año = now()->format('Y');
            $this->tramite->usuario = auth()->user()->clave;
            $this->tramite->numero_control = (Tramite::where('año', $this->tramite->año)->where('usuario', auth()->user()->clave)->max('numero_control') ?? 0) + 1;

            $this->procesarLineaCaptura();

            $this->tramite->save();

            if($this->tramite->solicitante == 'Oficialia de partes' || $this->tramite->solicitante == 'SAT'){

                $this->tramite->update([
                    'estado' => 'pagado',
                    'fecha_pago' => now(),
                    'fecha_prelacion' => now()->toDateString(),
                ]);

                if($this->tramite->servicio->categoria->nombre === 'Certificaciones')
                    (new SistemaRppService())->insertarSistemaRpp($this->tramite);

            }

            return $this->tramite;

        } catch (ErrorAlGenerarLineaDeCaptura $th) {

            throw new TramiteServiceException($th->getMessage());

        }catch (SistemaRppServiceException $th) {

            throw new TramiteServiceException($th->getMessage());

        } catch (\Throwable $th) {

            Log::error("Error al crear trámite por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". Trámite: " . $this->tramite->año . '-' . $this->tramite->numero_control . '-' . $this->tramite->usuario . '. ' . $th);

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

            Log::error("Error al actualizar trámite por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". Trámite: " . $this->tramite->año . '-' . $this->tramite->numero_control . '. '  . $this->tramite->usuario . $th);

            throw new TramiteServiceException("Error al actualizar trámite");

        }


    }

    public function procesarLineaCaptura():void
    {

        if($this->tramite->solicitante == 'Oficialia de partes' || $this->tramite->solicitante == 'SAT'){

            $this->tramite->orden_de_pago = 0;

            $this->tramite->linea_de_captura = 0;

            $this->tramite->limite_de_pago = now()->toDateString();

            $this->tramite->fecha_prelacion = now()->toDateString();

            return;

        }

        $array = (new LineaCapturaApi($this->tramite))->generarLineaDeCaptura();

        $this->tramite->orden_de_pago = $array['ES_OPAG']['NRO_ORD_PAGO'];
        $this->tramite->linea_de_captura = $array['ES_OPAG']['LINEA_CAPTURA'];
        $this->tramite->limite_de_pago = $this->convertirFecha($array['ES_OPAG']['FECHA_VENCIMIENTO']);

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

            $array = (new LineaCapturaApi($this->tramite))->validarLineaDeCaptura();

            $fecha = $this->convertirFecha($array['FEC_PAGO']);
            $documento = $array['DOC_PAGO'];

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

            /* Certificaciones */
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

            }else{

                if($this->tramite->servicio->categoria->nombre === 'Certificaciones')
                    (new SistemaRppService())->insertarSistemaRpp($this->tramite);

            }

            /* Alerta inmobiliaria */
            if($this->tramite->servicio->clave_ingreso == 'DL19' && $this->tramite->folio_real){

                AlertaInmobiliaria::craete([
                    'estado' => 'activo',
                    'folio_real' => $this->tramite->folio_real,
                    'fecha_vencimiento' => now()->addYear()->toDateString(),
                    'email' => $this->tramite->email
                ]);

                $this->tramite->update([
                    'estado' => 'finalizado'
                ]);

            }

        } catch (ErrorAlValidarLineaDeCaptura $th) {

            throw new TramiteServiceException($th->getMessage());

        } catch (SistemaRppServiceException $th) {

            throw new TramiteServiceException($th->getMessage());

        } catch (\Throwable $th) {

            Log::error("Error al procesar pago de trámite por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". Trámite: " . $this->tramite->año . '-' . $this->tramite->numero_control . '-' . $this->tramite->usuario . '. ' . $th);

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

            Log::error("Error al cambiar estado de trámite por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". Trámite: " . $this->tramite->año . '-' . $this->tramite->numero_control . '. ' . $this->tramite->usuario .$th);

            throw new TramiteServiceException("Error al cambiar estado del trámite.");

        }

    }

}
