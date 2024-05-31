<?php

namespace App\Livewire\Entrada;

use App\Constantes\Constantes;
use App\Models\Tramite;
use App\Models\Configuracion;
use Livewire\Component;
use App\Models\Servicio;
use App\Models\CategoriaServicio;
use App\Traits\BatchTramiteTrait;

class Entrada extends Component
{

    use BatchTramiteTrait;

    public $categorias;
    public $categoria;
    public $categoria_seleccionada;
    public $servicios;
    public $servicio;
    public $servicio_seleccionado;
    public $tramite;
    public $numero_control;
    public $usuario;
    public $año;
    public $años;

    public $flag = false;

    public $flags = [
        'Certificaciones' => false,
        'InscripcionesPropiedad' => false,
        'Gravamenes' => false,
    ];

    protected $listeners = [
        'reset' => 'resetAll',
        'crearBatch' => 'crearBatch'
    ];

    public function resetAll(){

        $this->reset(['categoria_seleccionada', 'servicio_seleccionado', 'categoria', 'servicios', 'servicio', 'flags']);

    }

    public function updatedCategoriaSeleccionada(){

        if($this->categoria_seleccionada == ""){

            $this->reset('categoria_seleccionada', 'servicio_seleccionado', 'servicio');

            return;

        }

        $this->categoria = json_decode($this->categoria_seleccionada, true);

        $this->servicios = Servicio::with('categoria')->where('categoria_servicio_id', $this->categoria['id'])->where('estado', 'activo')->orderBy('nombre')->get();

        $this->reset(['servicio_seleccionado', 'servicio', 'servicio_seleccionado', 'flags']);

    }

    public function updatedServicioSeleccionado(){

        if($this->servicio_seleccionado == ""){

            $this->reset('flags');

            return;

        }

        $this->servicio = json_decode($this->servicio_seleccionado, true);

        $this->mostrarComponente($this->categoria['nombre']);

        $this->dispatch('cambioServicio', $this->servicio);

        if($this->tramite)
            $this->dispatch('cargarTramite', $this->tramite);

        if($this->flag){

            $this->reset('tramite');

            $this->flag = false;

        }

    }

    public function mostrarComponente(string $categoria){

        $componente = match($categoria){
                            'Certificaciones' => 'Certificaciones',
                            'Inscripciones - Propiedad' => 'InscripcionesPropiedad',
                            'Inscripciones - Gravamenes' => 'Gravamenes',
                            'Cancelación - Gravamenes' => 'Gravamenes',
                            default => 'No encontrada',

                        };

        if($componente == 'No encontrada'){

            $this->dispatch('mostrarMensaje', ['error', 'Seleccione una catagoría correcta']);

            return;

        }

        foreach($this->flags as $key => $flag){
            $this->flags[$key] = false;
        }

        $this->flags[$componente] = true;

    }

    public function buscarTramite(){

        $this->validate(['numero_control' => 'required', 'año' => 'required', 'usuario' => 'required']);

        $this->reset('categoria_seleccionada', 'servicio_seleccionado', 'flags');

        $this->tramite = Tramite::with('servicio.categoria')
                                    ->where('año', $this->año)
                                    ->where('numero_control', $this->numero_control)
                                    ->where('usuario', $this->usuario)
                                    ->whereIn('estado', ['nuevo', 'rechazado'])
                                    ->first();

        if(!$this->tramite){

            $this->dispatch('mostrarMensaje', ['error', "No se encontro el trámite."]);

            $this->reset(['categoria_seleccionada', 'servicio_seleccionado', 'servicios', 'categoria','numero_control', 'tramite', 'flag']);

            return;

        }

        $this->categoria_seleccionada = json_encode($this->tramite->servicio->categoria);

        $this->updatedCategoriaSeleccionada();

        $this->servicio_seleccionado = json_encode($this->tramite->servicio);

        $this->updatedServicioSeleccionado();

        $this->flag = true;

        $this->reset(['categoria_seleccionada', 'servicio_seleccionado', 'servicios', 'categoria','numero_control']);

    }

    public function mount(): void
    {

        $this->años = Constantes::AÑOS;

        if(!cache()->get('categorias')){

            $this->categorias = CategoriaServicio::orderBy('nombre')->get();

            cache()->put('categorias', $this->categorias);

        }else{

            $this->categorias = cache()->get('categorias');

        }

        $this->año = now()->format('Y');

    }

    public function render()
    {
        return view('livewire.entrada.entrada')->extends('layouts.admin');
    }
}
