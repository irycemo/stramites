<?php

namespace App\Livewire\Recepcion;

use App\Models\File;
use App\Models\Tramite;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Constantes\Constantes;
use App\Exceptions\GeneralException;
use App\Traits\ComponentesTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\TramiteServiceException;
use App\Http\Services\Tramites\TramiteService;
use App\Http\Services\SistemaRPP\SistemaRppService;

class Recepcion extends Component
{

    use ComponentesTrait;
    use WithFileUploads;

    public $documento;

    public $años;
    public $año;
    public $usuario;
    public $numero_control;
    public $tramite;

    public Tramite $modelo_editar;

    public $usuario_asignado;

    protected function rules(){
        return [
            'documento' => 'required|mimes:pdf',
        ];
    }

    public function crearModeloVacio(){
        $this->modelo_editar = Tramite::make();
    }

    public function consultar(){

        $this->validate([
            'numero_control' => 'required',
            'año' => 'required',
            'usuario' => 'required'
        ]);

        $this->tramite = Tramite::where('numero_control', $this->numero_control)
                                    ->where('año', $this->año)
                                    ->where('usuario', $this->usuario)
                                    ->first();

        if(!$this->tramite){

            $this->dispatch('mostrarMensaje', ['warning', "El trámtie no existe."]);

            $this->crearModeloVacio();

        }else{

            if(in_array($this->tramite->servicio->categoria->nombre, ['Certificaciones', 'Comercio Inscripciones'])){

                $this->dispatch('mostrarMensaje', ['warning', "El trámtie no es una inscripción."]);

                $this->crearModeloVacio();

                $this->tramite = null;

                return;

            }

            if(in_array($this->tramite->servicio->clave_ingreso, ['DL28', 'DL19'])){

                $this->dispatch('mostrarMensaje', ['warning', "El trámtie no se recepciona."]);

                $this->crearModeloVacio();

                $this->tramite = null;

                return;

            }

            if($this->tramite->movimiento_registral){

                $this->dispatch('mostrarMensaje', ['warning', "El trámtie ya se encuentra en Sistema RPP."]);

                $this->crearModeloVacio();

                $this->tramite = null;

                return;

            }

            if($this->tramite->estado != 'pagado'){

                $this->modelo_editar = $this->tramite;

                $this->validarPago();

            }else{

                $this->modelo_editar = $this->tramite;

            }

        }

    }

    public function abrirModalEditar(Tramite $modelo){

        $this->dispatch('removeFiles');

        $this->selected_id = $modelo['id'];

        $this->modal = true;
        $this->editar = true;

    }

    public function guardar(){

        $this->validate();

        try {

            DB::transaction(function () {

                $this->tramite->update(['fecha_prelacion' => now()->format('Y-m-d H:i:s')]);

                $this->tramite->audits()->latest()->first()->update(['tags' => 'Recibió documentación']);

                if($this->documento){


                    if(app()->isProduction()){

                        $pdf = $this->documento->store(config('services.ses.ruta_documento_entrada'), 's3');

                    }else{

                        $pdf = $this->documento->store('/', 'documento_entrada');

                    }

                    File::create([
                        'fileable_id' => $this->selected_id,
                        'fileable_type' => 'App\Models\Tramite',
                        'url' => $pdf
                    ]);

                    $this->usuario_asignado = (new SistemaRppService())->insertarSistemaRpp($this->tramite);

                }

            });

            $this->dispatch('mostrarMensaje', ['success', "El trámite se envió correctamente a Sistema RPP. Asignado a: " . $this->usuario_asignado]);

            $this->resetearTodo();

            $this->dispatch('removeFiles');

        } catch (GeneralException $th) {

            $this->dispatch('mostrarMensaje', ['warning', $th->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al guardar documento del trámite id: " . $this->selected_id . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function recibir(){

        try {

            DB::transaction(function (){

                /* $this->tramite->update(['fecha_prelacion' => now()->format('Y-m-d H:i:s')]); */

                $this->tramite->audits()->latest()->first()->update(['tags' => 'Recibió documentación']);

                $this->usuario_asignado = (new SistemaRppService())->insertarSistemaRpp($this->tramite);

            });

            $this->dispatch('mostrarMensaje', ['success', "El trámite se envió correctamente a Sistema RPP. Asignado a: " . $this->usuario_asignado]);

            $this->resetearTodo();

        } catch (GeneralException $th) {

            $this->dispatch('mostrarMensaje', ['warning', $th->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al enviar tramitea rpp en recepción del trámite id: " . $this->selected_id . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    public function validarPago(){

        try {

            DB::transaction(function () {

                (new TramiteService($this->modelo_editar))->procesarPago();

                $this->dispatch('mostrarMensaje', ['success', "El trámite se validó con éxito."]);

            });

        } catch (GeneralException $th) {

            $this->dispatch('mostrarMensaje', ['warning', $th->getMessage()]);

            $this->tramite = null;

            $this->crearModeloVacio();

        } catch (\Throwable $th) {

            Log::error("Error al validar el trámite en recepción: " . $this->modelo_editar->año . '-' . $this->modelo_editar->numero_control . '-' . $this->modelo_editar->usuario . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

            $this->dispatch('mostrarMensaje', ['error', 'Hubo un error.']);

            $this->tramite = null;

            $this->crearModeloVacio();

        }

    }

    public function mount(){

        $this->años = Constantes::AÑOS;

        $this->año = now()->format('Y');

        $this->crearModeloVacio();

        array_push($this->fields, 'numero_control', 'usuario', 'tramite');

    }

    public function render()
    {

        return view('livewire.recepcion.recepcion')->extends('layouts.admin');
    }

}
