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
use App\Http\Services\Tramites\TramiteService;
use App\Traits\Ventanilla\ComunTrait;
use Exception;

class InscripcionesPropiedad extends Component
{

    use ComunTrait;

    public $valor_propiedad = ['D115','D116', 'D113', 'D114'];

    public $numero_inmuebles = ['D123', 'D120', 'D121', 'D122','D119', 'D124', 'D125', 'D126'];

    public $subdivisiones = ['D121', 'D120', 'D123', 'D122', 'D119'];

    public $flags = [
        'adiciona' => false,
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
        'numero_propiedad' => false,
        'numero_oficio' => false,
        'distrito' => false,
        'cantidad' => false,
        'tramite_foraneo' => false
    ];

    protected function rules(){
        return [
            'servicio' => 'required',
            'modelo_editar.id_servicio' => 'required',
            'modelo_editar.solicitante' => 'required',
            'modelo_editar.nombre_solicitante' => 'required',
            'modelo_editar.tomo' => Rule::requiredIf($this->modelo_editar->folio_real == null && !in_array($this->servicio['clave_ingreso'], ['D157', 'D115','D116', 'D113', 'D114'])),
            'modelo_editar.tomo_bis' => 'nullable',
            'modelo_editar.registro' => Rule::requiredIf($this->modelo_editar->folio_real == null && !in_array($this->servicio['clave_ingreso'], ['D157', 'D115','D116', 'D113', 'D114'])),
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
            'modelo_editar.fecha_emision' => ['nullable', 'date_format:Y-m-d', Rule::requiredIf($this->servicio['clave_ingreso'] != 'D118')],
            'modelo_editar.numero_documento' => 'nullable',
            'modelo_editar.numero_propiedad' => ['nullable', Rule::requiredIf($this->modelo_editar->folio_real == null && !in_array($this->servicio['clave_ingreso'], ['D157', 'D115','D116', 'D113', 'D114'])), 'min:1'],
            'modelo_editar.nombre_autoridad' => ['nullable', Rule::requiredIf($this->servicio['clave_ingreso'] != 'D118')],
            'modelo_editar.autoridad_cargo' => ['nullable', Rule::requiredIf($this->servicio['clave_ingreso'] != 'D118')],
            'modelo_editar.tipo_documento' => ['nullable', Rule::requiredIf($this->servicio['clave_ingreso'] != 'D118')],
            'modelo_editar.valor_propiedad' => [Rule::requiredIf(in_array($this->servicio['clave_ingreso'], $this->valor_propiedad)), 'min:0'],
            'modelo_editar.numero_inmuebles' => Rule::requiredIf(in_array($this->servicio['clave_ingreso'], $this->numero_inmuebles)),
            'modelo_editar.numero_oficio' => Rule::requiredIf(in_array($this->modelo_editar->solicitante, ['Oficialia de partes','SAT'])),
            'modelo_editar.folio_real' => 'nullable',
            'modelo_editar.numero_inmuebles' => 'nullable',
            'modelo_editar.foraneo' => 'required',
            'año_foraneo' => Rule::requiredIf($this->flags['tramite_foraneo']),
            'folio_foraneo' => Rule::requiredIf($this->flags['tramite_foraneo']),
            'usuario_foraneo' => Rule::requiredIf($this->flags['tramite_foraneo']),
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

        if(in_array($this->servicio['clave_ingreso'], $this->valor_propiedad)){

            $this->flags['valor_propiedad'] = true;

        }

        if(in_array($this->servicio['clave_ingreso'], $this->numero_inmuebles)){

            $this->flags['numero_inmuebles'] = true;

        }

        if($this->servicio['clave_ingreso'] == 'D157'){

            $this->flags['antecedente'] = false;
            $this->flags['distrito'] = true;
            $this->flags['seccion'] = true;
            $this->flags['cantidad'] = false;
            $this->flags['numero_inmuebles'] = true;

        }

        $this->modelo_editar->id_servicio = $this->servicio['id'];

    }

    public function updatedAdicionaTramite(){

        $this->modelo_editar->adiciona = null;

        if(!$this->adicionaTramite){

            $this->reset(['tramiteAdicionadoSeleccionado', 'tramiteAdicionado', 'flags']);

            $this->modelo_editar->adiciona = null;
            $this->modelo_editar->tipo_tramite = 'normal';

            $this->flags['cantidad'] = true;
            $this->flags['tomo'] = true;
            $this->flags['registro'] = true;
            $this->flags['adiciona'] = true;
            $this->flags['observaciones'] = true;

        }else{

            $this->reset('flags');
            $this->flags['cantidad'] = false;
            $this->flags['solicitante'] = false;
            $this->flags['distrito'] = false;
            $this->flags['seccion'] = false;
            $this->flags['tipo_servicio'] = false;
            $this->flags['tipo_tramite'] = true;
            $this->flags['cantidad'] = true;
            $this->flags['observaciones'] = true;
            $this->flags['adiciona'] = true;

            $this->tramitesAdicionados = Tramite::whereIn('estado', ['pagado', 'rechazado'])
                                                ->where('id_servicio', $this->servicio['id'])
                                                ->get();

        }

    }

    public function updatedModeloEditarAdiciona(){

        $this->tramiteAdicionado = Tramite::find($this->modelo_editar->adiciona);

        if(!$this->tramiteAdicionado){

            $this->dispatch('mostrarMensaje', ['error', "Error al consultar trámite"]);

            $this->modelo_editar->adiciona = null;

            return;

        }

        if(!$this->tramiteAdicionado->movimiento_registral){

            $this->dispatch('mostrarMensaje', ['error', "El trámite " . $this->tramiteAdicionado->numero_control . " no esta dado de alta en Sistema RPP"]);

            $this->modelo_editar->adiciona = null;

            return;

        }

        $this->modelo_editar->solicitante = $this->tramiteAdicionado->solicitante;
        $this->modelo_editar->nombre_solicitante = $this->tramiteAdicionado->nombre_solicitante;
        $this->modelo_editar->tomo = $this->tramiteAdicionado->tomo;
        $this->modelo_editar->registro = $this->tramiteAdicionado->registro;
        $this->modelo_editar->distrito = $this->tramiteAdicionado->distrito;
        $this->modelo_editar->seccion = $this->tramiteAdicionado->seccion;
        $this->modelo_editar->tipo_servicio = $this->tramiteAdicionado->tipo_servicio;
        $this->modelo_editar->movimiento_registral = $this->tramiteAdicionado->movimiento_registral;

        $this->updatedModeloEditarTipoServicio();

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

            /* if(!auth()->user()->can('Trámite exento')){

                $this->dispatch('mostrarMensaje', ['error', "No tiene permiso para elaborar trámites exentos."]);

                $this->modelo_editar->tipo_tramite = 'normal';

                return;

            } */

            $this->modelo_editar->monto = 0;

        }elseif($this->modelo_editar->tipo_tramite == 'complemento'){

            if($this->tramiteAdicionado){

                $this->modelo_editar->monto = $this->servicio[$this->modelo_editar->tipo_servicio] * $this->tramiteAdicionado->cantidad - $this->servicio[$this->tramiteAdicionado->tipo_servicio] * $this->tramiteAdicionado->cantidad ;

                $this->modelo_editar->monto < 0 ? $this->modelo_editar->monto = 0 : $this->modelo_editar->monto = $this->modelo_editar->monto;

                $this->modelo_editar->cantidad = $this->tramiteAdicionado->cantidad;

                $this->flags['tipo_servicio'] = true;

            }else{

                $this->dispatch('mostrarMensaje', ['error', "Para el complemento es necesario seleccione el tramite al que adiciona."]);

                $this->modelo_editar->tipo_tramite = 'normal';

                $this->updatedModeloEditarTipoTramite();

            }

        }elseif($this->modelo_editar->tipo_tramite == 'normal'){

            $this->modelo_editar->monto = $this->servicio[$this->modelo_editar->tipo_servicio] * $this->modelo_editar->cantidad;

            if($this->modelo_editar->adiciona)
                $this->flags['tipo_servicio'] = false;

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

            $this->modelo_editar->monto = $this->servicio['ordinario'] * $this->modelo_editar->cantidad;

            if($this->modelo_editar->monto == 0){

                $this->dispatch('mostrarMensaje', ['error', "No hay servicio ordinario para el servicio seleccionado."]);

                $this->modelo_editar->tipo_servicio = null;
            }

            if($this->modelo_editar->tipo_tramite == 'complemento'){

                $this->modelo_editar->tipo_servicio = null;
            }

        }
        elseif($this->modelo_editar->tipo_servicio == 'urgente'){

            $this->modelo_editar->monto = $this->servicio['urgente'] * $this->modelo_editar->cantidad;

            /* if(now() > now()->startOfDay()->addHour(14) && !auth()->user()->hasRole('Administrador')){

                $this->dispatch('mostrarMensaje', ['error', "No se pueden hacer trámites urgentes despues de las 13:00 hrs."]);

                $this->modelo_editar->tipo_servicio = null;
            } */

            if($this->modelo_editar->monto == 0){

                $this->dispatch('mostrarMensaje', ['error', "No hay servicio urgente para el servicio seleccionado."]);

                $this->modelo_editar->tipo_servicio = null;
            }

            if($this->modelo_editar->tipo_tramite == 'complemento' && $this->tramiteAdicionado->tipo_servicio == 'urgente'){

                $this->modelo_editar->tipo_servicio = null;
            }

        }
        elseif($this->modelo_editar->tipo_servicio == 'extra_urgente'){

            $this->modelo_editar->monto = $this->servicio['extra_urgente'] * $this->modelo_editar->cantidad;

           /*  if(now() > now()->startOfDay()->addHour(12) && !auth()->user()->hasRole('Administrador')){

                $this->dispatch('mostrarMensaje', ['error', "No se pueden hacer trámites extra urgentes despues de las 11:00 hrs."]);

                $this->modelo_editar->tipo_servicio = null;
            } */

            if($this->modelo_editar->monto == 0){

                $this->dispatch('mostrarMensaje', ['error', "No hay servicio extra urgente para el servicio seleccionado."]);

                $this->modelo_editar->tipo_servicio = null;
            }

        }

        if($this->modelo_editar->solicitante == 'Oficialia de partes'){

            $this->modelo_editar->monto = 0;

        }

        /* $this->updatedModeloEditarTipoTramite(); */

        if($this->modelo_editar->foraneo)
            $this->foraneo();

    }

    public function updatedModeloEditarNumeroInmuebles(){

        if($this->servicio['clave_ingreso'] != 'D157'){

            $this->modelo_editar->cantidad = $this->modelo_editar->numero_inmuebles;

            $this->updatedModeloEditarTipoServicio();

        }

    }

    public function updatedModeloEditarValorPropiedad(){

        if($this->servicio['clave_ingreso'] == 'D115' && $this->modelo_editar->valor_propiedad > 946110){

            $this->modelo_editar->valor_propiedad = 0;

            $this->dispatch('mostrarMensaje', ['error', 'Verificar el valor de propiedad.']);

        }elseif($this->servicio['clave_ingreso'] == 'D116' && $this->modelo_editar->valor_propiedad < 946110){

            $this->modelo_editar->valor_propiedad = 0;

            $this->dispatch('mostrarMensaje', ['error', 'Verificar el valor de propiedad.']);

        }

    }

    public function crear(){

        $this->validate();

        try {

            if($this->flags['tramite_foraneo']) $this->buscarforaneo();

            /* , 'D115','D116', 'D113', 'D114' */
            if(
                !in_array($this->servicio['clave_ingreso'], ['D157']) &&
                (
                    $this->modelo_editar->tomo &&
                    $this->modelo_editar->registro &&
                    $this->modelo_editar->numero_propiedad &&
                    $this->modelo_editar->distrito &&
                    $this->modelo_editar->seccion ||
                    $this->modelo_editar->folio_real
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
            Log::error($th);
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
        $this->flags['tomo'] = true;
        $this->flags['registro'] = true;
        $this->flags['observaciones'] = true;

        $this->editar = true;

    }

    public function mount(){

        $this->solicitantes = Constantes::SOLICITANTES;

        $this->secciones = Constantes::SECCIONES;

        $this->documentos_entrada = Constantes::DOCUMENTOS_DE_ENTRADA;

        $this->cargos_autoridad = Constantes::CARGO_AUTORIDAD;

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

        $this->años = Constantes::AÑOS;

        $this->año_foraneo = now()->format('Y');

    }

    public function render()
    {
        return view('livewire.entrada.inscripciones-propiedad');
    }
}

