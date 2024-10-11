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

class Varios extends Component
{

    use ComunTrait;
    use ConsultaFolioTrait;

    public $flags = [
        'antecedente' => true,
        'adiciona' => false,
        'solicitante' => true,
        'nombre_solicitante' => false,
        'seccion' => true,
        'numero_oficio' => false,
        'tomo' => false,
        'registro' => false,
        'distrito' => true,
        'cantidad' => false,
        'numero_inmuebles' => false,
        'dependencias' => false,
        'notarias' => false,
        'tipo_servicio' => true,
        'observaciones' => true,
        'tipo_tramite' => false,
        'documento' => true,
        'email' => false,
    ];

    protected function rules(){
        return [
            'servicio' => 'required',
            'modelo_editar.id_servicio' => 'required',
            'modelo_editar.solicitante' => 'required',
            'modelo_editar.nombre_solicitante' => 'required',
            'modelo_editar.numero_oficio' => Rule::requiredIf($this->modelo_editar->solicitante == 'Oficialia de partes'),
            'modelo_editar.tomo' => Rule::requiredIf($this->modelo_editar->folio_real == null && $this->servicio['clave_ingreso'] != 'D110'),
            'modelo_editar.tomo_bis' => 'nullable',
            'modelo_editar.registro' => Rule::requiredIf($this->modelo_editar->folio_real == null && $this->servicio['clave_ingreso'] != 'D110'),
            'modelo_editar.registro_bis' => 'nullable',
            'modelo_editar.distrito' => Rule::requiredIf($this->modelo_editar->folio_real == null && $this->servicio['clave_ingreso'] != 'D110'),
            'modelo_editar.seccion' => Rule::requiredIf($this->modelo_editar->folio_real == null && $this->servicio['clave_ingreso'] != 'D110'),
            'modelo_editar.monto' => 'nullable',
            'modelo_editar.tipo_servicio' => 'required',
            'modelo_editar.tipo_tramite' => 'required',
            'modelo_editar.cantidad' => 'required|numeric|min:1',
            'modelo_editar.observaciones' => 'nullable',
            'modelo_editar.folio_real' => 'nullable',
            'modelo_editar.numero_propiedad' => Rule::requiredIf($this->modelo_editar->folio_real == null && $this->servicio['clave_ingreso'] != 'D110'),
            'modelo_editar.procedencia' => 'nullable',
            'modelo_editar.fecha_emision' => [
                                                Rule::requiredIf(!in_array($this->servicio['clave_ingreso'], ['DL19', 'D112'])),
                                                'nullable',
                                                'date_format:Y-m-d'
                                            ],
            'modelo_editar.numero_documento' => 'nullable',
            'modelo_editar.nombre_autoridad' => Rule::requiredIf(!in_array($this->servicio['clave_ingreso'], ['DL19', 'D112'])),
            'modelo_editar.autoridad_cargo' => Rule::requiredIf(!in_array($this->servicio['clave_ingreso'], ['DL19', 'D112'])),
            'modelo_editar.tipo_documento' => Rule::requiredIf(!in_array($this->servicio['clave_ingreso'], ['DL19', 'D112'])),
            'modelo_editar.email' => [
                                        'nullable',
                                        'email',
                                        Rule::requiredIf($this->servicio['clave_ingreso'] == 'DL19')
                                    ],
         ];
    }

    public function crearModeloVacio(){

        $this->modelo_editar = Tramite::make([
            'cantidad' => 1,
            'tipo_tramite' => 'normal',
            'tipo_servicio' => 'ordinario'
        ]);

    }

    public function resetearTodo($borrado = false){

        $this->resetErrorBag();

        $this->resetValidation();

        $this->reset([
            'flags',
            'editar',
        ]);

        if($this->servicio['clave_ingreso'] == 'DL19'){

            $this->flags['email'] = true;
            $this->flags['documento'] = false;

        }

        if($this->servicio['clave_ingreso'] == 'D112'){

            $this->flags['documento'] = false;

        }

        if($borrado) $this->crearModeloVacio();

        $this->modelo_editar->id_servicio = $this->servicio['id'];

    }

    public function updatedModeloEditarSolicitante(){

        $this->modelo_editar->nombre_solicitante = null;
        $this->modelo_editar->nombre_notario = null;
        $this->modelo_editar->numero_notaria = null;
        $this->modelo_editar->tipo_tramite = 'normal';
        $this->notaria = null;

        $this->flags['nombre_solicitante'] = false;
        $this->flags['dependencias'] = false;
        $this->flags['notarias'] = false;
        $this->flags['numero_oficio'] = false;

        $this->modelo_editar->tipo_tramite = 'normal';

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

        }elseif($this->modelo_editar->solicitante == 'SAT'){

            if(!auth()->user()->hasRole('Administrador')){

                $this->dispatch('mostrarMensaje', ['error', "No tienes permisos para esta opción."]);

                $this->modelo_editar->solicitante = null;

                return;

            }

            $this->flags['dependencias'] = true;
            $this->flags['numero_oficio'] = true;

        }elseif($this->modelo_editar->solicitante == "S.T.A.S.P.E."){

            $this->modelo_editar->nombre_solicitante = $this->modelo_editar->solicitante;
            $this->modelo_editar->tipo_servicio = "extra_urgente";

        }else{

            $this->modelo_editar->nombre_solicitante = $this->modelo_editar->solicitante;

        }

        $this->updatedModeloEditarTipoServicio();

    }

    public function updatedModeloEditarTipoTramite(){

        if(!$this->modelo_editar->tipo_servicio){

            $this->dispatch('mostrarMensaje', ['error', "Es necesario indique el tipo de servicio."]);

            return;

        }

        if($this->modelo_editar->tipo_tramite == 'exento'){

            $this->modelo_editar->monto = 0;

        }elseif($this->modelo_editar->tipo_tramite == 'complemento'){

            $this->modelo_editar->tipo_tramite = 'normal';

            $this->updatedModeloEditarTipoTramite();

        }elseif($this->modelo_editar->tipo_tramite == 'normal'){

            $this->modelo_editar->monto = $this->servicio[$this->modelo_editar->tipo_servicio] * $this->modelo_editar->cantidad;

        }

        if($this->modelo_editar->tipo_tramite == 'normal'){

            $this->flags['cantidad'] = true;

        }

    }

    public function updatedModeloEditarTipoServicio(){

        if($this->modelo_editar->id_servicio == ""){

            $this->dispatch('mostrarMensaje', ['error', "Debe seleccionar un servicio."]);

            $this->modelo_editar->tipo_servicio = 'ordinario';

            $this->modelo_editar->solicitante = null;

            return;
        }

        if($this->modelo_editar->tipo_servicio == 'ordinario'){

            if($this->servicio['ordinario'] == 0){

                $this->dispatch('mostrarMensaje', ['error', "No hay servicio ordinario para el servicio seleccionado."]);

                $this->modelo_editar->tipo_servicio = 'ordinario';

                return;

            }

            $this->modelo_editar->monto = $this->servicio['ordinario'] * $this->modelo_editar->cantidad;

            if($this->modelo_editar->tipo_tramite == 'complemento'){

                $this->modelo_editar->tipo_servicio = 'ordinario';
            }

        }
        elseif($this->modelo_editar->tipo_servicio == 'urgente'){

            if(now() > now()->startOfDay()->addHour(14) && !auth()->user()->hasRole('Administrador')){

                $this->dispatch('mostrarMensaje', ['error', "No se pueden hacer trámites urgentes despues de las 13:00 hrs."]);

                $this->modelo_editar->tipo_servicio = 'ordinario';
            }

            if($this->servicio['urgente'] == 0){

                $this->dispatch('mostrarMensaje', ['error', "No hay servicio urgente para el servicio seleccionado."]);

                $this->modelo_editar->tipo_servicio = 'ordinario';

                return;

            }

            $this->modelo_editar->monto = $this->servicio['urgente'] * $this->modelo_editar->cantidad;

            if($this->modelo_editar->tipo_tramite == 'complemento'){

                $this->modelo_editar->tipo_servicio = 'ordinario';
            }

        }
        elseif($this->modelo_editar->tipo_servicio == 'extra_urgente'){

            if(now() > now()->startOfDay()->addHour(12) && !auth()->user()->hasRole('Administrador')){

                $this->dispatch('mostrarMensaje', ['error', "No se pueden hacer trámites extra urgentes despues de las 11:00 hrs."]);

                $this->modelo_editar->tipo_servicio = 'ordinario';
            }

            if($this->servicio['extra_urgente']  == 0){

                $this->dispatch('mostrarMensaje', ['error', "No hay servicio extra urgente para el servicio seleccionado."]);

                $this->modelo_editar->tipo_servicio = 'ordinario';

                return;

            }

            $this->modelo_editar->monto = $this->servicio['extra_urgente'] * $this->modelo_editar->cantidad;

        }

        if($this->modelo_editar->solicitante == 'Oficialia de partes'){

            $this->modelo_editar->monto = 0;

        }

        $this->updatedModeloEditarTipoTramite();

    }

    public function updatedModeloEditarCantidad(){

        $this->updatedModeloEditarTipoServicio();

    }

    public function crear(){

        $this->validate();

        try {

            if(
                !in_array($this->servicio['clave_ingreso'], ['D110']) &&
                (
                    $this->modelo_editar->tomo &&
                    $this->modelo_editar->registro &&
                    $this->modelo_editar->numero_propiedad &&
                    $this->modelo_editar->distrito &&
                    $this->modelo_editar->seccion
                )

            ){

                $this->consultarFolioReal();

            }

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

        }catch (Exception $th) {

            $this->dispatch('mostrarMensaje', ['error', $th->getMessage()]);

        }catch (TramiteServiceException $th) {

            $this->dispatch('mostrarMensaje', ['error', $th->getMessage()]);

        }catch (\Throwable $th) {

            Log::error("Error al crear el trámite: " . $this->modelo_editar->año . '-' . $this->modelo_editar->numero_control . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

            $this->dispatch('mostrarMensaje', ['error', 'Hubo un error.']);

        }

    }

    public function editarTramite(){

        if($this->modelo_editar->isNot($this->tramite))
            $this->modelo_editar = $this->tramite;

        $this->reset(['tramite']);

        $this->servicio = $this->modelo_editar->servicio;

        $this->flags['solicitante'] = false;
        $this->flags['tipo_tramite'] = false;
        $this->flags['tipo_servicio'] = false;
        $this->flags['cantidad'] = false;
        $this->flags['adiciona'] = false;
        $this->flags['tomo'] = false;
        $this->flags['registro'] = false;
        $this->flags['observaciones'] = true;

        if($this->servicio['clave_ingreso'] == 'DL19'){

            $this->flags['email'] = true;
            $this->flags['documento'] = false;

        }

        if($this->servicio['clave_ingreso'] == 'D112'){

            $this->flags['documento'] = false;

        }

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

        $this->resetearTodo($borrado = true);

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

        if($this->tramiteMantener){

            foreach ($this->tramiteMantener as $key => $value) {

                $this->modelo_editar->{$key} = $value;

            }

            $this->mantener = true;

        }

    }

    public function render()
    {
        return view('livewire.entrada.varios');
    }
}
