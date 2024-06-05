<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Dependencia;
use Livewire\WithPagination;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\Log;

class Dependencias extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public Dependencia $modelo_editar;

    protected function rules(){
        return [
            'modelo_editar.nombre' => 'required',
         ];
    }

    public function crearModeloVacio(){
        $this->modelo_editar = Dependencia::make();
    }

    public function abrirModalEditar(Dependencia $modelo){

        $this->resetearTodo();
        $this->modal = true;
        $this->editar = true;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

    }

    public function guardar(){

        $this->validate();

        try {

            $this->modelo_editar->creado_por = auth()->user()->id;
            $this->modelo_editar->save();

            cache()->forget('dependencias');

            $this->resetearTodo();

            $this->dispatch('mostrarMensaje', ['success', "La dependencia se creó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al crear dependencia por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function actualizar(){

        try{

            $this->modelo_editar->actualizado_por = auth()->user()->id;
            $this->modelo_editar->save();

            $this->resetearTodo();

            cache()->forget('dependencias');

            $this->dispatch('mostrarMensaje', ['success', "La dependencia se actualizó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al actualizar dependencia por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function borrar(){

        try{

            $Dependencia = Dependencia::find($this->selected_id);

            $Dependencia->delete();

            $this->resetearTodo($borrado = true);

            $this->dispatch('mostrarMensaje', ['success', "La dependencia se eliminó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar dependencia por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function render()
    {

        $dependencias = Dependencia::with('creadoPor', 'actualizadoPor')
                                        ->where('nombre', 'like', '%'. $this->search . '%')
                                        ->orderBy($this->sort, $this->direction)
                                        ->paginate($this->pagination);

        return view('livewire.admin.dependencias', compact('dependencias'))->extends('layouts.admin');
    }

}
