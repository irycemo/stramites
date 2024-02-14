<?php

namespace App\Jobs;

use Throwable;
use App\Models\Tramite;
use Illuminate\Support\Str;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Exceptions\ErrorAlGenerarLineaDeCaptura;
use App\Http\Services\LineasDeCaptura\LineaCaptura;
use App\Http\Services\SistemaRPP\SistemaRppService;

class GenerarFolioTramite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    private Tramite $tramite;

    public $tries = 5;

    public function __construct(int $tramiteId)
    {

        $this->tramite = Tramite::findOrFail($tramiteId);

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        try {

            $this->tramite->numero_control = (Tramite::where('año', $this->tramite->año)->max('numero_control') ?? 0) + 1;

            $this->procesarLineaCaptura();

            $this->tramite->save();

            if($this->tramite->solicitante == 'Oficialia de partes' || $this->tramite->solicitante == 'SAT'){

                $this->tramite->update([
                    'estado' => 'pagado',
                    'fecha_pago' => now(),
                    'fecha_prelacion' => now()->toDateString(),
                ]);

                (new SistemaRppService())->insertarSistemaRpp($this->tramite);

            }

        } catch (ErrorAlGenerarLineaDeCaptura $th) {

            throw $th;

        } catch (\Throwable $th) {

            throw $th;

        }

    }

    public function failed(Throwable $exception): void
    {
        if($this->tramite->adicionadoPor->count()){

            if($this->tramite->adicionadoPor->first()->linea_de_captura) return;

            $this->tramite->adicionadoPor->first()->delete();

            $this->tramite->delete();

        }else{

            $this->tramite->delete();

        }
    }

    public function procesarLineaCaptura():void
    {

        if($this->tramite->solicitante == 'Oficialia de partes'){

            $this->tramite->orden_de_pago = 0;

            $this->tramite->linea_de_captura = 0;

            $this->tramite->limite_de_pago = now()->toDateString();

            $this->tramite->fecha_prelacion = now()->toDateString();

            return;

        }

        $array = (new LineaCaptura($this->tramite))->generarLineaDeCaptura();

        $this->tramite->orden_de_pago = $array['SOAPBody']['ns0MT_ServGralLC_PI_Receiver']['ES_OPAG']['NRO_ORD_PAGO'];

        $this->tramite->linea_de_captura = $array['SOAPBody']['ns0MT_ServGralLC_PI_Receiver']['ES_OPAG']['LINEA_CAPTURA'];

        $this->tramite->limite_de_pago = $this->convertirFecha($array['SOAPBody']['ns0MT_ServGralLC_PI_Receiver']['ES_OPAG']['FECHA_VENCIMIENTO']);

        /* $this->oxxo_cod = $array['SOAPBody']['ns0MT_ServGralLC_PI_Receiver']['TB_CONV_BANCARIOS'][1]['COD_BANCO'];

        $this->oxxo_conv = $array['SOAPBody']['ns0MT_ServGralLC_PI_Receiver']['TB_CONV_BANCARIOS'][1]['COD_CONVENIO']; */

    }

    public function convertirFecha($fecha):string
    {

        if(Str::length($fecha) == 10)
            return $fecha;

        return Str::substr($fecha, 0, 4) . '-' . Str::substr($fecha, 4, 2) . '-' . Str::substr($fecha, 6, 2);

    }

}
