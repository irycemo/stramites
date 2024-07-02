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
use Illuminate\Support\Facades\Http;
use App\Exceptions\TramiteServiceException;
use App\Exceptions\SistemaRppServiceException;
use App\Http\Services\Tramites\TramiteService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Certificaciones extends Component
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

    public $mantener = false;

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
        'dependencias' => false,
        'notarias' => false,
        'tipo_servicio' => true,
        'observaciones' => false,
        'tipo_tramite' => false,
    ];

    protected function rules(){
        return [
            'servicio' => 'required',
            'modelo_editar.id_servicio' => 'required',
            'modelo_editar.solicitante' => 'required',
            'modelo_editar.nombre_solicitante' => 'required',
            'modelo_editar.numero_oficio' => Rule::requiredIf($this->modelo_editar->solicitante == 'Oficialia de partes'),
            'modelo_editar.tomo' => Rule::requiredIf($this->servicio['clave_ingreso'] == 'DL14' || $this->servicio['clave_ingreso'] == 'DL13'),
            'modelo_editar.tomo_bis' => 'nullable',
            'modelo_editar.registro' => Rule::requiredIf($this->servicio['clave_ingreso'] == 'DL14' || $this->servicio['clave_ingreso'] == 'DL13'),
            'modelo_editar.registro_bis' => 'nullable',
            'modelo_editar.distrito' => Rule::requiredIf(in_array($this->servicio['clave_ingreso'], ['DL14', 'DL13', 'DC93'])),
            'modelo_editar.seccion' => Rule::requiredIf(in_array($this->servicio['clave_ingreso'], ['DL14', 'DL13', 'DC93'])),
            'modelo_editar.monto' => 'nullable',
            'modelo_editar.tipo_servicio' => 'required',
            'modelo_editar.tipo_tramite' => 'required',
            'modelo_editar.cantidad' => 'required|numeric|min:1',
            'modelo_editar.adiciona' => 'required_if:adicionaTramite,true',
            'modelo_editar.observaciones' => 'nullable',
            'modelo_editar.folio_real' => 'nullable',
            'modelo_editar.numero_propiedad' => Rule::requiredIf($this->modelo_editar->folio_real == null && !in_array($this->servicio['clave_ingreso'], ['DL14', 'DL13', 'DC93'])),
            'modelo_editar.movimiento_registral' => Rule::requiredIf(
                                                    $this->servicio['clave_ingreso'] == 'DL14' && $this->tramiteAdicionado && in_array($this->tramiteAdicionado->servicio->clave_ingreso, ['DL13', 'DL14']) ||
                                                    $this->servicio['clave_ingreso'] == 'DL13' && $this->tramiteAdicionado && in_array($this->tramiteAdicionado->servicio->clave_ingreso, ['DL13', 'DL14'])
                                                ),
         ];
    }

    protected $messages = [
        'modelo_editar.adiciona.required_if' => 'El campo trámite es obligatorio cuando el campo adiciona a otro tramite está seleccionado.',
        'modelo_editar.movimiento_registral.required_if' => 'No se ha vinculado el trámite original de copias.',
        'modelo_editar.nombre_solicitante' => 'nombre del solicitante',
        'modelo_editar.numero_oficio' => 'número de oficio',
        'modelo_editar.nombre_solicitante' => 'nombre del solicitante',
    ];

    protected $validationAttributes  = [
        'modelo_editar.tomo_bis' => 'tomo bis',
        'modelo_editar.registro_bis' => 'registro bis',
        'modelo_editar.tipo_servicio' => 'tipo de servicio',
        'modelo_editar.numero_control' => 'número de control',
        'modelo_editar.adiciona' => 'trámite',
        'modelo_editar.seccion' => 'sección',
        'modelo_editar.numero_oficio' => 'número de oficio',
    ];

    protected $listeners = [
        'cambioServicio' => 'cambiarFlags',
        'cargarTramite' => 'cargarTramite',
    ];

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

        }

        $this->modelo_editar->id_servicio = $this->servicio['id'];

    }

    public function cargarTramite(Tramite $tramite){

        $this->tramite = $tramite;

    }

    public function cambiarFlags($servicio){

        $this->servicio = $servicio;

        $this->reset('tramite');

        $this->resetearTodo($borrado = true);

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

            if(now() > now()->startOfDay()->addHour(14) && !auth()->user()->hasRole('Administrador')){

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

            if(now() > now()->startOfDay()->addHour(12) && !auth()->user()->hasRole('Administrador')){

                $this->dispatch('mostrarMensaje', ['error', "No se pueden hacer trámites extra urgentes despues de las 11:00 hrs."]);

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

                /* Consultas */
                }elseif($this->servicio['clave_ingreso'] == 'DL07'){

                    $this->consultarFolioReal();

                    $tramite = (new TramiteService($this->modelo_editar))->crear();

                }else{

                    $tramite = (new TramiteService($this->modelo_editar))->crear();

                }

                $this->dispatch('imprimir_recibo', $tramite->id);

                if(!$this->mantener){

                    $this->dispatch('reset');

                    $this->resetearTodo($borrado = true);

                }else{

                    $this->modelo_editar = $tramite->replicate();

                    $this->modelo_editar->observaciones = null;

                    $this->updatedModeloEditarTipoServicio();

                }

                $this->dispatch('mostrarMensaje', ['success', "El trámite se creó con éxito."]);

            });

        } catch (TramiteServiceException $th) {

            $this->dispatch('mostrarMensaje', ['error', $th->getMessage()]);

        } catch (Exception $th) {

            $this->dispatch('mostrarMensaje', ['error', $th->getMessage()]);

        } catch (\Throwable $th) {

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

    public function actualizar(){

        $this->validate();

        try{

            (new TramiteService($this->modelo_editar))->actualizar();

            $this->resetearTodo($borrado = true);

            $this->dispatch('mostrarMensaje', ['success', "El trámite se actualizó con éxito."]);

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

            Log::error("Error al validar el trámite: " . $this->modelo_editar->año . '-' . $this->modelo_editar->numero_control . '-' . $this->modelo_editar->usuario . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

            $this->dispatch('mostrarMensaje', ['error', 'Hubo un error.']);
            $this->resetearTodo();
        }

    }

    public function reimprimir(){

        $this->dispatch('imprimir_recibo', ['tramite' => $this->tramite->id]);

    }

    public function consultarFolioReal(){

        $response = Http::withToken(env('SISTEMA_RPP_SERVICE_TOKEN'))
                        ->accept('application/json')
                        ->asForm()
                        ->post(env('SISTEMA_RPP_SERVICE_CONSULTAR_FOLIO_REAL'),[
                            'folio_real' => $this->modelo_editar->folio_real,
                            'tomo' => $this->modelo_editar->tomo,
                            'registro' => $this->modelo_editar->registro,
                            'numero_propiedad' => $this->modelo_editar->numero_propiedad,
                            'distrito' => $this->modelo_editar->distrito,
                            'seccion' => $this->modelo_editar->seccion,
                        ]);



        $data = json_decode($response, true);

        if($response->status() == 200){

            $this->modelo_editar->folio_real = $data['data']['folio'];
            $this->modelo_editar->tomo = $data['data']['tomo'];
            $this->modelo_editar->registro = $data['data']['registro'];
            $this->modelo_editar->numero_propiedad = $data['data']['numero_propiedad'];
            $this->modelo_editar->distrito = $data['data']['distrito'];
            $this->modelo_editar->seccion = $data['data']['seccion'];

        }if($response->status() == 401){

            throw new Exception("El folio real con el antecedente ingresado no esta activo.");

        }

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

            if(in_array($this->tramiteAdicionado->servicio->clave_ingreso, ['DL13', 'DL14'])){

                $this->modelo_editar->movimiento_registral = $this->tramiteAdicionado->movimiento_registral;

            }

        }

        $this->modelo_editar->adiciona = $this->tramiteAdicionado->id;

        $this->updatedModeloEditarTipoServicio();
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

    }

    public function render()
    {
        return view('livewire.entrada.certificaciones');
    }
}
