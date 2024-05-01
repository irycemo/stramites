<?php

namespace App\Livewire\Entrada;

use App\Models\Notaria;
use App\Models\Tramite;
use Livewire\Component;
use App\Models\Dependencia;
use App\Constantes\Constantes;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\TramiteServiceException;
use App\Exceptions\SistemaRppServiceException;
use App\Http\Services\Tramites\TramiteService;

class Gravamenes extends Component
{

    public Tramite $modelo_editar;

    public $editar = false;

    public $servicio;
    public $tramite;

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

    public $tramiteCreado;

    public $flags = [
        'adiciona' => true,
        'solicitante' => true,
        'nombre_solicitante' => false,
        'antecedente' => true,
        'documento' => true,
        'dependencias' => false,
        'notarias' => false,
        'tipo_servicio' => true,
        'observaciones' => true,
        'tipo_tramite' => false,
        'valor_propiedad' => false,
        'numero_inmuebles' => false,
        'numero_oficio' => false,
        'antecedente_gravamen' => false
    ];

    protected function rules(){
        return [
            'servicio' => 'required',
            'modelo_editar.id_servicio' => 'required',
            'modelo_editar.solicitante' => 'required',
            'modelo_editar.nombre_solicitante' => 'required',
            'modelo_editar.tomo' => Rule::requiredIf($this->modelo_editar->folio_real == null),
            'modelo_editar.tomo_bis' => 'nullable',
            'modelo_editar.registro' => Rule::requiredIf($this->modelo_editar->folio_real == null),
            'modelo_editar.registro_bis' => 'nullable',
            'modelo_editar.distrito' => 'required',
            'modelo_editar.seccion' => 'required',
            'modelo_editar.monto' => 'nullable',
            'modelo_editar.cantidad' => 'required|numeric|min:1',
            'modelo_editar.tipo_servicio' => 'required',
            'modelo_editar.tipo_tramite' => 'required',
            'modelo_editar.adiciona' => 'required_if:adicionaTramite,true',
            'modelo_editar.observaciones' => 'nullable',
            'modelo_editar.movimiento_registral' => 'nullable',
            'modelo_editar.procedencia' => 'nullable',
            'modelo_editar.fecha_emision' => 'required',
            'modelo_editar.numero_documento' => 'required',
            'modelo_editar.numero_propiedad' => 'required',
            'modelo_editar.nombre_autoridad' => 'required',
            'modelo_editar.autoridad_cargo' => 'required',
            'modelo_editar.tipo_documento' => 'required',
            'modelo_editar.numero_oficio' => Rule::requiredIf($this->modelo_editar->solicitante == 'Oficialia de partes'),
            'modelo_editar.folio_real' => 'nullable',
            'modelo_editar.numero_inmuebles' => 'nullable',
            'modelo_editar.foraneo' => 'required',
            'modelo_editar.tomo_gravamen' => Rule::requiredIf($this->servicio['clave_ingreso'] === 'DL66'),
            'modelo_editar.registro_gravamen' => Rule::requiredIf($this->servicio['clave_ingreso'] === 'DL66'),
         ];
    }

    protected $messages = [
        'modelo_editar.adiciona.required_if' => 'El campo trámite es obligatorio cuando el campo adiciona a otro tramite está seleccionado.',
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
    ];

    protected $listeners = [
        'cambioServicio' => 'cambiarFlags',
        'cargarTramite' => 'cargarTramite'
    ];

    public function crearModeloVacio(){

        $this->modelo_editar = Tramite::make([
            'cantidad' => 1,
            'tipo_tramite' => 'normal',
            'tipo_servicio' => 'ordinario',
            'foraneo' => false
        ]);

    }

    public function resetearTodo($borrado = false){

        $this->resetErrorBag();

        $this->resetValidation();

        $this->reset([
            'adicionaTramite',
            'tramitesAdicionados',
            'tramiteAdicionadoSeleccionado',
            'tramiteAdicionado',
            'flags',
            'editar',
        ]);

        if($borrado)
            $this->crearModeloVacio();

        $this->modelo_editar->id_servicio = $this->servicio['id'];

        if($this->servicio['clave_ingreso'] === 'DL66'){

            $this->flags['antecedente_gravamen'] = true;

        }

    }

    public function cargarTramite(Tramite $tramite){

        $this->tramite = $tramite;

    }

    public function cambiarFlags($servicio){

        $this->servicio = $servicio;

        $this->reset('tramite');

        $this->resetearTodo($borrado = true);

    }

    public function updatedModeloEditarSolicitante(){

        $this->modelo_editar->nombre_solicitante = null;
        $this->modelo_editar->nombre_notario = null;
        $this->modelo_editar->numero_notaria = null;
        $this->notaria = null;

        $this->flags['nombre_solicitante'] = false;
        $this->flags['dependencias'] = false;
        $this->flags['notarias'] = false;

        if($this->modelo_editar->solicitante == 'Usuario'){

            $this->flags['nombre_solicitante'] = true;

        }elseif($this->modelo_editar->solicitante == 'Notaría'){

            $this->flags['notarias'] = true;

        }elseif($this->modelo_editar->solicitante == 'Oficialia de partes'){

            if(!auth()->user()->hasRole(['Oficialia de partes', 'Administrador'])){

                $this->dispatch('mostrarMensaje', ['error', "No tienes permisos para esta opción."]);

                $this->modelo_editar->solicitante = null;

                return;

            }

            $this->flags['dependencias'] = true;
            $this->flags['numero_oficio'] = true;

            $this->modelo_editar->monto = 0;
            $this->modelo_editar->tipo_tramite = 'exento';

        }elseif($this->modelo_editar->solicitante == "S.T.A.S.P.E."){

            $this->modelo_editar->nombre_solicitante = $this->modelo_editar->solicitante;
            $this->modelo_editar->tipo_servicio = "extra_urgente";

        }else{

            $this->modelo_editar->nombre_solicitante = $this->modelo_editar->solicitante;

        }

    }

    public function updatedModeloEditarAutoridadCargo(){

        if($this->modelo_editar->autoridad_cargo == 'foraneo'){

            $this->modelo_editar->foraneo = true;

        }else{

            $this->modelo_editar->foraneo = false;

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
        $this->modelo_editar->nombre_solicitante = $notaria->numero . ' ' .$notaria->notario;

    }

    public function crear(){

        $this->validate();

        try {

            DB::transaction(function (){

                $this->tramiteCreado = (new TramiteService($this->modelo_editar))->crear();

                $this->dispatch('crearBatch', $this->tramiteCreado->id);

                $this->dispatch('reset');

                $this->resetearTodo($borrado = true);

                $this->dispatch('mostrarMensaje', ['success', "El trámite se creó con éxito."]);

        });

        }catch (TramiteServiceException $th) {

            $this->dispatch('mostrarMensaje', ['error', $th->getMessage()]);

        }catch (\Throwable $th) {

            Log::error("Error al crear el trámite: " . $this->modelo_editar->año . '-' . $this->modelo_editar->numero_control . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

            $this->dispatch('mostrarMensaje', ['error', 'Hubo un error.']);
            $this->resetearTodo($borrado = true);

        }

    }

    public function editarTramite(){

        if($this->modelo_editar->isNot($this->tramite))
            $this->modelo_editar = $this->tramite;

        $this->reset(['tramite']);

        $this->servicio = $this->modelo_editar->servicio;

        $this->flags['solicitante'] = false;
        $this->flags['observaciones'] = true;

        $this->editar = true;

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

    public function mount(){

        $this->solicitantes = Constantes::SOLICITANTES;

        $this->secciones = Constantes::SECCIONES;

        if(auth()->user()->ubicacion == 'Regional 4'){

            $this->distritos = [2 => '02 Uruapan',];

        }else{

            $this->distritos = Constantes::DISTRITOS;

            unset($this->distritos[2]);

        }

        $this->dependencias = Dependencia::orderBy('nombre')->get();

        $this->notarias = Notaria::orderBy('numero')->get();

        $this->resetearTodo($borrado = true);
    }

    public function render()
    {
        return view('livewire.entrada.gravamenes');
    }
}
