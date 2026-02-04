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

class PersonaMoral extends Component
{

    use ComunTrait;

    public $flags = [
        'solicitante' => true,
        'nombre_solicitante' => false,
        'documento' => true,
        'dependencias' => false,
        'notarias' => false,
        'tipo_servicio' => false,
        'observaciones' => true,
        'tipo_tramite' => false,
        'numero_oficio' => false,
        'tramite_foraneo' => false,
        'distrito' => true,
        'folio_real' => false,
        'tomo' => false,
        'registro' => false
    ];

    protected function rules(){
        return [
            'servicio' => 'required',
            'modelo_editar.id_servicio' => 'required',
            'modelo_editar.solicitante' => 'required',
            'modelo_editar.nombre_solicitante' => 'required',
            'modelo_editar.numero_oficio' => Rule::requiredIf(in_array($this->modelo_editar->solicitante, ['Oficialia de partes','SAT'])),
            'modelo_editar.tipo_servicio' => 'required',
            'modelo_editar.distrito' => Rule::requiredIf($this->servicio['nombre'] == 'Acta constitutiva'),
            'modelo_editar.tipo_tramite' => 'required',
            'modelo_editar.cantidad' => 'required|numeric|min:1',
            'modelo_editar.observaciones' => 'nullable',
            'modelo_editar.folio_real_persona_moral' => 'nullable',
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
            'modelo_editar.seccion' => 'required',
            'año_foraneo' => Rule::requiredIf($this->flags['tramite_foraneo']),
            'folio_foraneo' => Rule::requiredIf($this->flags['tramite_foraneo']),
            'usuario_foraneo' => Rule::requiredIf($this->flags['tramite_foraneo']),
            'modelo_editar.tomo' => ['nullable', Rule::requiredIf($this->modelo_editar->folio_real_persona_moral == null && $this->servicio['nombre'] != 'Acta constitutiva')],
            'modelo_editar.registro' => ['nullable', Rule::requiredIf($this->modelo_editar->folio_real_persona_moral == null && $this->servicio['nombre'] != 'Acta constitutiva')],
            'modelo_editar.distrito' => ['nullable', Rule::requiredIf($this->modelo_editar->folio_real_persona_moral == null && $this->servicio['nombre'] != 'Acta constitutiva')],
         ];
    }

    public function crearModeloVacio(){

        $this->modelo_editar = Tramite::make([
            'cantidad' => 1,
            'tipo_tramite' => 'normal',
            'tipo_servicio' => 'ordinario',
            'seccion' => 'Folio real de persona moral'
        ]);

    }

    public function resetearTodo($borrado = false){

        $this->resetErrorBag();

        $this->resetValidation();

        $this->reset([
            'flags',
            'editar',
        ]);

        if($this->servicio['nombre'] == 'Acta de asamblea'){

            $this->flags['folio_real'] = true;
            $this->flags['tomo'] = true;
            $this->flags['registro'] = true;
            $this->flags['tipo_servicio'] = true;

        }

        if($borrado) $this->crearModeloVacio();

        $this->modelo_editar->id_servicio = $this->servicio['id'];

    }

    public function crear(){

        $this->validate();

        try {

            if($this->flags['tramite_foraneo']) $this->buscarforaneo();

            if($this->servicio['nombre'] == 'Acta de asamblea') $this->consultarFolioRealPersonaMoral();

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

        } catch (GeneralException $ex) {

            $this->dispatch('mostrarMensaje', ['warning', $ex->getMessage()]);

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

        $this->editar = true;



    }

    public function mount(){

        $this->solicitantes = Constantes::SOLICITANTES;

        $this->documentos_entrada = ['ESCRITURA PÚBLICA'];

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
        return view('livewire.entrada.persona-moral');
    }
}
