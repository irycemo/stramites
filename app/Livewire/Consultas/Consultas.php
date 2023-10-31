<?php

namespace App\Livewire\Consultas;

use App\Models\Tramite;
use Livewire\Component;
use Livewire\WithPagination;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\SistemaRppServiceException;
use App\Http\Services\Tramites\TramiteService;
use App\Exceptions\ErrorAlValidarLineaDeCaptura;

class Consultas extends Component
{

    use ComponentesTrait;
    use WithPagination;

    public $año;
    public $numero_control;
    public $tramite;
    public Tramite $modelo_editar;

    public function crearModeloVacio(){
        $this->modelo_editar = Tramite::make();
    }

    public function abrirModalEditar(Tramite $modelo){

        $this->resetearTodo();

        $this->selected_id = $modelo->id;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modal = true;

        $this->modelo_editar->load('adicionadoPor');

    }

    public function validarPago(){

        try {

            DB::transaction(function () {

                (new TramiteService($this->modelo_editar))->procesarPago();

                $this->dispatch('mostrarMensaje', ['success', "El trámite se validó con éxito."]);

                $this->resetearTodo($borrado = true);

            });

        } catch (ErrorAlValidarLineaDeCaptura $th) {

            $this->dispatch('mostrarMensaje', ['error', $th->getMessage()]);

        } catch (SistemaRppServiceException $th) {

            $this->dispatch('mostrarMensaje', ['error', $th->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al validar el trámite: " . $this->modelo_editar->año . '-' . $this->modelo_editar->numero_control . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

            $this->dispatch('mostrarMensaje', ['error', 'Hubo un error.']);
            $this->resetearTodo();
        }

    }

    public function reimprimir(){

        $this->dispatch('imprimir_recibo', ['tramite' => $this->modelo_editar->id]);

    }

    public function consultar(){

        $this->validate([
            'numero_control' => 'required',
            'año' => 'required',
        ]);

        $this->tramite = Tramite::where('numero_control', $this->numero_control)->where('año', $this->año)->first();

        $this->numero_control = null;

    }

    public function mount(){

        $this->año = now()->format('Y');

        $this->crearModeloVacio();

        array_push($this->fields, 'numero_control', 'tramite');

    }

    public function render()
    {

        return view('livewire.consultas.consultas')->extends('layouts.admin');
    }

}
