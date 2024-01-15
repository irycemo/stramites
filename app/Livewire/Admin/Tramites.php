<?php

namespace App\Livewire\Admin;

use App\Models\Tramite;
use Livewire\Component;
use Livewire\WithPagination;
use App\Constantes\Constantes;
use App\Traits\ComponentesTrait;
use App\Jobs\GenerarFolioTramite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\TramiteServiceException;
use App\Exceptions\SistemaRppServiceException;
use App\Http\Services\Tramites\TramiteService;
use App\Http\Services\SistemaRPP\SistemaRppService;

class Tramites extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public $solicitantes;
    public $secciones;
    public $categorias;
    public $categoria;
    public $categoria_selected;
    public $servicios;
    public $servicio;
    public $servicio_selected;
    public $adicionaTramite;
    public $tramitesAdiciona;
    public $distritos;
    public $dependencias;
    public $notarias;
    public $notaria;
    public $numero_de_control;
    public $tramite;
    public $modalVer = false;
    public $año;
    public $años;

    public Tramite $modelo_editar;

    public $flags = [
        'adiciona' => false,
        'solicitante' => false,
        'nombre_solicitante' => false,
        'seccion' => false,
        'numero_oficio' => false,
        'tomo' => false,
        'folio_real' => false,
        'registro' => false,
        'numero_propiedad' => false,
        'distrito' => false,
        'numero_inmuebles' => false,
        'numero_escritura' => false,
        'tomo_gravamen' => false,
        'foraneo' => false,
        'registro_gravamen' => false,
        'numero_paginas' => false,
        'valor_propiedad' => false,
        'dependencias' => false,
        'notarias' => false,
        'tipo_servicio' => false,
        'observaciones' => false
    ];

    protected function rules(){
        return [
            'modelo_editar.solicitante' => 'required',
            'modelo_editar.nombre_solicitante' => 'required_if:modelo_editar.solicitante,Ventanilla,Juzgado',
            'modelo_editar.numero_oficio' => 'nullable',
            'modelo_editar.folio_real' => 'nullable',
            'modelo_editar.tomo' => 'nullable|required_with:tomo_bis',
            'modelo_editar.tomo_bis' => 'nullable',
            'modelo_editar.registro' => 'nullable|required_with:registro_bis',
            'modelo_editar.registro_bis' => 'nullable',
            'modelo_editar.tomo_gravamen' => 'nullable',
            'modelo_editar.registro_gravamen' => 'nullable',
            'modelo_editar.distrito' => 'nullable',
            'modelo_editar.seccion' => 'nullable',
            'modelo_editar.limite_de_pago' => 'nullable',
            'modelo_editar.fecha_entrega' => 'nullable',
            'modelo_editar.cantidad' => 'nullable',
            'modelo_editar.numero_inmuebles' => 'nullable',
            'modelo_editar.numero_propiedad' => 'nullable',
            'modelo_editar.numero_escritura' => 'nullable',
            'modelo_editar.numero_notaria' => 'nullable',
            'modelo_editar.nombre_notario' => 'nullable',
            'modelo_editar.valor_propiedad' => 'nullable',
            'modelo_editar.observaciones' => 'nullable',
            'modelo_editar.tipo_documento' => 'nullable',
            'modelo_editar.autoridad_cargo' => 'nullable',
            'modelo_editar.nombre_autoridad' => 'nullable',
            'modelo_editar.numero_documento' => 'nullable',
            'modelo_editar.fecha_emision' => 'nullable',
            'modelo_editar.procedencia' => 'nullable',
         ];
    }

    protected $messages = [
        'modelo_editar.adiciona.required_if' => 'El campo trámite es obligatorio cuando el campo adiciona tramite está seleccionado.',
    ];

    protected $validationAttributes  = [
        'modelo_editar.id_servicio' => 'servicio',
        'modelo_editar.folio_real' => 'folio real',
        'modelo_editar.tomo_bis' => 'tomo bis',
        'modelo_editar.registro_bis' => 'registro bis',
        'modelo_editar.numero_propiedad' => 'número de propiedad',
        'modelo_editar.nombre_solicitante' => 'nombre del solicitante',
        'modelo_editar.fecha_entrega' => 'fecha de entrega',
        'modelo_editar.tipo_servicio' => 'tipo de servicio',
        'modelo_editar.numero_control' => 'número de control',
        'modelo_editar.numero_escritura' => 'número de escritura',
        'modelo_editar.numero_notaria' => 'número de notaria',
        'modelo_editar.limite_de_pago' => 'límite de pago',
        'modelo_editar.adiciona' => 'trámite',
        'modelo_editar.numero_inmuebles' => 'cantidad de inmuebles',
        'modelo_editar.valor_propiedad' => 'valor de la propiedad',
        'modelo_editar.registro_gravamen' => 'registro gravamen',
        'modelo_editar.tomo_gravamen' => 'tomo gravamen',
        'modelo_editar.numero_oficio' => 'número de oficio'
    ];

    public function crearModeloVacio(){
        $this->modelo_editar = Tramite::make();
    }

    public function abrirModalEditar(Tramite $modelo){

        $this->resetearTodo();

        $this->selected_id = $modelo->id;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modal = true;
        $this->editar = true;

        foreach($this->modelo_editar->getAttributes() as $attribute => $value){

            if($value)
                $this->flags['' . $attribute] = true;

        };

        if($this->modelo_editar->adiciona)
            $this->adicionaTramite = true;

    }

    public function abrirModalVer(Tramite $modelo){

        $this->resetearTodo();

        $this->selected_id = $modelo->id;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalVer = true;

        $this->modelo_editar->load('adicionadoPor');

    }

    public function validarPago(){

        try {

            DB::transaction(function () {

                (new TramiteService($this->modelo_editar))->procesarPago();

                $this->dispatch('mostrarMensaje', ['success', "El trámite se validó con éxito."]);

                $this->resetearTodo($borrado = true);

            });

        } catch (TramiteServiceException $th) {

            $this->dispatch('mostrarMensaje', ['error', $th->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al validar el trámite: " . $this->modelo_editar->año . '-' . $this->modelo_editar->numero_control . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

            $this->dispatch('mostrarMensaje', ['error', 'Hubo un error.']);
            $this->resetearTodo();
        }

    }

    public function actualizar(){

        $this->validate();

        try{

            (new TramiteService($this->modelo_editar))->actualizar();

            $this->resetearTodo($borrado = true);

            $this->dispatch('mostrarMensaje', ['success', "El trámite se actualizó con éxito."]);

        } catch (SistemaRppServiceException $th) {

            $this->dispatch('mostrarMensaje', ['error', $th->getMessage()]);

        } catch (TramiteServiceException $th) {

            $this->dispatch('mostrarMensaje', ['error', $th->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al actualizar el trámite: " . $this->modelo_editar->año . '-' . $this->modelo_editar->numero_control . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

            $this->dispatch('mostrarMensaje', ['error', 'Hubo un error.']);
            $this->resetearTodo($borrado = true);
        }

    }

    public function borrar(){

        try{

            if($this->modelo_editar->movimiento_registral){

                $this->dispatch('mostrarMensaje', ['error', "El trámite esta registrado en el Sistema RPP no puede ser eliminado."]);

                return;

            }

            if($this->modelo_editar->fecha_pago){

                $this->dispatch('mostrarMensaje', ['error', "El trámite tiene un pago registrado no puede ser borrado."]);

                return;

            }

            $this->modelo_editar->delete();

            $this->resetearTodo($borrado = true);

            $this->dispatch('mostrarMensaje', ['success', "El trámite se eliminó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar trámite por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }
    }

    public function reimprimir(){

        $this->dispatch('imprimir_recibo', ['tramite' => $this->modelo_editar->id]);

    }

    public function enviarTramiteRpp(){

        if($this->modelo_editar->adicionaAlTramite && $this->modelo_editar->adicionaAlTramite->servicio->clave_ingreso != 'DC93'){

            $this->dispatch('mostrarMensaje', ['success', "El trámite adiciona al trámite: " . $this->modelo_editar->adicionaAlTramite->numero_control . '-' . $this->modelo_editar->adicionaAlTramite->numero_control . ' no es posible enviarlo al Sistema RPP.']);

            return;

        }

        try{

            (new SistemaRppService())->insertarSistemaRpp($this->modelo_editar);

            $this->resetearTodo($borrado = true);

            $this->dispatch('mostrarMensaje', ['success', "El trámite se envió al Sistema RPP con éxito."]);

        } catch (SistemaRppServiceException $th) {

            $this->dispatch('mostrarMensaje', ['error', $th->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al enviar trámite al sistema rpp por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function generarNumeroControl(){

        if($this->modelo_editar->id && !$this->modelo_editar->numero_control)
            dispatch(new GenerarFolioTramite($this->modelo_editar->id));

        $this->resetearTodo();

    }

    public function mount(){

        array_push($this->fields, 'adicionaTramite', 'flags', 'modalVer');

        $this->crearModeloVacio();

        $this->secciones = Constantes::SECCIONES;

        $this->distritos = Constantes::DISTRITOS;

        $this->años = Constantes::AÑOS;

    }

    public function render()
    {

        $tramites = Tramite::with('creadoPor', 'actualizadoPor', 'adicionaAlTramite', 'servicio')
                                ->when(isset($this->año) && $this->año != "", function($q){
                                    return $q->orWhere('año', $this->año);

                                })
                                ->where(function($q){
                                    $q->where('solicitante', 'LIKE', '%' . $this->search . '%')
                                        ->orWhere('nombre_solicitante', 'LIKE', '%' . $this->search . '%')
                                        ->orWhere('folio_real', 'LIKE', '%' . $this->search . '%')
                                        ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                        ->orWhere('estado', 'LIKE', '%' . $this->search . '%')
                                        ->orWhere('registro', 'LIKE', '%' . $this->search . '%')
                                        ->orWhere('numero_propiedad', 'LIKE', '%' . $this->search . '%')
                                        ->orWhere('distrito', 'LIKE', '%' . $this->search . '%')
                                        ->orWhere('numero_control', 'LIKE', '%' . $this->search . '%')
                                        ->orWhere('numero_escritura', 'LIKE', '%' . $this->search . '%')
                                        ->orWhere('numero_notaria', 'LIKE', '%' . $this->search . '%')
                                        ->orWhere(function($q){
                                            return $q->whereHas('creadoPor', function($q){
                                                return $q->where('name', 'LIKE', '%' . $this->search . '%');
                                            });
                                        })
                                        ->orWhere(function($q){
                                            return $q->whereHas('servicio', function($q){
                                                return $q->where('nombre', 'LIKE', '%' . $this->search . '%');
                                            });
                                        });
                                })
                                ->orderBy($this->sort, $this->direction)
                                ->paginate($this->pagination);

        return view('livewire.admin.tramites', compact('tramites'))->extends('layouts.admin');
    }

}
