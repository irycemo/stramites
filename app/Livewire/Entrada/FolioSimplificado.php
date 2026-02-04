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
use App\Exceptions\GeneralException;
use App\Traits\Ventanilla\ComunTrait;
use App\Http\Services\Tramites\TramiteService;

class FolioSimplificado extends Component
{

    use ComunTrait;

    public $flags = [
        'solicitante' => true,
        'nombre_solicitante' => false,
        'documento' => true,
        'dependencias' => false,
        'notarias' => false,
        'tipo_servicio' => true,
        'observaciones' => true,
        'tipo_tramite' => false,
        'valor_propiedad' => false,
        'numero_propiedad' => false,
        'numero_oficio' => false,
        'distrito' => true,
        'tramite_foraneo' => false,
        'tomo' => true,
        'registro' => true,
        'numero_propiedad' => true,
        'valor_propiedad' => true,
    ];

    protected function rules(){
        return [
            'servicio' => 'required',
            'modelo_editar.id_servicio' => 'required',
            'modelo_editar.solicitante' => 'required',
            'modelo_editar.nombre_solicitante' => 'required',
            'modelo_editar.tomo' => ['required', 'min:1'],
            'modelo_editar.registro' => ['required', 'min:1'],
            'modelo_editar.distrito' => ['required', 'min:1'],
            'modelo_editar.seccion' => ['required', 'min:1'],
            'modelo_editar.monto' => 'nullable',
            'modelo_editar.cantidad' => 'required|numeric|min:1',
            'modelo_editar.tipo_servicio' => 'required',
            'modelo_editar.tipo_tramite' => 'required',
            'modelo_editar.observaciones' => 'nullable',
            'modelo_editar.procedencia' => 'nullable',
            'modelo_editar.fecha_emision' => ['nullable', 'date_format:Y-m-d'],
            'modelo_editar.numero_documento' => 'nullable',
            'modelo_editar.numero_propiedad' => ['required', 'min:1'],
            'modelo_editar.nombre_autoridad' => ['required'],
            'modelo_editar.autoridad_cargo' => ['required'],
            'modelo_editar.tipo_documento' => ['required'],
            'modelo_editar.valor_propiedad' => ['required'],
            'modelo_editar.numero_oficio' => Rule::requiredIf(in_array($this->modelo_editar->solicitante, ['Oficialia de partes','SAT'])),
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

        if($borrado) $this->crearModeloVacio();

        $this->modelo_editar->id_servicio = $this->servicio['id'];

    }

    public function crear(){

        $this->validate();

        try {

            if($this->flags['tramite_foraneo']) $this->buscarforaneo();

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

        }catch (GeneralException $th) {

            $this->dispatch('mostrarMensaje', ['warning', $th->getMessage()]);

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
        return view('livewire.entrada.folio-simplificado');
    }
}
