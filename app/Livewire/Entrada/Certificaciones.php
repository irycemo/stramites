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
use App\Exceptions\GeneralException;
use App\Traits\Ventanilla\ComunTrait;
use App\Http\Services\Tramites\TramiteService;
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
        'folio_real' => false,
        'movimiento_registral' => false
    ];

    protected function rules(){
        return [
            'servicio' => 'required',
            'modelo_editar.id_servicio' => 'required',
            'modelo_editar.solicitante' => 'required',
            'modelo_editar.nombre_solicitante' => 'required',
            'modelo_editar.numero_oficio' => Rule::requiredIf(in_array($this->modelo_editar->solicitante, ['Oficialia de partes','SAT'])),
            'modelo_editar.tomo' => [Rule::requiredIf(in_array($this->servicio['clave_ingreso'], ['DL14', 'DL13', 'DL12', 'D110']) && !$this->modelo_editar->folio_real), 'nullable', 'numeric'],
            'modelo_editar.tomo_bis' => 'nullable',
            'modelo_editar.registro' => [Rule::requiredIf(in_array($this->servicio['clave_ingreso'], ['DL14', 'DL13', 'DL12', 'D110']) && !$this->modelo_editar->folio_real), 'nullable', 'numeric'],
            'modelo_editar.registro_bis' => 'nullable',
            'modelo_editar.distrito' => Rule::requiredIf($this->modelo_editar->folio_real == null && !in_array($this->servicio['clave_ingreso'], ['DM67', 'D111']) && $this->servicio['nombre'] != 'Certificado negativo de vivienda bienestar'),
            'modelo_editar.seccion' => Rule::requiredIf($this->modelo_editar->folio_real == null && !in_array($this->servicio['clave_ingreso'], ['DL12', 'DM67', 'D110', 'D111'])),
            'modelo_editar.monto' => 'nullable',
            'modelo_editar.tipo_servicio' => 'required',
            'modelo_editar.tipo_tramite' => 'required',
            'modelo_editar.cantidad' => 'required|numeric|min:1',
            'modelo_editar.adiciona' => 'required_if:adicionaTramite,true',
            'modelo_editar.observaciones' => 'nullable',
            'modelo_editar.folio_real' => [Rule::requiredIf($this->modelo_editar->asiento_registral != null), 'nullable'],
            'modelo_editar.asiento_registral' => 'nullable',
            'modelo_editar.numero_propiedad' => ['nullable', 'numeric', 'min:1', Rule::requiredIf($this->modelo_editar->folio_real == null && !in_array($this->servicio['clave_ingreso'], ['DL14', 'DL13', 'DC93', 'DL10' , 'DL11', 'DL12', 'DM67', 'D110', 'D111']))],
            'modelo_editar.movimiento_registral' => Rule::requiredIf(
                                                    $this->servicio['clave_ingreso'] == 'DL14' && $this->tramiteAdicionado && in_array($this->tramiteAdicionado->servicio->clave_ingreso, ['DL13', 'DL14']) ||
                                                    $this->servicio['clave_ingreso'] == 'DL13' && $this->tramiteAdicionado && in_array($this->tramiteAdicionado->servicio->clave_ingreso, ['DL13', 'DL14'])
                                                ),
            'año' => [Rule::requiredIf($this->adicionaTramite && !$this->editar), 'nullable', 'numeric'],
            'folio' => [Rule::requiredIf($this->adicionaTramite && !$this->editar), 'nullable', 'numeric'],
            'usuario' => [Rule::requiredIf($this->adicionaTramite && !$this->editar), 'nullable', 'numeric'],
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
            $this->flags['solicitante'] = false;
            $this->flags['tomo'] = false;
            $this->flags['registro'] = false;
            $this->flags['seccion'] = false;
            $this->flags['distrito'] = false;
            $this->flags['movimiento_registral'] = false;
            $this->flags['folio_real'] = false;
            $this->flags['adiciona'] = true;
            $this->flags['observaciones'] = true;
            $this->adicionaTramite = true;

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

        }elseif(in_array($this->servicio['clave_ingreso'], ['DL12', 'D110'])){

            $this->flags['distrito'] = true;
            $this->flags['tomo'] = true;
            $this->flags['registro'] = true;
            $this->flags['seccion'] = false;
            $this->flags['tipo_servicio'] = false;

        }elseif(in_array($this->servicio['clave_ingreso'], ['DM67', 'D111'])){

            $this->flags['distrito'] = false;
            $this->flags['tomo'] = false;
            $this->flags['registro'] = false;
            $this->flags['seccion'] = false;
            $this->flags['tipo_servicio'] = false;
            $this->flags['adiciona'] = true;
            $this->flags['cantidad'] = true;
            $this->adicionaTramite = true;

        }


        $this->modelo_editar->id_servicio = $this->servicio['id'];

        if($this->modelo_editar->solicitante == 'Oficialia de partes') $this->flags['numero_oficio'] = true;

        if(auth()->user()->hasRole('Oficialia de partes')){

            $this->flags['cantidad'] = true;
            $this->flags['solicitante'] = true;
            $this->flags['tomo'] = true;
            $this->flags['registro'] = true;
            $this->flags['seccion'] = true;
            $this->flags['folio_real'] = true;
            $this->flags['movimiento_registral'] = true;
            $this->flags['distrito'] = true;
            $this->adicionaTramite = false;
            $this->flags['adiciona'] = false;
            $this->flags['observaciones'] = true;

        }

        if($this->servicio['nombre'] == 'Certificado negativo de vivienda bienestar'){

            if(auth()->user()->ubicacion == 'Regional 4'){

                $this->modelo_editar->distrito = 2;

            }elseif(auth()->user()->ubicacion == 'Regional 1'){

                $this->modelo_editar->distrito = 3;

            }elseif(auth()->user()->ubicacion == 'Regional 2'){

                $this->modelo_editar->distrito = 12;

            }elseif(auth()->user()->ubicacion == 'Regional 3'){

                $this->modelo_editar->distrito = 4;

            }elseif(auth()->user()->ubicacion == 'Regional 5'){

                $this->modelo_editar->distrito = 13;

            }elseif(auth()->user()->ubicacion == 'Regional 6'){

                $this->modelo_editar->distrito = 15;

            }elseif(auth()->user()->ubicacion == 'Regional 7'){

                $this->modelo_editar->distrito = 5;

            }else{

                $this->modelo_editar->distrito = 1;

            }


        }

    }

    public function updatedAdicionaTramite(){

        return;

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
            $this->flags['movimiento_registral'] = true;
            $this->flags['folio_real'] = true;

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

                /* Certificado de gravamen */
                if($this->servicio['clave_ingreso'] == 'DL07'){

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

                /* Consultas - Copias */
                }else{

                    if($this->modelo_editar->folio_real){

                        $this->consultarFolioMovimiento();

                    }

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

        } catch (GeneralException $ex) {

            $this->dispatch('mostrarMensaje', ['warning', $ex->getMessage()]);

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
        $this->flags['movimiento_registral'] = true;
        $this->flags['folio_real'] = true;
        $this->flags['tomo'] = true;
        $this->flags['registro'] = true;
        $this->flags['distrito'] = true;
        $this->flags['observaciones'] = true;

        if($this->modelo_editar->solicitante == 'Oficialia de partes') $this->flags['numero_oficio'] = true;

        $this->editar = true;

    }

    public function buscarTramiteAdiciona(){

        $this->tramiteAdicionado = Tramite::where('año', $this->año)
                                        ->where('numero_control', $this->folio)
                                        ->where('usuario', $this->usuario)
                                        ->whereHas('servicio', function($q){
                                            $q->whereIn('clave_ingreso', ['DC93','DL13', 'DL14', 'Dl12', 'D110']);
                                        })
                                        ->first();

        if(!$this->tramiteAdicionado){

            $this->dispatch('mostrarMensaje', ['error', "No se encontro el trámite."]);

            $this->modelo_editar->adiciona = null;

            $this->updatedAdicionaTramite();

            return;

        }

        if(!$this->tramiteAdicionado->fecha_pago){

            $this->dispatch('mostrarMensaje', ['warning', "El trámite a adicionar no esta pagado."]);

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
            $this->flags['movimiento_registral'] = true;
            $this->flags['folio_real'] = true;

            $this->modelo_editar->tipo_servicio = $this->tramiteAdicionado->tipo_servicio;

        }elseif(in_array($this->tramiteAdicionado->servicio->clave_ingreso, ['DL12', 'D110'])){

            if($this->tramiteAdicionado->adicionadoPor->count()){

                $this->dispatch('mostrarMensaje', ['error', "El trámite " .  $this->tramiteAdicionado->año . '-' . $this->tramiteAdicionado->numero_control . '-' . $this->tramiteAdicionado->usuario . " ya esta asociado a otro trámite."]);

                $this->modelo_editar->adiciona = null;

                return;

            }

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

        $this->modelo_editar->tipo_tramite = 'complemento';

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

        $this->años = Constantes::AÑOS;

        $this->año_foraneo = now()->format('Y');

    }

    public function render()
    {
        return view('livewire.entrada.certificaciones');
    }
}
