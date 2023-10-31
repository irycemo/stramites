<?php

namespace App\Livewire\Recepcion;

use App\Models\File;
use App\Models\Tramite;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Recepcion extends Component
{

    use WithPagination;
    use ComponentesTrait;
    use WithFileUploads;

    public $documento;
    public $documentos = [];

    public Tramite $modelo_editar;

    protected function rules(){
        return [
            'documento' => 'nullable|mimes:pdf',
        ];
    }

    public function crearModeloVacio(){
        return Tramite::make();
    }

    public function abrirModalEditar(Tramite $modelo){

        $this->dispatch('removeFiles');

        $this->resetearTodo();

        $this->selected_id = $modelo['id'];
        $this->documentos = $modelo['files'];

        $this->modal = true;
        $this->editar = true;

    }

    public function guardarDocuento(){

        $this->validate();

        try {

            DB::transaction(function () {

                if($this->documento){

                    $pdf = $this->documento->store('/', 'pdfs');

                    File::create([
                        'fileable_id' => $this->selected_id,
                        'fileable_type' => 'App\Models\Tramite',
                        'url' => $pdf
                    ]);

                }

                $tramite = Tramite::find($this->selected_id);
                $tramite->update(['estado' => 'aceptado']);

                $this->dispatch('mostrarMensaje', ['success', "Se actualizó la información con éxito."]);

                $this->resetearTodo();

                $this->dispatch('removeFiles');

            });

        } catch (\Throwable $th) {

            Log::error("Error al guardar documento del trámite id: " . $this->selected_id . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function render()
    {

        $tramites = Tramite::with('creadoPor', 'actualizadoPor', 'adicionaAlTramite', 'servicio', 'files')
                                ->where(function($q){
                                    return $q->where('estado', 'recepcion')
                                                ->orWhere('estado', 'revision');
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

        return view('livewire.recepcion.recepcion', compact('tramites'))->extends('layouts.admin');
    }

}
