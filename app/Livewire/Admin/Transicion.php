<?php

namespace App\Livewire\Admin;

use App\Constantes\Constantes;
use Livewire\Component;
use Livewire\WithPagination;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\Log;
use App\Models\Transicion as ModelsTransicion;

class Transicion extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public ModelsTransicion $modelo_editar;

    public $distritos;

    protected function rules(){
        return [
            'modelo_editar.tomo' => 'required',
            'modelo_editar.registro' => 'required',
            'modelo_editar.distrito' => 'required',
            'modelo_editar.seccion' => 'required',
            'modelo_editar.numero_control' => 'required',
            'modelo_editar.numero_propiedad' => 'required',
            'modelo_editar.servicio' => 'required',
            'modelo_editar.observaciones' => 'nullable',
         ];
    }

    protected $validationAttributes  = [
        'modelo_editar.numero_control' => 'número de control',
        'modelo_editar.numero_propiedad' => 'número de propiedad',
    ];

    public function crearModeloVacio(){
        $this->modelo_editar = ModelsTransicion::make();
    }

    public function abrirModalEditar(ModelsTransicion $modelo){

        $this->resetearTodo();
        $this->modal = true;
        $this->editar = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

    }

    public function guardar(){

        $this->validate();

        try {

            $this->modelo_editar->save();

            $this->resetearTodo();

            $this->dispatch('mostrarMensaje', ['success', "El transición se creó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al crear transición por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function actualizar(){

        $this->validate();

        try{

            $this->modelo_editar->save();

            $this->resetearTodo();

            $this->dispatch('mostrarMensaje', ['success', "El transición se actualizó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al actualizar transición por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function borrar(){

        try{

            $transicion = ModelsTransicion::find($this->selected_id);

            $transicion->delete();

            $this->resetearTodo($borrado = true);

            $this->dispatch('mostrarMensaje', ['success', "El transición se eliminó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar transición por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function mount(){

        $this->crearModeloVacio();

        $this->distritos = Constantes::DISTRITOS;

    }

    public function render()
    {

        $trancisiones = ModelsTransicion::where('tomo', 'LIKE', '%' . $this->search . '%')
                                            ->orWhere('registro', 'LIKE', '%' . $this->search . '%')
                                            ->orWhere('seccion', 'LIKE', '%' . $this->search . '%')
                                            ->orWhere('distrito', 'LIKE', '%' . $this->search . '%')
                                            ->orWhere('numero_propiedad', 'LIKE', '%' . $this->search . '%')
                                            ->orWhere('numero_control', 'LIKE', '%' . $this->search . '%')
                                            ->orWhere('servicio', 'LIKE', '%' . $this->search . '%')
                                            ->orderBy($this->sort, $this->direction)
                                            ->paginate($this->pagination);


        return view('livewire.admin.transicion', compact('trancisiones'))->extends('layouts.admin');
    }
}
