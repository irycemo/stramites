<?php

namespace App\Livewire\Entrada\Cobol;

use App\Exceptions\GeneralException;
use App\Http\Services\LineasDeCaptura\LineaCapturaApi;
use App\Models\Servicio;
use App\Models\Tramite;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;

class Cobol extends Component
{

    public $numero_control;

    public function buscarNumeroControl(){

        $this->validate(['numero_control' => 'required']);

        try {

            if(Storage::disk('s3')->exists('cobol/entrada/' . $this->numero_control . '.TXT')){

                $contenido = Storage::disk('s3')->get('cobol/entrada/' . $this->numero_control . '.TXT');

                $array = explode('|', $contenido);

                $trimmed_array = array_map('trim', $array);

                $servicio = Servicio::where('material', $trimmed_array[17])->first();

                if(! $servicio){

                    throw new GeneralException("No hay servicios con la clave material: " . $trimmed_array[17]);

                }

                $tipo_servicio = $this->tipoServicio($trimmed_array[5]);

                $tramite = Tramite::make([
                    'estado' => 'nuevo',
                    'año' => now()->format('Y'),
                    'numero_control' => (Tramite::where('año', now()->format('Y'))->where('usuario', 0)->max('numero_control') ?? 0) + 1,
                    'usuario' => 0,
                    'id_servicio' => $servicio->id,
                    'solicitante' => 'Usuario',
                    'nombre_solicitante' => mb_convert_encoding($trimmed_array[3], 'UTF-8', 'ISO-8859-1'),
                    'monto' => $servicio[$tipo_servicio],
                    'fecha_entrega' => $this->calcularFechaEntrega($servicio, $tipo_servicio),
                    'tipo_servicio' => $tipo_servicio,
                    'procedencia' => $this->numero_control
                ]);

                $array = (new LineaCapturaApi($tramite))->generarLineaDeCaptura();

                $tramite->orden_de_pago = $array['ES_OPAG']['NRO_ORD_PAGO'];
                $tramite->linea_de_captura = $array['ES_OPAG']['LINEA_CAPTURA'];
                $tramite->limite_de_pago = $this->convertirFecha($array['ES_OPAG']['FECHA_VENCIMIENTO']);
                $tramite->observaciones = mb_convert_encoding($trimmed_array[5], 'UTF-8', 'ISO-8859-1');

                $tramite->save();

                $this->dispatch('imprimir_recibo', ['tramite' => $tramite->id]);

            }else{

                $this->dispatch('mostrarMensaje', ['warning', "El número de control no se encontró."]);

            }

        } catch (GeneralException $ex) {

            $this->dispatch('mostrarMensaje', ['warning', $ex->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error crear trámite de cobol por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function calcularFechaEntrega($servicio, $tipo_servicio):string
    {

        if($servicio->categoria->nombre == 'Certificaciones'){

            $dias = 5;

        }else{

            $dias = 10;

        }

        if($tipo_servicio == 'ordinario'){

            $actual = now();

            for ($i=0; $i < $dias; $i++) {

                $actual->addDays(1);

                while($actual->isWeekend()){

                    $actual->addDay();

                }

            }

            return $actual->toDateString();

        }elseif($tipo_servicio == 'urgente'){

            $actual = now()->addDays(1);

            while($actual->isWeekend()){

                $actual->addDay();

            }

            return $actual->toDateString();

        }else{

            return now()->toDateString();

        }

    }

    public function tipoServicio($observaciones){

        if(str_contains($observaciones, 'EXTRAURGENTE')){

            return 'extra_urgente';

        }elseif(str_contains($observaciones, 'URGENTE')){

            return 'urgente';

        }elseif(str_contains($observaciones, 'ORDINARIO')){

            return 'ordinario';

        }else{

            return 'ordinario';

        }

    }

    public function convertirFecha($fecha):string
    {

        if(Str::length($fecha) == 10) return $fecha;

        return Str::substr($fecha, 0, 4) . '-' . Str::substr($fecha, 4, 2) . '-' . Str::substr($fecha, 6, 2);

    }

    public function render()
    {
        return view('livewire.entrada.cobol.cobol')->extends('layouts.admin');
    }
}
