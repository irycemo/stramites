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
use App\Traits\Ventanilla\ComunTrait;
use App\Exceptions\TramiteServiceException;
use App\Http\Services\Tramites\TramiteService;

class Subdivisiones extends Component
{

    use ComunTrait;

    public $flags = [
        'solicitante' => true,
        'nombre_solicitante' => false,
        'documento' => true,
        'dependencias' => false,
        'notarias' => false,
        'observaciones' => true,
        'numero_oficio' => false,
        'folio_real' => true,
        'numero_inmuebles' => true,
        'tramite_foraneo' => false,
        'antecedente' => false,
        'numero_propiedad' => false,
        'tipo_servicio' => true,
    ];

    protected function rules(){
        return [
            'servicio' => 'required',
            'modelo_editar.id_servicio' => 'required',
            'modelo_editar.solicitante' => 'required',
            'modelo_editar.nombre_solicitante' => 'required',
            'modelo_editar.numero_oficio' => Rule::requiredIf(in_array($this->modelo_editar->solicitante, ['Oficialia de partes','SAT'])),
            'modelo_editar.tipo_servicio' => 'required',
            'modelo_editar.tipo_tramite' => 'required',
            'modelo_editar.cantidad' => 'required|numeric|min:1',
            'modelo_editar.observaciones' => 'nullable',
            'modelo_editar.folio_real' => 'nullable',
            'modelo_editar.procedencia' => 'nullable',
            'modelo_editar.monto' => 'nullable',
            'modelo_editar.fecha_emision' => [
                                                'nullable',
                                                'date_format:Y-m-d'
                                            ],
            'modelo_editar.numero_documento' => 'nullable',
            'modelo_editar.nombre_autoridad' => 'required',
            'modelo_editar.autoridad_cargo' => 'required',
            'modelo_editar.tipo_documento' => 'required',
            'modelo_editar.numero_inmuebles' => 'required|numeric|min:1',
            'año_foraneo' => Rule::requiredIf($this->flags['tramite_foraneo']),
            'folio_foraneo' => Rule::requiredIf($this->flags['tramite_foraneo']),
            'usuario_foraneo' => Rule::requiredIf($this->flags['tramite_foraneo']),
            'modelo_editar.tomo' => Rule::requiredIf($this->modelo_editar->folio_real == null && !in_array($this->servicio['clave_ingreso'], ['D128'])),
            'modelo_editar.tomo_bis' => 'nullable',
            'modelo_editar.registro' => Rule::requiredIf($this->modelo_editar->folio_real == null && !in_array($this->servicio['clave_ingreso'], ['D128'])),
            'modelo_editar.registro_bis' => 'nullable',
            'modelo_editar.distrito' => Rule::requiredIf($this->modelo_editar->folio_real == null),
            'modelo_editar.seccion' => Rule::requiredIf($this->modelo_editar->folio_real == null),
            'modelo_editar.numero_propiedad' => ['nullable', Rule::requiredIf($this->modelo_editar->folio_real == null && !in_array($this->servicio['clave_ingreso'], ['D128'])), 'min:1'],
         ];
    }

    public function crearModeloVacio(){

        $this->modelo_editar = Tramite::make([
            'cantidad' => 1,
            'tipo_tramite' => 'normal',
            'tipo_servicio' => 'ordinario',
            'seccion' => 'Subdivisiones',
            'numero_inmuebles' => 1
        ]);

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

        }elseif($this->modelo_editar->solicitante == 'SAT'){

            if(!auth()->user()->hasRole('Administrador')){

                $this->dispatch('mostrarMensaje', ['error', "No tienes permisos para esta opción."]);

                $this->modelo_editar->solicitante = null;

                return;

            }

            $this->modelo_editar->nombre_solicitante = $this->modelo_editar->solicitante;

            $this->flags['numero_oficio'] = true;

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

    public function updatedModeloEditarNumeroInmuebles(){

        if($this->modelo_editar->numero_inmuebles == 1){

            $this->modelo_editar->cantidad = $this->modelo_editar->numero_inmuebles + 1;

        }else{

            $this->modelo_editar->cantidad = $this->modelo_editar->numero_inmuebles;

        }

        $this->updatedModeloEditarTipoServicio();

    }

    public function resetearTodo($borrado = false){

        $this->resetErrorBag();

        $this->resetValidation();

        $this->reset([
            'flags',
            'editar',
        ]);

        if($borrado) $this->crearModeloVacio();

        $this->modelo_editar->id_servicio = $this->servicio['id'];

        $this->modelo_editar->cantidad = $this->modelo_editar->numero_inmuebles + 1;

        if($this->servicio['clave_ingreso'] == 'D127'){

            $this->flags['antecedente'] = true;
            $this->flags['numero_propiedad'] = true;

            $this->documentos_entrada = Constantes::DOCUMENTOS_DE_ENTRADA;

        }else{

            $this->documentos_entrada = ['ESCRITURA PÚBLICA'];

        }

    }

    public function crear(){

        $this->validate();

        try {

            $this->modelo_editar->numero_inmuebles = $this->modelo_editar->cantidad;

            if($this->flags['tramite_foraneo']) $this->buscarforaneo();

            $this->consultarFolioReal();

            if(!$this->matriz && $this->modelo_editar->folio_real && $this->servicio['nombre'] != 'Subdivisiones por cada inmueble'){

                throw new Exception("El folio real no es matriz.");

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
        $this->flags['numero_inmuebles'] = true;

        $this->editar = true;



    }

    public function mount(){

        $this->solicitantes = Constantes::SOLICITANTES;

        if($this->servicio['clave_ingreso'] == 'D127'){

            $this->documentos_entrada = Constantes::DOCUMENTOS_DE_ENTRADA;

        }else{

            $this->documentos_entrada = ['ESCRITURA PÚBLICA'];

        }

        $this->cargos_autoridad = Constantes::CARGO_AUTORIDAD;

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

        $this->años = Constantes::AÑOS;

        $this->año_foraneo = now()->format('Y');

    }

    public function render()
    {
        return view('livewire.entrada.subdivisiones');
    }
}
