<?php

namespace App\Livewire\Reportes;

use App\Models\User;
use App\Models\Tramite;
use Livewire\Component;
use App\Models\Servicio;
use Livewire\WithPagination;
use App\Constantes\Constantes;

class Tramites extends Component
{

    use WithPagination;

    public $usuarios;
    public $servicios;
    public $estados;
    public $distritos;
    public $distrito;
    public $estado;
    public $servicio_id;
    public $usuario_id;
    public $tipo_servicio;
    public $solicitantes;
    public $solicitante;
    public $fecha1;
    public $fecha2;

    public $pagination = 10;

    public $data;

    public function updated(){

        $this->fecha1;
        $this->fecha2;

        $this->data = [
            $this->estado,
            $this->distrito,
            $this->servicio_id,
            $this->usuario_id,
            $this->tipo_servicio,
            $this->solicitante,
            $this->fecha1 . ' 00:00:00',
            $this->fecha2 . ' 23:59:59',
            auth()->user()->name
        ];

        $this->dispatch('reciveData', $this->data);

    }

    public function mount(){

        $this->usuarios = User::all()->sortby('name');

        $this->servicios = Servicio::all()->sortby('nombre');

        $this->solicitantes = Constantes::SOLICITANTES;

        $this->distritos = Constantes::DISTRITOS;

    }

    public function render()
    {

        $tramites = Tramite::with('servicio', 'creadoPor', 'actualizadoPor')
                                ->when(isset($this->servicio_id) && $this->servicio_id != "", function($q){
                                    $q->where('id_servicio', $this->servicio_id);
                                })
                                ->when(isset($this->usuario_id) && $this->usuario_id != "", function($q){
                                    $q->where('creado_por', $this->usuario_id);
                                })
                                ->when(isset($this->estado) && $this->estado != "", function($q){
                                    $q->where('estado', $this->estado);
                                })
                                ->when(isset($this->tipo_servicio) && $this->tipo_servicio != "", function($q){
                                    $q->where('tipo_servicio', $this->tipo_servicio);
                                })
                                ->when(isset($this->solicitante) && $this->solicitante != "", function($q){
                                    $q->where('solicitante', $this->solicitante);
                                })
                                ->when(isset($this->distrito) && $this->distrito != "", function($q){
                                    $q->where('distrito', $this->distrito);
                                })
                                ->whereBetween('created_at', [$this->fecha1 . ' 00:00:00', $this->fecha2 . ' 23:59:59'])
                                ->paginate($this->pagination);

        return view('livewire.reportes.tramites', compact('tramites'));
    }

}
