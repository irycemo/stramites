<?php

namespace App\Livewire\Entrada;

use Exception;
use App\Models\Notaria;
use App\Models\Tramite;
use Livewire\Component;
use App\Models\Dependencia;
use App\Constantes\Constantes;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\TramiteServiceException;
use App\Http\Services\Tramites\TramiteService;
use App\Traits\Ventanilla\ComunTrait;
use App\Traits\Ventanilla\ConsultaFolioTrait;

class Gravamenes extends Component
{

    use ComunTrait;

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
            'modelo_editar.distrito' => Rule::requiredIf($this->modelo_editar->folio_real == null),
            'modelo_editar.seccion' => Rule::requiredIf($this->modelo_editar->folio_real == null),
            'modelo_editar.monto' => 'nullable',
            'modelo_editar.cantidad' => 'required|numeric|min:1',
            'modelo_editar.tipo_servicio' => 'required',
            'modelo_editar.tipo_tramite' => 'required',
            'modelo_editar.adiciona' => 'required_if:adicionaTramite,true',
            'modelo_editar.observaciones' => 'nullable',
            'modelo_editar.movimiento_registral' => 'nullable',
            'modelo_editar.procedencia' => 'nullable',
            'modelo_editar.fecha_emision' => 'required|date_format:Y-m-d',
            'modelo_editar.numero_documento' => 'nullable',
            'modelo_editar.numero_propiedad' => Rule::requiredIf($this->modelo_editar->folio_real == null),
            'modelo_editar.nombre_autoridad' => 'required',
            'modelo_editar.autoridad_cargo' => 'required',
            'modelo_editar.tipo_documento' => 'required',
            'modelo_editar.numero_oficio' => Rule::requiredIf($this->modelo_editar->solicitante == 'Oficialia de partes'),
            'modelo_editar.folio_real' => 'nullable',
            'modelo_editar.numero_inmuebles' => 'nullable',
            'modelo_editar.asiento_registral' => 'nullable',
            'modelo_editar.foraneo' => 'required',
         ];
    }

    public function crearModeloVacio(){

        $this->modelo_editar = Tramite::make([
            'cantidad' => 1,
            'tipo_tramite' => 'normal',
            'tipo_servicio' => 'ordinario',
            'foraneo' => false,
            'seccion' => 'Propiedad'
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

        $this->modelo_editar->monto = $this->servicio['ordinario'];

    }

    public function updatedModeloEditarSolicitante(){

        $this->modelo_editar->nombre_solicitante = null;
        $this->modelo_editar->nombre_notario = null;
        $this->modelo_editar->numero_notaria = null;
        $this->modelo_editar->tipo_tramite = 'normal';
        $this->notaria = null;

        $this->modelo_editar->tipo_tramite = 'normal';

        $this->updatedModeloEditarTipoServicio();

        $this->flags['nombre_solicitante'] = false;
        $this->flags['dependencias'] = false;
        $this->flags['notarias'] = false;
        $this->flags['numero_oficio'] = false;

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

    public function updatedModeloEditarTipoServicio(){

        if($this->modelo_editar->id_servicio == ""){

            $this->dispatch('mostrarMensaje', ['error', "Debe seleccionar un servicio."]);

            $this->modelo_editar->tipo_servicio = null;

            $this->modelo_editar->solicitante = null;

            return;
        }

        if($this->modelo_editar->tipo_servicio == 'ordinario'){

            if($this->servicio['ordinario'] == 0){

                $this->dispatch('mostrarMensaje', ['error', "No hay servicio ordinario para el servicio seleccionado."]);

                $this->modelo_editar->tipo_servicio = null;

                return;

            }

            $this->modelo_editar->monto = $this->servicio['ordinario'] * $this->modelo_editar->cantidad;

            if($this->modelo_editar->tipo_tramite == 'complemento'){

                $this->modelo_editar->tipo_servicio = null;
            }

        }
        elseif($this->modelo_editar->tipo_servicio == 'urgente'){

            if(now() > now()->startOfDay()->addHour(17) && !auth()->user()->hasRole('Administrador')){

                $this->dispatch('mostrarMensaje', ['error', "No se pueden hacer trámites urgentes despues de las 13:00 hrs."]);

                $this->modelo_editar->tipo_servicio = null;
            }

            if($this->servicio['urgente'] == 0){

                $this->dispatch('mostrarMensaje', ['error', "No hay servicio urgente para el servicio seleccionado."]);

                $this->modelo_editar->tipo_servicio = null;

                return;

            }

            $this->modelo_editar->monto = $this->servicio['urgente'] * $this->modelo_editar->cantidad;

            if($this->modelo_editar->tipo_tramite == 'complemento' && $this->tramiteAdicionado->tipo_servicio == 'urgente'){

                $this->modelo_editar->tipo_servicio = null;
            }

        }
        elseif($this->modelo_editar->tipo_servicio == 'extra_urgente'){

            if(now() > now()->startOfDay()->addHour(17) && !auth()->user()->hasRole('Administrador')){

                $this->dispatch('mostrarMensaje', ['error', "No se pueden hacer trámites extra urgentes despues de las 12:00 hrs."]);

                $this->modelo_editar->tipo_servicio = null;
            }

            /* if(!$this->modelo_editar->folio_real){

                $this->dispatch('mostrarMensaje', ['error', "No hay servicio extra urgente sin folio real."]);

                $this->modelo_editar->tipo_servicio = null;

                return;

            } */

            if($this->servicio['extra_urgente']  == 0){

                $this->dispatch('mostrarMensaje', ['error', "No hay servicio extra urgente para el servicio seleccionado."]);

                $this->modelo_editar->tipo_servicio = null;

                return;

            }

            $this->modelo_editar->monto = $this->servicio['extra_urgente'] * $this->modelo_editar->cantidad;

        }

        if($this->modelo_editar->solicitante == 'Oficialia de partes'){

            $this->modelo_editar->monto = 0;

        }

    }

    public function crear(){

        $this->validate();

        try {

            $this->consultarFolioReal();

            DB::transaction(function (){

                $tramite = (new TramiteService($this->modelo_editar))->crear();

                $this->dispatch('imprimir_recibo', ['tramite' => $tramite->id]);

                if(!$this->mantener){

                    $this->dispatch('reset');

                    $this->resetearTodo($borrado = true);

                }else{

                    $this->modelo_editar = $tramite->replicate();

                    $this->dispatch('matenerDatos', $this->modelo_editar);

                    $this->modelo_editar->observaciones = null;

                    $this->updatedModeloEditarTipoServicio();

                }

                $this->dispatch('mostrarMensaje', ['success', "El trámite se creó con éxito."]);

        });

        }catch (TramiteServiceException $th) {

            $this->dispatch('mostrarMensaje', ['error', $th->getMessage()]);

        } catch (Exception $th) {

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
        $this->flags['tipo_servicio'] = false;

        $this->editar = true;



    }

    public function mount(){

        $this->solicitantes = Constantes::SOLICITANTES;

        $this->secciones = Constantes::SECCIONES;

        $this->documentos_entrada = Constantes::DOCUMENTO_ENTRADA;

        if(auth()->user()->ubicacion == 'Regional 4'){

            $this->distritos = [2 => '02 Uruapan',];

        }else{

            $this->distritos = Constantes::DISTRITOS;

            unset($this->distritos[2]);

        }

        if(!cache()->get('dependencias')){

            $this->dependencias = Dependencia::orderBy('nombre')->get();

            cache()->put('dependencias', $this->dependencias);

        }else{

            $this->dependencias = cache()->get('dependencias');

        }

        if(!cache()->get('notarias')){

            $this->notarias = Notaria::orderBy('numero')->get();

            cache()->put('notarias', $this->notarias);

        }else{

            $this->notarias = cache()->get('notarias');

        }

        $this->resetearTodo($borrado = true);

        if($this->tramiteMantener){

            foreach ($this->tramiteMantener as $key => $value) {

                $this->modelo_editar->{$key} = $value;

            }

            $this->mantener = true;

        }

    }

    public function render()
    {
        return view('livewire.entrada.gravamenes');
    }
}
