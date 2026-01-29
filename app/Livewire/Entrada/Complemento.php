<?php

namespace App\Livewire\Entrada;

use App\Models\Tramite;
use Livewire\Component;
use App\Constantes\Constantes;
use Illuminate\Support\Facades\Log;
use App\Exceptions\GeneralException;
use App\Http\Services\Tramites\TramiteService;

class Complemento extends Component
{

    public $años;
    public $año;
    public $numero_control;
    public $usuario;

    public $tramite;
    public $tipo_servicio;
    public $monto;

    public function buscarTramite(){

        $this->validate(['numero_control' => 'required', 'año' => 'required', 'usuario' => 'required']);

        $this->tramite = Tramite::with('servicio.categoria')
                                    ->where('año', $this->año)
                                    ->where('numero_control', $this->numero_control)
                                    ->where('usuario', $this->usuario)
                                    ->first();

        if(!$this->tramite){

            $this->dispatch('mostrarMensaje', ['warning', "No se encontro el trámite."]);

            $this->tramite = null;

            return;

        }

        if($this->tramite->estado != 'pagado'){

            $this->dispatch('mostrarMensaje', ['warning', "El trámite no esta pagado."]);

            $this->tramite = null;

            return;

        }

        if(in_array($this->tramite->servicio->clave_ingreso, ['DL14', 'DL13'])){

            $this->dispatch('mostrarMensaje', ['warning', "Los complementos para copias se registran en el área de entrada."]);

            $this->tramite = null;

            return;

        }

    }

    public function updatedTipoServicio(){

        $this->monto = $this->tramite->servicio[$this->tipo_servicio] * $this->tramite->cantidad - $this->tramite->monto;

        $this->monto < 0 ? $this->monto = 0 : $this->monto = $this->monto;

    }

    public function crearTramite(){

        $this->validate(['tipo_servicio' => 'required']);

        try {

            $tramite = $this->tramite->replicate();

            $tramite->adiciona = $this->tramite->id;
            $tramite->tipo_servicio = $this->tipo_servicio;
            $tramite->tipo_tramite = 'complemento';
            $tramite->monto = $this->monto;
            $tramite->cantidad = 1;
            $tramite->observaciones = 'PAGO COMPLEMENTO';
            $tramite->fecha_pago = null;
            $tramite->documento_de_pago = null;

            $tramite = (new TramiteService($tramite))->crear();

            $this->dispatch('mostrarMensaje', ['success', "El trámite se creó con éxito."]);

            $this->dispatch('imprimir_recibo', ['tramite' => $tramite->id]);

            $this->reset(['tramite', 'monto', 'tipo_servicio', 'numero_control', 'usuario']);

        } catch (GeneralException $ex) {

            $this->dispatch('mostrarMensaje', ['warning', $ex->getMessage()]);

        }  catch (\Throwable $th) {

            Log::error("Error al crear tramite complemento por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

            $this->dispatch('mostrarMensaje', ['error', 'Hubo un error.']);

        }

    }

    public function mount(){

        $this->años = Constantes::AÑOS;

        $this->año = now()->format('Y');

    }

    public function render()
    {
        return view('livewire.entrada.complemento')->extends('layouts.admin');
    }
}
