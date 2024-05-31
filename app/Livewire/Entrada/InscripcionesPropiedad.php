<?php

namespace App\Livewire\Entrada;

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
use Exception;

class InscripcionesPropiedad extends Component
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

    public $valor_propiedad = ['D115','D116', 'D113', 'D114'];
    public $numero_inmuebles = ['D123', 'D120', 'D121', 'D122','D119', 'D124', 'D125', 'D126'];

    public $mantener = false;

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
            'modelo_editar.fecha_emision' => 'required',
            'modelo_editar.numero_documento' => 'required',
            'modelo_editar.numero_propiedad' => Rule::requiredIf($this->modelo_editar->folio_real == null),
            'modelo_editar.nombre_autoridad' => 'required',
            'modelo_editar.autoridad_cargo' => 'required',
            'modelo_editar.tipo_documento' => 'required',
            'modelo_editar.valor_propiedad' => Rule::requiredIf(in_array($this->servicio['clave_ingreso'], $this->valor_propiedad)),
            'modelo_editar.numero_inmuebles' => Rule::requiredIf(in_array($this->servicio['clave_ingreso'], $this->numero_inmuebles)),
            'modelo_editar.numero_oficio' => Rule::requiredIf($this->modelo_editar->solicitante == 'Oficialia de partes'),
            'modelo_editar.folio_real' => 'nullable',
            'modelo_editar.numero_inmuebles' => 'nullable',
            'modelo_editar.foraneo' => 'required'
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

        if(in_array($this->servicio['clave_ingreso'], $this->valor_propiedad)){

            $this->flags['valor_propiedad'] = true;

        }

        if(in_array($this->servicio['clave_ingreso'], $this->numero_inmuebles)){

            $this->flags['numero_inmuebles'] = true;

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

        $this->updatedModeloEditarTipoTramite();

        if($this->modelo_editar->foraneo)
            $this->foraneo();

    }

    public function updatedModeloEditarNumeroPaginas(){

        $this->updatedModeloEditarTipoServicio();

    }

    public function updatedModeloEditarCantidad(){

        $this->updatedModeloEditarTipoServicio();

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
        $this->modelo_editar->nombre_solicitante = $notaria->numero . ' ' .$notaria->notario;

    }

    public function crear(){

        $this->validate();

        try {

            $this->consultarFolioReal();

            DB::transaction(function (){

                $tramite = (new TramiteService($this->modelo_editar))->crear();

                $this->dispatch('imprimir_recibo', $tramite->id);

                if(!$this->mantener){

                    $this->dispatch('reset');

                    $this->resetearTodo($borrado = true);

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

    public function consultarFolioReal(){

        try {

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

            }if($response->status() == 400){

                throw new Exception("No se encontró el antecedente, verifique.");

            }

        } catch (\Throwable $th) {

            Log::error("Errorr al consultar folio real al crear trámite " . $th);

            throw new SistemaRppServiceException("Error al comunicar con Sistema RPP.");

        }

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

    }

    public function render()
    {
        return view('livewire.entrada.inscripciones-propiedad');
    }
}
