<?php

namespace App\Livewire\Entrega;

use App\Models\Tramite;
use Livewire\Component;
use Livewire\WithPagination;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\Log;
use App\Http\Services\Tramites\TramiteService;

class Entrega extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public $solicitantes;
    public $secciones;
    public $categorias;
    public $categoria_servicio;
    public $servicios;
    public $servicio;
    public $adicionaTramite;
    public $modalRecibir = false;
    public $modalFinalizar = false;

    public Tramite $modelo_editar;

    public function crearModeloVacio(){
        $this->modelo_editar = Tramite::make();
    }

    public function abrirModalRecibir(Tramite $modelo){
        $this->modalRecibir = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;
    }

    public function recibir(){

        try {

            (new TramiteService($this->modelo_editar))->cambiarEstado('recibido');

            $this->modelo_editar->recibido_por = auth()->user()->id;
            $this->modelo_editar->actualizado_por = auth()->user()->id;
            $this->modelo_editar->save();

            $this->resetearTodo();

        } catch (\Throwable $th) {

            Log::error("Error al recibir documentación por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function abrirModalFinalizar(Tramite $modelo){

        $this->modalFinalizar = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

    }

    public function finalizar(){

        try{

            (new TramiteService($this->modelo_editar))->cambiarEstado('finalizado');

            $this->modelo_editar->actualizado_por = auth()->user()->id;
            $this->modelo_editar->save();

            $this->resetearTodo();

            $this->dispatch('mostrarMensaje', ['success', "El trámite finalizó con éxito."]);

        }catch (\Throwable $th) {

            Log::error("Error al entregar trámite por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function mount(){

        array_push($this->fields, 'modalFinalizar', 'modalRecibir');

        $this->crearModeloVacio();

    }

    public function render()
    {

        $tramites = Tramite::select('id', 'año', 'numero_control', 'usuario', 'solicitante', 'adiciona', 'id_servicio', 'recibido_por', 'nombre_solicitante', 'folio_real', 'tomo', 'registro', 'distrito', 'tipo_servicio', 'creado_por', 'actualizado_por', 'created_at', 'updated_at')
                                ->with('creadoPor:id,name', 'actualizadoPor:id,name', 'adicionaAlTramite:id', 'servicio:id,nombre', 'recibidoPor:id,name')
                                ->whereIn('estado', ['concluido', 'recibido'])
                                ->when(auth()->user()->ubicacion == 'Regional 4', function($q){
                                    $q->where('distrito', 2);
                                })
                                ->when(auth()->user()->ubicacion != 'Regional 4', function($q){
                                    $q->where('distrito', '!=', 2);
                                })
                                ->where(function ($q){
                                    return $q->where('solicitante', 'LIKE', '%' . $this->search . '%')
                                                ->orWhere('nombre_solicitante', 'LIKE', '%' . $this->search . '%')
                                                ->orWhere('folio_real', 'LIKE', '%' . $this->search . '%')
                                                ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                                ->orWhere('registro', 'LIKE', '%' . $this->search . '%')
                                                ->orWhere('numero_propiedad', 'LIKE', '%' . $this->search . '%')
                                                ->orWhere('distrito', 'LIKE', '%' . $this->search . '%')
                                                ->orWhere('numero_control', 'LIKE', '%' . $this->search . '%')
                                                ->orWhere('numero_escritura', 'LIKE', '%' . $this->search . '%')
                                                ->orWhere('numero_notaria', 'LIKE', '%' . $this->search . '%')
                                                ->orWhere(function($q){
                                                    $q->whereHas('creadoPor', function($q){
                                                        $q->select('id','name')
                                                                    ->where('name', 'LIKE', '%' . $this->search . '%');
                                                    });
                                                })
                                                ->orWhere(function($q){
                                                    $q->whereHas('servicio', function($q){
                                                        $q->select('id', 'nombre')
                                                            ->where('nombre', 'LIKE', '%' . $this->search . '%');
                                                    });
                                                });
                                })
                                ->orderBy($this->sort, $this->direction)
                                ->paginate($this->pagination);

        return view('livewire.entrega.entrega', compact('tramites'))->extends('layouts.admin');

    }

}
