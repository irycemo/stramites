<?php

namespace App\Traits\Ventanilla;

use App\Exceptions\GeneralException;
use App\Models\Tramite;
use App\Models\Transicion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Services\SistemaRPP\SistemaRppService;
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
    public $año_foraneo;
    public $folio_foraneo;
    public $usuario_foraneo;
    public $tramite_foraneo;

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
    public $cargos_autoridad;

    public $mantener = false;
    public $tramiteMantener;

    public $labelNumeroDocumento = 'Número de documento';

    public $antecedentes = [];

    public $matriz;

    protected $messages = [
        'modelo_editar.adiciona.required_if' => 'El campo trámite es obligatorio cuando el campo adiciona a otro tramite está seleccionado.',
        'modelo_editar.nombre_solicitante' => 'El nombre del solicitante es obligatorio',
        'modelo_editar.numero_oficio' => 'El número de oficio es obligatorio',
        'modelo_editar.nombre_solicitante' => 'El nombre del solicitante es obligatorio',
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
        'modelo_editar.asiento_registral' => 'movimiento registral',
        'modelo_editar.folio_real' => 'folio real',
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

        if(in_array($this->modelo_editar->solicitante , ['Usuario', 'Vivienda Bienestar'])){

            $this->flags['nombre_solicitante'] = true;

        }elseif($this->modelo_editar->solicitante == 'Notaría'){

            $this->flags['notarias'] = true;

        }elseif($this->modelo_editar->solicitante == 'Oficialia de partes'){

            if(!auth()->user()->hasRole(['Oficialia de partes', 'Administrador'])){

                $this->dispatch('mostrarMensaje', ['warning', "No tienes permisos para esta opción."]);

                $this->modelo_editar->solicitante = null;

                return;

            }

            $this->flags['dependencias'] = true;
            $this->flags['numero_oficio'] = true;

            $this->modelo_editar->monto = 0;
            $this->modelo_editar->tipo_tramite = 'exento';

        }elseif($this->modelo_editar->solicitante == 'SAT'){

            if(!auth()->user()->hasRole('Administrador')){

                $this->dispatch('mostrarMensaje', ['warning', "No tienes permisos para esta opción."]);

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

            $this->dispatch('mostrarMensaje', ['warning', "Es necesario indique el tipo de servicio."]);

            return;

        }

        if($this->modelo_editar->tipo_tramite == 'exento'){

            /* if(!auth()->user()->can('Trámite exento')){

                $this->dispatch('mostrarMensaje', ['warning', "No tiene permiso para elaborar trámites exentos."]);

                $this->modelo_editar->tipo_tramite = 'normal';

                return;

            } */

            $this->modelo_editar->monto = 0;

        }elseif($this->modelo_editar->tipo_tramite == 'complemento'){

            if($this->tramiteAdicionado){

                $this->modelo_editar->monto = $this->servicio[$this->modelo_editar->tipo_servicio] * $this->tramiteAdicionado->cantidad - $this->servicio[$this->tramiteAdicionado->tipo_servicio] * $this->tramiteAdicionado->cantidad ;

                $this->modelo_editar->monto < 0 ? $this->modelo_editar->monto = 0 : $this->modelo_editar->monto = $this->modelo_editar->monto;

                $this->modelo_editar->cantidad = $this->tramiteAdicionado->cantidad;

                if(in_array($this->tramiteAdicionado->servicio->clave_ingreso, ['DL13', 'DL14'])){

                    $this->flags['cantidad'] = false;

                }

                $this->flags['tipo_servicio'] = true;

            }else{

                $this->dispatch('mostrarMensaje', ['warning', "Para el complemento es necesario seleccione el tramite al que adiciona."]);

                $this->modelo_editar->tipo_tramite = 'normal';

                $this->updatedModeloEditarTipoTramite();

            }

        }elseif($this->modelo_editar->tipo_tramite == 'normal'){

            $this->modelo_editar->monto = (int)$this->servicio[$this->modelo_editar->tipo_servicio] * (int)$this->modelo_editar->cantidad;

            /* $this->flags['cantidad'] = true; */

        }

        if($this->modelo_editar->tipo_tramite == 'normal' && $this->tramiteAdicionado && in_array($this->tramiteAdicionado->servicio->clave_ingreso, ['DL13', 'DL14'])){

            $this->flags['tipo_servicio'] = false;
            $this->flags['cantidad'] = true;

        }

    }

    public function updatedModeloEditarTipoServicio(){

        if($this->modelo_editar->id_servicio == ""){

            $this->dispatch('mostrarMensaje', ['warning', "Debe seleccionar un servicio."]);

            $this->modelo_editar->tipo_servicio = null;

            $this->modelo_editar->solicitante = null;

            return;
        }

        if($this->modelo_editar->tipo_servicio == 'ordinario'){

            if($this->servicio['ordinario'] == 0){

                $this->dispatch('mostrarMensaje', ['warning', "No hay servicio ordinario para el servicio seleccionado."]);

                $this->modelo_editar->tipo_servicio = null;

                return;

            }

            $this->modelo_editar->monto = (int)$this->servicio['ordinario'] * (int)$this->modelo_editar->cantidad;

        }elseif($this->modelo_editar->tipo_servicio == 'urgente'){

            if(now() > now()->startOfDay()->addHour(17) && !auth()->user()->hasRole('Administrador')){

                $this->dispatch('mostrarMensaje', ['warning', "No se pueden hacer trámites urgentes despues de las 13:00 hrs."]);

                $this->modelo_editar->tipo_servicio = null;
            }

            if($this->servicio['urgente'] == 0){

                $this->dispatch('mostrarMensaje', ['warning', "No hay servicio urgente para el servicio seleccionado."]);

                $this->modelo_editar->tipo_servicio = null;

                return;

            }

            $this->modelo_editar->monto = (int)$this->servicio['urgente'] * (int)$this->modelo_editar->cantidad;

            if($this->modelo_editar->tipo_tramite == 'complemento' && $this->tramiteAdicionado->tipo_servicio == 'urgente'){

                $this->modelo_editar->tipo_servicio = null;
            }

        }
        elseif($this->modelo_editar->tipo_servicio == 'extra_urgente'){

            if(now() > now()->startOfDay()->addHour(17) && !auth()->user()->hasRole('Administrador')){

                $this->dispatch('mostrarMensaje', ['warning', "No se pueden hacer trámites extra urgentes despues de las 12:00 hrs."]);

                $this->modelo_editar->tipo_servicio = null;
            }

            if($this->servicio['extra_urgente']  == 0){

                $this->dispatch('mostrarMensaje', ['warning', "No hay servicio extra urgente para el servicio seleccionado."]);

                $this->modelo_editar->tipo_servicio = null;

                return;

            }

            $this->modelo_editar->monto = (int)$this->servicio['extra_urgente'] * (int)$this->modelo_editar->cantidad;

        }

        if($this->modelo_editar->solicitante == 'Oficialia de partes'){

            $this->modelo_editar->monto = 0;

        }

        $this->updatedModeloEditarTipoTramite();

    }

    public function updatedModeloEditarTomo(){

        $this->reset('antecedentes');

    }

    public function updatedModeloEditarDistrito(){

        $this->reset('antecedentes');

    }

    public function updatedModeloEditarRegistro(){

        $this->reset('antecedentes');

    }

    public function updatedModeloEditarTipoDocumento(){

        if($this->modelo_editar->tipo_documento == ''){

            $this->reset('labelNumeroDocumento');

        }else{

            $this->labelNumeroDocumento = 'Número de ' . mb_strtolower($this->modelo_editar->tipo_documento);

        }

    }

    public function updatedMantener(){

        if(!$this->mantener){

            $this->dispatch('resetTramiteMantener');

            $this->resetearTodo($borrado = true);

        }

    }

    public function updatedModeloEditarAutoridadCargo(){

        if($this->modelo_editar->autoridad_cargo == 'FORANEO'){

            $this->flags['tramite_foraneo'] = true;

        }else{

            $this->flags['tramite_foraneo'] = false;

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

    }

    public function updatedModeloEditarFolioRealPersonaMoral(){

        $this->updatedModeloEditarFolioReal();

    }

    public function buscarforaneo(){

        $this->tramite_foraneo = Tramite::where('año',$this->año_foraneo)
                                    ->where('numero_control' ,$this->folio_foraneo)
                                    ->where('usuario', $this->usuario_foraneo)
                                    ->first();

        if(!$this->tramite_foraneo)
            throw new GeneralException('El trámite foraneo no existe.');

        if($this->tramite_foraneo->servicio->clave_ingreso != 'DL28')
            throw new GeneralException('El trámite foraneo no valido.');

        if($this->tramite_foraneo->adicionadoPor->count() >= 5)
            throw new GeneralException("El trámite de notario foraneo tiene 5 tramites adicionados.");

        $this->modelo_editar->adiciona = $this->tramite_foraneo->id;

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

            if($this->modelo_editar->folio_real || ($this->modelo_editar->tomo && $this->modelo_editar->registro && $this->modelo_editar->numero_propiedad)){

                $this->consultarFolioReal();

            }

            if($this->modelo_editar->servicio->categoria->nombre == 'Cancelación - Gravamenes'){

                $this->consultarGravamen();

            }

            DB::transaction(function (){

                (new TramiteService($this->modelo_editar))->actualizar();

                $this->modelo_editar->audits()->latest()->first()->update(['tags' => 'Actualizó trámite']);

            });

            $this->resetearTodo($borrado = true);

            $this->dispatch('mostrarMensaje', ['success', "El trámite se actualizó con éxito."]);

        } catch (GeneralException $ex) {

            $this->dispatch('mostrarMensaje', ['warning', $ex->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al actualizar el trámite: " . $this->modelo_editar->año . '-' . $this->modelo_editar->numero_control . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

            $this->dispatch('mostrarMensaje', ['error', 'Hubo un error.']);

        }

    }

    public function validarPago(){

        try {

            DB::transaction(function () {

                (new TramiteService($this->tramite))->procesarPago();

                $this->dispatch('mostrarMensaje', ['success', "El trámite se validó con éxito."]);

                $this->resetearTodo($borrado = true);

            });

        } catch (GeneralException $ex) {

            $this->dispatch('mostrarMensaje', ['error', $ex->getMessage()]);

        } catch (\Throwable $th) {

            Log::error("Error al validar el trámite: " . $this->modelo_editar->año . '-' . $this->modelo_editar->numero_control . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

            $this->dispatch('mostrarMensaje', ['error', 'Hubo un error.']);
            $this->resetearTodo();
        }

    }

    public function reimprimir(){

        $this->dispatch('imprimir_recibo', ['tramite' => $this->tramite->id]);

    }

    public function consultarGravamen(){

        $data = (new SistemaRppService)->consultarGravamen($this->modelo_editar);

        $this->modelo_editar->asiento_registral = $data['data']['folio'];
        $this->modelo_editar->tomo_gravamen = $data['data']['tomo_gravamen'];
        $this->modelo_editar->registro_gravamen = $data['data']['registro_gravamen'];

    }

    public function consultarFolioReal(){

        $data = (new SistemaRppService)->consultarFolioReal($this->modelo_editar);

        /* Response 204 Folio real no existe */
        if(! isset($data['data']['distrito'])) return;

        if(auth()->user()->ubicacion == 'Regional 4' && $data['data']['distrito'] != 2){

            throw new GeneralException('EL folio no es del distrito 2');

        }

        $this->modelo_editar->folio_real = $data['data']['folio'];
        $this->modelo_editar->tomo = $data['data']['tomo'];
        $this->modelo_editar->registro = $data['data']['registro'];
        $this->modelo_editar->numero_propiedad = $data['data']['numero_propiedad'];
        $this->modelo_editar->distrito = $data['data']['distrito'];
        $this->modelo_editar->seccion = $data['data']['seccion'];
        $this->matriz = $data['data']['matriz'];

        if($this->modelo_editar->tomo && $this->modelo_editar->registro && $this->modelo_editar->numero_propiedad && $this->modelo_editar->distrito && $this->modelo_editar->seccion){

            $transicion = Transicion::where('tomo', $this->modelo_editar->tomo)
                                        ->where('registro', $this->modelo_editar->registro)
                                        ->where('numero_propiedad', $this->modelo_editar->numero_propiedad)
                                        ->where('distrito', $this->modelo_editar->distrito)
                                        ->where('seccion', $this->modelo_editar->seccion)
                                        ->first();

            if($transicion){

                throw new GeneralException("La propiedad se encuentra en transición.");

            }

        }

    }

    public function consultarFolioRealPersonaMoral(){

        $data = (new SistemaRppService)->consultarFolioRealPersonaMoral($this->modelo_editar);

        if(auth()->user()->ubicacion == 'Regional 4' && $data['data']['distrito'] != 2){

            throw new GeneralException('EL folio no es del distrito 2');

        }

        $this->modelo_editar->folio_real_persona_moral = $data['data']['folio'];
        $this->modelo_editar->distrito = $data['data']['distrito'];

    }

    public function consultarFolioMovimiento(){

        $data = (new SistemaRppService)->consultarFolioMovimiento($this->modelo_editar);

        $this->modelo_editar->folio_real = $data['data']['folio'];
        $this->modelo_editar->tomo = $data['data']['tomo'];
        $this->modelo_editar->registro = $data['data']['registro'];
        $this->modelo_editar->numero_propiedad = $data['data']['numero_propiedad'];
        $this->modelo_editar->distrito = $data['data']['distrito'];
        $this->modelo_editar->seccion = $data['data']['seccion'];

    }

    public function consultarAntecedentes(){

        try {

            $this->validate([
                'modelo_editar.distrito' => 'required',
                'modelo_editar.tomo' => 'required',
                'modelo_editar.registro' => 'required',
            ]);

            $this->reset('antecedentes');

            $this->flags['numero_propiedad'] = false;

            $data = (new SistemaRppService)->consultarAntecedentes($this->modelo_editar);

            $this->antecedentes = $data['antecedentes'];

        } catch (GeneralException $ex) {

            $this->dispatch('mostrarMensaje', ['warning', $ex->getMessage()]);

        }  catch (\Throwable $th) {

            Log::error("Error al crear el trámite: " . $this->modelo_editar->año . '-' . $this->modelo_editar->numero_control . " por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

            $this->dispatch('mostrarMensaje', ['error', 'Hubo un error.']);

        }

    }

    public function cambiarFlagNumeroPropiedad(){

        $this->flags['numero_propiedad'] = true;

    }

    public function consultarPrimerAviso(){

        (new SistemaRppService)->consultarPrimerAvisoPreventivo($this->modelo_editar);

    }

    public function consultarSegundoAviso(){

        (new SistemaRppService)->consultarSegundoAvisoPreventivo($this->modelo_editar);

    }

}
