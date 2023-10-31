<?php

namespace App\Livewire\Admin;

use App\Models\Uma;
use Livewire\Component;
use Livewire\WithPagination;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\Log;

class Umas extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public Uma $modelo_editar;

    protected function rules(){
        return [
            'modelo_editar.año' => 'required|numeric|min:2016|unique:umas,año,' . $this->modelo_editar->id,
            'modelo_editar.diario' => 'required|numeric',
         ];
    }

    public function crearModeloVacio(){
        $this->modelo_editar = Uma::make();
    }

    public function abrirModalEditar(Uma $modelo){

        $this->resetearTodo();
        $this->modal = true;
        $this->editar = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

    }

    public function guardar(){

        $this->validate();

        try {

            $this->modelo_editar->anual = $this->modelo_editar->diario * 30.4 *12;
            $this->modelo_editar->mensual = $this->modelo_editar->diario * 30.4;
            $this->modelo_editar->creado_por = auth()->user()->id;
            $this->modelo_editar->save();

            $this->resetearTodo();

            $this->dispatch('mostrarMensaje', ['success', "La UMA se creó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al crear UMA por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function actualizar(){

        try{

            $this->modelo_editar->anual = $this->modelo_editar->diario * 30.4 *12;
            $this->modelo_editar->mensual = $this->modelo_editar->diario * 30.4;
            $this->modelo_editar->actualizado_por = auth()->user()->id;
            $this->modelo_editar->save();

            $this->resetearTodo();

            $this->dispatch('mostrarMensaje', ['success', "La UMA se actualizó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al actualizar UMA por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function borrar(){

        try{

            $uma = Uma::find($this->selected_id);

            $uma->delete();

            $this->resetearTodo($borrado = true);

            $this->dispatch('mostrarMensaje', ['success', "La UMA se eliminó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar UMA por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function render()
    {

        $umas = Uma::with('creadoPor', 'actualizadoPor')
                            ->where('año', 'like', '%' . $this->search .'%')
                            ->orderBy($this->sort, $this->direction)
                            ->paginate($this->pagination);

        return view('livewire.admin.umas', compact('umas'))->extends('layouts.admin');
    }

}
