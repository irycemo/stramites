<?php

namespace App\Livewire\Admin;

use App\Models\Notaria;
use App\Models\Tramite;
use Livewire\Component;
use App\Models\Servicio;
use App\Models\Dependencia;
use Livewire\WithPagination;
use App\Models\Configuracion;
use App\Constantes\Constantes;
use App\Exceptions\GeneralException;
use App\Traits\ComponentesTrait;
use App\Models\CategoriaServicio;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Services\Tramites\TramiteService;
use App\Http\Services\SistemaRPP\SistemaRppService;

class Tramites extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public $solicitantes;
    public $secciones;
    public $categorias;
    public $categoria;
    public $categoria_selected;
    public $servicios;
    public $servicio;
    public $servicio_selected;
    public $adicionaTramite;
    public $tramitesAdiciona;
    public $distritos;
    public $dependencias;
    public $notarias;
    public $notaria;
    public $numero_de_control;
    public $tramite;
    public $modalVer = false;
    public $modalAcreditar = false;
    public $años;
    public $referencia_pago;
    public $fecha_pago;
    public $regionales;
    public $filters = [
        'año' => '',
        'folio' => '',
        'usuario' => '',
        'estado' => '',
        'categoria' => '',
        'servicio' => '',
        'regional' => ''
    ];

    public Tramite $modelo_editar;

    public $flags = [
        'adiciona' => false,
        'solicitante' => false,
        'nombre_solicitante' => false,
        'seccion' => false,
        'numero_oficio' => false,
        'tomo' => false,
        'folio_real' => false,
        'registro' => false,
        'numero_propiedad' => false,
        'distrito' => false,
        'numero_inmuebles' => false,
        'numero_escritura' => false,
        'tomo_gravamen' => false,
        'foraneo' => false,
        'registro_gravamen' => false,
        'numero_paginas' => false,
        'valor_propiedad' => false,
        'dependencias' => false,
        'notarias' => false,
        'tipo_servicio' => false,
        'observaciones' => false
    ];

    protected function rules(){
        return [
            'modelo_editar.solicitante' => 'required',
            'modelo_editar.nombre_solicitante' => 'required_if:modelo_editar.solicitante,Ventanilla,Juzgado',
            'modelo_editar.numero_oficio' => 'nullable',
            'modelo_editar.folio_real' => 'nullable',
            'modelo_editar.tomo' => 'nullable|required_with:tomo_bis',
            'modelo_editar.tomo_bis' => 'nullable',
            'modelo_editar.registro' => 'nullable|required_with:registro_bis',
            'modelo_editar.registro_bis' => 'nullable',
            'modelo_editar.tomo_gravamen' => 'nullable',
            'modelo_editar.registro_gravamen' => 'nullable',
            'modelo_editar.distrito' => 'nullable',
            'modelo_editar.seccion' => 'nullable',
            'modelo_editar.limite_de_pago' => 'nullable',
            'modelo_editar.fecha_entrega' => 'nullable',
            'modelo_editar.cantidad' => 'nullable',
            'modelo_editar.numero_inmuebles' => 'nullable',
            'modelo_editar.numero_propiedad' => 'nullable',
            'modelo_editar.numero_escritura' => 'nullable',
            'modelo_editar.numero_notaria' => 'nullable',
            'modelo_editar.nombre_notario' => 'nullable',
            'modelo_editar.valor_propiedad' => 'nullable',
            'modelo_editar.observaciones' => 'nullable',
            'modelo_editar.tipo_documento' => 'nullable',
            'modelo_editar.autoridad_cargo' => 'nullable',
            'modelo_editar.nombre_autoridad' => 'nullable',
            'modelo_editar.numero_documento' => 'nullable',
            'modelo_editar.fecha_emision' => 'nullable',
            'modelo_editar.procedencia' => 'nullable',
         ];
    }

    protected $messages = [
        'modelo_editar.adiciona.required_if' => 'El campo trámite es obligatorio cuando el campo adiciona tramite está seleccionado.',
    ];

    protected $validationAttributes  = [
        'modelo_editar.id_servicio' => 'servicio',
        'modelo_editar.folio_real' => 'folio real',
        'modelo_editar.tomo_bis' => 'tomo bis',
        'modelo_editar.registro_bis' => 'registro bis',
        'modelo_editar.numero_propiedad' => 'número de propiedad',
        'modelo_editar.nombre_solicitante' => 'nombre del solicitante',
        'modelo_editar.fecha_entrega' => 'fecha de entrega',
        'modelo_editar.tipo_servicio' => 'tipo de servicio',
        'modelo_editar.numero_control' => 'número de control',
        'modelo_editar.numero_escritura' => 'número de escritura',
        'modelo_editar.numero_notaria' => 'número de notaria',
        'modelo_editar.limite_de_pago' => 'límite de pago',
        'modelo_editar.adiciona' => 'trámite',
        'modelo_editar.numero_inmuebles' => 'cantidad de inmuebles',
        'modelo_editar.valor_propiedad' => 'valor de la propiedad',
        'modelo_editar.registro_gravamen' => 'registro gravamen',
        'modelo_editar.tomo_gravamen' => 'tomo gravamen',
        'modelo_editar.numero_oficio' => 'número de oficio'
    ];

    public function updatedFilters() {

        $this->resetPage();

        if($this->filters['categoria'] != ''){

            $this->servicios = Servicio::select('id', 'nombre')->where('estado', 'activo')->where('categoria_servicio_id', $this->filters['categoria'])->orderBy('nombre')->get();

        }else{

            $this->servicios = Servicio::select('id', 'nombre')->where('estado', 'activo')->orderBy('nombre')->get();

        }

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

        }else{

            $this->modelo_editar->nombre_solicitante = $this->modelo_editar->solicitante;

        }

    }

    public function updatedModeloEditarFolioReal(){

        if($this->modelo_editar->folio_real == ''){

            $this->modelo_editar->folio_real = null;

        }

        $this->modelo_editar->tomo = null;
        $this->modelo_editar->registro = null;
        $this->modelo_editar->numero_propiedad = null;
        $this->modelo_editar->distrito = null;

    }

    public function crearModeloVacio(){
        $this->modelo_editar = Tramite::make();
    }

    public function abrirModalEditar(Tramite $modelo){

        $this->resetearTodo();

        $this->selected_id = $modelo->id;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modal = true;
        $this->editar = true;

        foreach($this->modelo_editar->getAttributes() as $attribute => $value){

            if($value){

                $this->flags[$attribute] = true;

            }

        };

        if($this->modelo_editar->adiciona)
            $this->adicionaTramite = true;

    }

    public function abrirModalVer(Tramite $modelo){

        $this->resetearTodo();

        $this->selected_id = $modelo->id;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        $this->modalVer = true;

        $this->modelo_editar->load('adicionadoPor');

    }

    public function abrirModalAcreditar(Tramite $modelo){

        $this->resetearTodo();

        $this->selected_id = $modelo->id;

        if($this->modelo_editar->isNot($modelo))
            $this->modelo_editar = $modelo;

        if($this->modelo_editar->adicionaAlTramite?->servicio->clave_ingreso == 'DC93' && !$this->modelo_editar->adicionaAlTramite?->fecha_pago){

            $this->dispatch('mostrarMensaje', ['warning', "El trámite de consulta al que adiciona no esta pagado."]);

            return;

        }elseif($this->modelo_editar->adicionaAlTramite?->servicio->clave_ingreso == 'DL28' && !$this->modelo_editar->adicionaAlTramite?->fecha_pago){

            $this->dispatch('mostrarMensaje', ['warning', "El trámite foraneo al que adiciona no esta pagado."]);

            return;

        }

        $this->validarPago();

        $modelo->refresh();

        if(!$modelo->fecha_pago)
            $this->modalAcreditar = true;

    }

    public function validarPago(){

        try {

            DB::transaction(function () {

                (new TramiteService($this->modelo_editar))->procesarPago();

                $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Validó pago']);

                $this->dispatch('mostrarMensaje', ['success', "El trámite se validó con éxito."]);

                $this->resetearTodo($borrado = true);

            });

        } catch (GeneralException $ex) {

            $this->dispatch('mostrarMensaje', ['warning', $ex->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al validar el trámite: " . $this->modelo_editar->año . '-' . $this->modelo_editar->numero_control . '-' . $this->modelo_editar->usuario . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

            $this->dispatch('mostrarMensaje', ['error', 'Hubo un error.']);
            $this->resetearTodo();
        }

    }

    public function actualizar(){

        $this->validate();

        try{

            if($this->modelo_editar->folio_real || ($this->modelo_editar->tomo && $this->modelo_editar->registro && $this->modelo_editar->numero_propiedad)){

                $this->consultarFolioReal();

            }

            if($this->modelo_editar->movimiento_registral)

                (new TramiteService($this->modelo_editar))->actualizar();

            else{

                $this->modelo_editar->actualizado_por = auth()->id();

                $this->modelo_editar->save();

            }

            $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Actualizó trámite']);

            $this->resetearTodo($borrado = true);

            $this->dispatch('mostrarMensaje', ['success', "El trámite se actualizó con éxito."]);

        } catch (GeneralException $ex) {

            $this->dispatch('mostrarMensaje', ['warning', $ex->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al actualizar el trámite: " . $this->modelo_editar->año . '-' . $this->modelo_editar->numero_control . '-' . $this->modelo_editar->usuario . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

            $this->dispatch('mostrarMensaje', ['error', 'Hubo un error.']);
            $this->resetearTodo($borrado = true);
        }

    }

    public function consultarFolioReal(){

        $data = (new SistemaRppService())->consultarFolioReal($this->modelo_editar);

        if(isset($data['data']['folio'])){

            $this->modelo_editar->folio_real = $data['data']['folio'];
            $this->modelo_editar->tomo = $data['data']['tomo'];
            $this->modelo_editar->registro = $data['data']['registro'];
            $this->modelo_editar->numero_propiedad = $data['data']['numero_propiedad'];
            $this->modelo_editar->distrito = $data['data']['distrito'];
            $this->modelo_editar->seccion = $data['data']['seccion'];

        }

    }

    public function borrar(){

        try{

            $tramite = Tramite::find($this->selected_id);

            if($tramite->estado != 'nuevo'){

                $this->dispatch('mostrarMensaje', ['error', "El trámite no se puede eliminar."]);

                return;

            }

            if($tramite->movimiento_registral){

                $this->dispatch('mostrarMensaje', ['error', "El trámite esta registrado en el Sistema RPP no puede ser eliminado."]);

                return;

            }

            if($tramite->movimiento_registral){

                $this->dispatch('mostrarMensaje', ['error', "El trámite esta registrado en el Sistema RPP no puede ser eliminado."]);

                return;

            }

            if($tramite->fecha_pago){

                $this->dispatch('mostrarMensaje', ['error', "El trámite tiene un pago registrado no puede ser borrado."]);

                return;

            }

           $tramite->delete();

            $this->resetearTodo($borrado = true);

            $this->dispatch('mostrarMensaje', ['success', "El trámite se eliminó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al borrar trámite por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }
    }

    public function reimprimir(){

        $this->dispatch('imprimir_recibo', ['tramite' => $this->modelo_editar->id]);

    }

    public function enviarTramiteRpp(){

        if($this->modelo_editar->movimiento_registral){

            $this->dispatch('mostrarMensaje', ['warning', "El trámite ya se encuentra en Sistema RPP."]);

            return;

        }

        if($this->modelo_editar->adicionaAlTramite && !in_array($this->modelo_editar->adicionaAlTramite->servicio->clave_ingreso, ['DC93', 'DL28'])){

            $this->dispatch('mostrarMensaje', ['warning', "El trámite adiciona al trámite: " . $this->modelo_editar->adicionaAlTramite->año . '-' . $this->modelo_editar->adicionaAlTramite->numero_control . '-' . $this->modelo_editar->adicionaAlTramite->usuario . ' no es posible enviarlo al Sistema RPP.']);

            return;

        }

        try{

            (new SistemaRppService())->insertarSistemaRpp($this->modelo_editar);

            $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Envió trámite a Sistema RPP']);

            $this->resetearTodo($borrado = true);

            $this->dispatch('mostrarMensaje', ['success', "El trámite se envió al Sistema RPP con éxito."]);

        } catch (GeneralException $ex) {

            $this->dispatch('mostrarMensaje', ['warning', $ex->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al enviar trámite al sistema rpp por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);
            $this->resetearTodo();

        }

    }

    /* public function generarNumeroControl(){

        if($this->modelo_editar->id && !$this->modelo_editar->numero_control)
            dispatch(new GenerarFolioTramite($this->modelo_editar->id));

        $this->resetearTodo();

    } */

    public function reactivarTramtie(Tramite $modelo){

        try{

            if($modelo->estado === 'caducado'){

                $modelo->update(['estado' => 'nuevo']);

            }elseif($modelo->estado === 'expirado' && $modelo->fecha_pago){

                $modelo->update(['estado' => 'pagado']);

            }

            $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Reactivó trámite']);

            $this->dispatch('mostrarMensaje', ['success', "El trámite se reactivó con éxito."]);

        } catch (\Throwable $th) {

            Log::error("Error al reactivar trámite  por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function desactivarEntrada()
    {

        try {

            $valor = Configuracion::first();

            $valor ->update(['entrada' => !$valor->entrada]);

            $this->dispatch('mostrarMensaje', ['success', "El área de entrada se " . ($valor->entrada ? 'habilito' : 'deshabilito') . ' con éxito']);

        } catch (\Throwable $th) {

            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function simularPago(){

        $this->modelo_editar->update([
            'estado' => 'pagado',
            'fecha_prelacion' => now()->toDateString(),
            'fecha_pago'  => now()->toDateString(),
            'orden_de_pago'  => '300082157991',
        ]);

    }

    public function acreditarPago(){

        $this->validate(
        [
            'referencia_pago' => 'required|numeric',
            'fecha_pago' => 'required|date|before:tomorrow'
        ],
        [],
        [
            'referencia_pago' => 'referencia de pago',
            'fecha_pago' => 'fecha de pago',
        ]);

        try {

            $this->modelo_editar->update([
                'estado' => 'pagado',
                'documento_de_pago' => $this->referencia_pago,
                'fecha_pago' => $this->fecha_pago,
                'fecha_prelacion' => $this->fecha_pago,
                'actualizado_por' => auth()->id()
            ]);

            $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Acreditó pago manualmente']);

            if($this->modelo_editar->servicio->categoria->nombre === 'Certificaciones')
                (new SistemaRppService())->insertarSistemaRpp($this->modelo_editar);

            $this->dispatch('mostrarMensaje', ['success', "El trámite acreditó con éxito. Guardar la documentación que acredita el pago."]);

            $this->resetearTodo();

        } catch (GeneralException $ex) {

            $this->dispatch('mostrarMensaje', ['warning', $ex->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al acreditar trámite  por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th->getMessage());
            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    #[Computed]
    public function tramites(){

        return Tramite::select('id', 'año', 'numero_control', 'usuario', 'estado', 'adiciona', 'nombre_solicitante', 'fecha_pago', 'id_servicio', 'solicitante', 'folio_real', 'tomo', 'registro', 'distrito', 'monto', 'tipo_servicio', 'creado_por', 'actualizado_por', 'created_at', 'updated_at')
                        ->with('creadoPor:id,name', 'actualizadoPor:id,name', 'adicionaAlTramite:id', 'servicio.categoria:id,nombre')
                        ->when($this->filters['año'] != '', function($q){
                            return $q->where('año', $this->filters['año']);

                        })
                        ->when($this->filters['folio'] != '', function($q){
                            return $q->where('numero_control', $this->filters['folio']);

                        })
                        ->when($this->filters['usuario'] != '', function($q){
                            return $q->where('usuario', $this->filters['usuario']);

                        })
                        ->when($this->filters['estado'] != '', function($q){
                            return $q->where('estado', $this->filters['estado']);

                        })
                        ->when($this->filters['servicio'] != '', function($q){
                            return $q->where('id_servicio', $this->filters['servicio']);

                        })
                        ->when($this->filters['categoria'] != '', function($q){
                            return $q->whereHas('servicio', function ($q){
                                $q->select('id', 'categoria_servicio_id')
                                    ->where('categoria_servicio_id', $this->filters['categoria']);
                            });
                        })
                        ->when($this->filters['regional'] != '', function($q){
                            return $q->whereHas('creadoPor', function ($q){
                                $q->select('id', 'ubicacion')
                                    ->where('ubicacion', $this->filters['regional']);
                            });
                        })
                        ->where(function($q){
                            $q->where('solicitante', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('nombre_solicitante', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('folio_real', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('tomo', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('estado', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('registro', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('distrito', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('numero_control', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('numero_escritura', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('numero_documento', 'LIKE', '%' . $this->search . '%')
                                ->orWhere('numero_oficio', 'LIKE', '%' . $this->search . '%')
                                ->orWhere(function($q){
                                    return $q->whereHas('creadoPor', function($q){
                                        return $q->select('id', 'name')
                                                ->where('name', 'LIKE', '%' . $this->search . '%');
                                    });
                                })
                                ->orWhere(function($q){
                                    return $q->whereHas('servicio', function($q){
                                        return $q->select('id', 'nombre')
                                                ->where('nombre', 'LIKE', '%' . $this->search . '%');
                                    });
                                });
                        })
                        ->orderBy($this->sort, $this->direction)
                        ->paginate($this->pagination);

    }

    public function mount(){

        array_push($this->fields, 'adicionaTramite', 'flags', 'modalVer', 'modalAcreditar', 'referencia_pago', 'fecha_pago');

        $this->crearModeloVacio();

        $this->secciones = Constantes::SECCIONES;

        $this->distritos = Constantes::DISTRITOS;

        $this->años = Constantes::AÑOS;

        $this->dependencias = Dependencia::orderBy('nombre')->get();

        $this->notarias = Notaria::orderBy('numero')->get();

        $this->solicitantes = Constantes::SOLICITANTES;

        $this->secciones = Constantes::SECCIONES;

        $this->servicios = Servicio::select('id', 'nombre')->where('estado', 'activo')->orderBy('nombre')->get();

        $this->categorias = CategoriaServicio::select('id', 'nombre')->orderBy('nombre')->get();

        $this->regionales = Constantes::UBICACIONES;

    }

    public function render()
    {
        return view('livewire.admin.tramites')->extends('layouts.admin');
    }

}
