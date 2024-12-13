<?php

namespace App\Livewire\Entrada;

use Exception;
use App\Models\Notaria;
use App\Models\Tramite;
use Livewire\Component;
use App\Models\Servicio;
use App\Models\Dependencia;
use App\Constantes\Constantes;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\TramiteServiceException;
use App\Http\Services\Tramites\TramiteService;
use App\Traits\Ventanilla\ComunTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Certificaciones extends Component
{

    use ComunTrait;

    public $flags = [
        'antecedente' => false,
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
        'numero_propiedad' => false,
        'dependencias' => false,
        'notarias' => false,
        'tipo_servicio' => true,
        'observaciones' => true,
        'tipo_tramite' => false,
    ];

    protected function rules(){
        return [
            'servicio' => 'required',
            'modelo_editar.id_servicio' => 'required',
            'modelo_editar.solicitante' => 'required',
            'modelo_editar.nombre_solicitante' => 'required',
            'modelo_editar.numero_oficio' => Rule::requiredIf(in_array($this->modelo_editar->solicitante, ['Oficialia de partes','SAT'])),
            'modelo_editar.tomo' => [Rule::requiredIf($this->servicio['clave_ingreso'] == 'DL14' || $this->servicio['clave_ingreso'] == 'DL13'), 'numeric'],
            'modelo_editar.tomo_bis' => 'nullable',
            'modelo_editar.registro' => [Rule::requiredIf($this->servicio['clave_ingreso'] == 'DL14' || $this->servicio['clave_ingreso'] == 'DL13'), 'numeric'],
            'modelo_editar.registro_bis' => 'nullable',
            'modelo_editar.distrito' => Rule::requiredIf($this->modelo_editar->folio_real == null),
            'modelo_editar.seccion' => Rule::requiredIf($this->modelo_editar->folio_real == null),
            'modelo_editar.monto' => 'nullable',
            'modelo_editar.tipo_servicio' => 'required',
            'modelo_editar.tipo_tramite' => 'required',
            'modelo_editar.cantidad' => 'required|numeric|min:1',
            'modelo_editar.adiciona' => 'required_if:adicionaTramite,true',
            'modelo_editar.observaciones' => 'nullable',
            'modelo_editar.folio_real' => 'nullable',
            'modelo_editar.numero_propiedad' => ['nullable', 'numeric', Rule::requiredIf($this->modelo_editar->folio_real == null && !in_array($this->servicio['clave_ingreso'], ['DL14', 'DL13', 'DC93', 'DL10' , 'DL11'])), 'min:1'],
            'modelo_editar.movimiento_registral' => Rule::requiredIf(
                                                    $this->servicio['clave_ingreso'] == 'DL14' && $this->tramiteAdicionado && in_array($this->tramiteAdicionado->servicio->clave_ingreso, ['DL13', 'DL14']) ||
                                                    $this->servicio['clave_ingreso'] == 'DL13' && $this->tramiteAdicionado && in_array($this->tramiteAdicionado->servicio->clave_ingreso, ['DL13', 'DL14'])
                                                ),
         ];
    }

    public function crearModeloVacio(){

        $this->modelo_editar = Tramite::make([
            'cantidad' => 1,
            'tipo_tramite' => 'normal',
            'tipo_servicio' => 'ordinario',
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

        if($borrado) $this->crearModeloVacio();

        if($this->servicio['clave_ingreso'] == 'DL14' || $this->servicio['clave_ingreso'] == 'DL13'){

            $this->flags['cantidad'] = true;
            $this->flags['tomo'] = true;
            $this->flags['registro'] = true;
            $this->flags['adiciona'] = true;
            $this->flags['observaciones'] = true;

        }elseif($this->servicio['clave_ingreso'] == 'DL07'){

            $this->flags['distrito'] = false;
            $this->flags['seccion'] = false;
            $this->flags['antecedente'] = true;
            $this->flags['observaciones'] = true;

        }elseif($this->servicio['clave_ingreso'] == 'DL10' || $this->servicio['clave_ingreso'] == 'DL11'){

            $this->flags['distrito'] = false;
            $this->flags['seccion'] = false;
            $this->flags['antecedente'] = true;
            $this->flags['cantidad'] = true;

        }

        $this->modelo_editar->id_servicio = $this->servicio['id'];

        if($this->modelo_editar->solicitante == 'Oficialia de partes') $this->flags['numero_oficio'] = true;

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

        }

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

        }elseif($this->modelo_editar->solicitante == 'SAT'){

            if(!auth()->user()->hasRole('Administrador')){

                $this->dispatch('mostrarMensaje', ['error', "No tienes permisos para esta opción."]);

                $this->modelo_editar->solicitante = null;

                return;

            }

            $this->modelo_editar->nombre_solicitante = $this->modelo_editar->solicitante;

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

                $this->flags['cantidad'] = false;
                $this->flags['tipo_servicio'] = true;

            }else{

                $this->dispatch('mostrarMensaje', ['error', "Para el complemento es necesario seleccione el tramite al que adiciona."]);

                $this->modelo_editar->tipo_tramite = 'normal';

                $this->updatedModeloEditarTipoTramite();

            }

        }elseif($this->modelo_editar->tipo_tramite == 'normal'){

            $this->modelo_editar->monto = $this->servicio[$this->modelo_editar->tipo_servicio] * $this->modelo_editar->cantidad;

            /* $this->flags['cantidad'] = true; */

        }

        if($this->modelo_editar->tipo_tramite == 'normal' && $this->tramiteAdicionado && in_array($this->tramiteAdicionado->servicio->clave_ingreso, ['DL13', 'DL14'])){

            $this->flags['tipo_servicio'] = false;
            $this->flags['cantidad'] = true;

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

        $this->updatedModeloEditarTipoTramite();

    }

    public function updatedModeloEditarNumeroPaginas(){

        $this->updatedModeloEditarTipoServicio();

    }

    public function updatedModeloEditarCantidad(){

        $this->updatedModeloEditarTipoServicio();

    }

    public function crearTramiteConsulta():Tramite
    {

        $servicio = Servicio::where('clave_ingreso', 'DC93')->first();

        if(!$servicio){

            throw new ModelNotFoundException("No se encontró el servcio DC93");

        }

        $consulta = $this->modelo_editar->replicate();
        $consulta->id_servicio = $servicio->id;
        $consulta->estado = 'nuevo';
        $consulta->tipo_servicio = 'ordinario';
        $consulta->monto = $this->modelo_editar->solicitante == 'Oficialia de partes' ? 0 : $servicio->ordinario;
        $consulta->año = now()->format('Y');
        $consulta->usuario = auth()->user()->clave;
        $consulta->numero_control = (Tramite::where('año', $consulta->año)->where('usuario', $consulta->usuario)->max('numero_control') ?? 0) + 1;

        $tramite = (new TramiteService($consulta))->crear();

        return $tramite;

    }

    public function crear(){

        $this->validate();

        try {

            DB::transaction(function (){

                /* Copias */
                if($this->servicio['clave_ingreso'] == 'DL14' || $this->servicio['clave_ingreso'] == 'DL13'){

                    /* Copias nuevas */
                    if($this->modelo_editar->adiciona == null){

                        $consulta = $this->crearTramiteConsulta();

                        $this->modelo_editar->adiciona = $consulta->id;

                        $this->modelo_editar->monto = $this->modelo_editar->monto + $consulta->monto;

                        $tramite = (new TramiteService($this->modelo_editar))->crear();

                    /* Copias que adicionan a otras copias */
                    }else{

                        $tramite = (new TramiteService($this->modelo_editar))->crear();

                    }

                /* Certificado de gravamen */
                }elseif($this->servicio['clave_ingreso'] == 'DL07'){

                    $this->consultarFolioReal();

                    $tramite = (new TramiteService($this->modelo_editar))->crear();

                /* Certificado de propiedad - Certificado con medidas y linderos */
                }elseif($this->servicio['clave_ingreso'] == 'DL10' || $this->servicio['clave_ingreso'] == 'DL11'){

                    if($this->modelo_editar->tomo || $this->modelo_editar->registro ||$this->modelo_editar->numero_propiedad){

                        $this->validate([
                            'modelo_editar.tomo' => 'required',
                            'modelo_editar.registro' => 'required',
                            'modelo_editar.numero_propiedad' => 'required'
                        ]);

                        $this->consultarFolioReal();

                    }elseif($this->modelo_editar->folio_real){

                        $this->consultarFolioReal();

                    }

                    $tramite = (new TramiteService($this->modelo_editar))->crear();

                /* Consultas */
                }else{

                    $tramite = (new TramiteService($this->modelo_editar))->crear();

                }

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

        } catch (Exception $th) {

            Log::error("Error al crear el trámite: " . $this->modelo_editar->año . '-' . $this->modelo_editar->numero_control . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

            $this->dispatch('mostrarMensaje', ['error', $th->getMessage()]);

        } catch (TramiteServiceException $th) {

            Log::error("Error al crear el trámite: " . $this->modelo_editar->año . '-' . $this->modelo_editar->numero_control . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

            $this->dispatch('mostrarMensaje', ['error', $th->getMessage()]);

        }  catch (\Throwable $th) {

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

        if($this->modelo_editar->solicitante == 'Oficialia de partes') $this->flags['numero_oficio'] = true;

        $this->editar = true;

    }

    public function buscarTramiteAdiciona(){

        $this->tramiteAdicionado = Tramite::where('año', $this->año)
                                        ->where('numero_control', $this->folio)
                                        ->where('usuario', $this->usuario)
                                        ->whereIn('estado', ['pagado', 'rechazado'])
                                        ->whereHas('servicio', function($q){
                                            $q->whereIn('clave_ingreso', ['DC93','DL13', 'DL14']);
                                        })
                                        ->first();

        if(!$this->tramiteAdicionado){

            $this->dispatch('mostrarMensaje', ['error', "No se encontro el trámite."]);

            $this->modelo_editar->adiciona = null;

            $this->updatedAdicionaTramite();

            return;

        }

        if(!$this->tramiteAdicionado->movimiento_registral){

            $this->dispatch('mostrarMensaje', ['error', "El trámite " . $this->tramiteAdicionado->año . '-' . $this->tramiteAdicionado->numero_control . '-' . $this->tramiteAdicionado->usuario . " no esta dado de alta en Sistema RPP"]);

            $this->modelo_editar->adiciona = null;

            return;

        }

        if($this->tramiteAdicionado->servicio->clave_ingreso == 'DC93'){

            if($this->tramiteAdicionado->adicionadoPor->count() >= 5){

                $this->dispatch('mostrarMensaje', ['error', "El trámite " .  $this->tramiteAdicionado->año . '-' . $this->tramiteAdicionado->numero_control . '-' . $this->tramiteAdicionado->usuario . " tiene 5 tramites adicionados"]);

                $this->modelo_editar->adiciona = null;

                return;

            }

            $this->flags['cantidad'] = true;
            $this->flags['tomo'] = true;
            $this->flags['registro'] = true;
            $this->flags['tipo_servicio'] = true;
            $this->flags['tipo_tramite'] = false;
            $this->flags['distrito'] = true;
            $this->flags['seccion'] = true;
            $this->flags['solicitante'] = true;
            $this->flags['nombre_solicitante'] = true;

        }else{

            $this->flags['nombre_solicitante'] = false;
            $this->flags['solicitante'] = false;
            $this->flags['tipo_tramite'] = true;
            $this->flags['cantidad'] = true;
            $this->flags['tomo'] = false;
            $this->flags['registro'] = false;
            $this->flags['tipo_servicio'] = false;
            $this->flags['distrito'] = false;
            $this->flags['seccion'] = false;

            $this->modelo_editar->solicitante = $this->tramiteAdicionado->solicitante;
            $this->modelo_editar->nombre_solicitante = $this->tramiteAdicionado->nombre_solicitante;
            $this->modelo_editar->tomo = $this->tramiteAdicionado->tomo;
            $this->modelo_editar->registro = $this->tramiteAdicionado->registro;
            $this->modelo_editar->distrito = $this->tramiteAdicionado->distrito;
            $this->modelo_editar->seccion = $this->tramiteAdicionado->seccion;
            $this->modelo_editar->tipo_servicio = $this->tramiteAdicionado->tipo_servicio;
            $this->modelo_editar->numero_oficio = $this->tramiteAdicionado->numero_oficio;

            if(in_array($this->tramiteAdicionado->servicio->clave_ingreso, ['DL13', 'DL14'])){

                $this->modelo_editar->movimiento_registral = $this->tramiteAdicionado->movimiento_registral;

            }

            if($this->modelo_editar->solicitante == 'Oficialia de partes') $this->flags['numero_oficio'] = true;

        }

        $this->modelo_editar->adiciona = $this->tramiteAdicionado->id;

        $this->updatedModeloEditarTipoServicio();
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

        $this->resetearTodo($borrado = true);

        $this->años = Constantes::AÑOS;

        $this->año = now()->format('Y');

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
        return view('livewire.entrada.certificaciones');
    }
}
