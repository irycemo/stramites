<?php

namespace App\Traits\Ventanilla;

use App\Models\Tramite;
use App\Models\Servicio;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\TramiteServiceException;
use App\Exceptions\SistemaRppServiceException;
use App\Http\Services\Tramites\TramiteService;

trait ComunTrait
{

    public Tramite $modelo_editar;

    public $editar = false;

    public $servicio;
    public $tramite;

    public $años;
    public $año;
    public $folio;
    public $usuario;

    public $adicionaTramite;
    public $tramitesAdicionados;
    public $tramiteAdicionadoSeleccionado;
    public $tramiteAdicionado;

    public $solicitantes;
    public $secciones;
    public $distritos;
    public $dependencias;
    public $notarias;
    public $notaria;
    public $documentos_entrada;

    public $mantener = false;
    public $tramiteMantener;

    protected $messages = [
        'modelo_editar.adiciona.required_if' => 'El campo trámite es obligatorio cuando el campo adiciona a otro tramite está seleccionado.',
        'modelo_editar.nombre_solicitante' => 'nombre del solicitante',
        'modelo_editar.numero_oficio' => 'número de oficio',
        'modelo_editar.nombre_solicitante' => 'nombre del solicitante',
        'modelo_editar.movimiento_registral.required_if' => 'No se ha vinculado el trámite original de copias.',
        'modelo_editar.fecha_emision.date_format' => 'El formato de fecha es incorrecto.',
    ];

    protected $validationAttributes  = [
        'modelo_editar.tomo_bis' => 'tomo bis',
        'modelo_editar.registro_bis' => 'registro bis',
        'modelo_editar.tipo_servicio' => 'tipo de servicio',
        'modelo_editar.numero_control' => 'número de control',
        'modelo_editar.numero_propiedad' => 'número de propiedad',
        'modelo_editar.adiciona' => 'trámite',
        'modelo_editar.seccion' => 'sección',
        'modelo_editar.numero_oficio' => 'número de oficio',
        'modelo_editar.numero_documento' => 'número de documento',
        'modelo_editar.folio_real' => 'folio_real',
        'modelo_editar.tipo_documento' => 'tipo de documento',
        'modelo_editar.nombre_autoridad' => 'nombre de la autoridad',
        'modelo_editar.autoridad_cargo' => 'cargo de la autoridad',
        'modelo_editar.fecha_emision' => 'fecha de emisión',
        'modelo_editar.valor_propiedad' => 'valor de la propiedad',
        'modelo_editar.tomo_gravamen' => 'tomo del gravamen',
        'modelo_editar.registro_gravamen' => 'registro del gravamen',
    ];

    public function getListeners()
    {
        return $this->listeners + [
            'cambioServicio' => 'cambiarFlags',
            'cargarTramite' => 'cargarTramite',
            'cargarTramiteMantener' => 'cargarTramiteMantener'
        ];
    }

    public function cargarTramite(Tramite $tramite){

        $this->tramite = $tramite;

    }

    public function cargarTramiteMantener($tramite){

        foreach ($tramite as $key => $value) {

            $this->modelo_editar->{$key} = $value;

        }

        $this->mantener = true;

    }

    public function cambiarFlags($servicio){

        $this->servicio = $servicio;

        $this->reset('tramite');

        $this->resetearTodo($borrado = true);

    }

    public function updatedMantener(){

        if(!$this->mantener){

            $this->dispatch('resetTramiteMantener');

            $this->resetearTodo($borrado = true);

        }

    }

    public function updatedModeloEditarAutoridadCargo(){

        if($this->modelo_editar->autoridad_cargo == 'foraneo'){

            $this->modelo_editar->foraneo = true;

        }else{

            $this->modelo_editar->foraneo = false;

        }

        $this->updatedModeloEditarTipoServicio();

    }

    public function updatedModeloEditarFolioReal(){

        if($this->modelo_editar->folio_real == ''){

            $this->modelo_editar->folio_real = null;

        }

        $this->modelo_editar->tomo = null;
        $this->modelo_editar->registro = null;
        $this->modelo_editar->numero_propiedad = null;
        $this->modelo_editar->distrito = null;
        $this->modelo_editar->seccion = null;

    }

    public function foraneo(){

        $foraneo = Servicio::where('clave_ingreso', 'D158')->first()[$this->modelo_editar->tipo_servicio] * $this->modelo_editar->cantidad;

        if($this->modelo_editar->foraneo){

            $this->modelo_editar->monto = $this->modelo_editar->monto + $foraneo;

        }else{

            $this->modelo_editar->monto = $this->modelo_editar->monto - $foraneo;
        }

    }

    public function updatedNotaria(){

        if($this->notaria == ""){

            $this->reset(['notaria']);

            $this->modelo_editar->numero_notaria = null;
            $this->modelo_editar->nombre_notario = null;
            $this->modelo_editar->nombre_solicitante = null;

            return;

        }

        $notaria = json_decode($this->notaria);

        $this->modelo_editar->numero_notaria = $notaria->numero;
        $this->modelo_editar->nombre_notario = $notaria->notario;
        $this->modelo_editar->nombre_solicitante = 'Notario ' . $notaria->numero . ' ' . $notaria->notario;

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

    public function validarPago(){

        try {

            DB::transaction(function () {

                (new TramiteService($this->tramite))->procesarPago();

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

    public function reimprimir(){

        $this->dispatch('imprimir_recibo', ['tramite' => $this->tramite->id]);

    }
}
