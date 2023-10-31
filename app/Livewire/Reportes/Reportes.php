<?php

namespace App\Livewire\Reportes;

use Livewire\Component;

class Reportes extends Component
{

    public $area;
    public $fecha1;
    public $fecha2;
    public $estado;

    public $verTramites;

    protected function rules(){
        return [
            'fecha1' => 'required|date',
            'fecha2' => 'required|date|after:date1',
         ];
    }

    protected $messages = [
        'fecha1.required' => "La fecha inicial es obligatoria.",
        'fecha2.required' => "La fecha final es obligatoria.",
    ];

    public function updatedArea(){

        if($this->area == 'tramites'){

            $this->verTramites = true;

        }

    }

    public function mount(){

        $this->area = request()->query('area');

        $this->fecha1 = request()->query('fecha1');

        $this->fecha2 = request()->query('fecha2');

        $this->estado = request()->query('estado');

        $this->updatedArea();

    }

    public function render()
    {
        return view('livewire.reportes.reportes')->extends('layouts.admin');
    }

}
