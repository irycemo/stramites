<?php

namespace App\Livewire\Reportes;

use App\Models\Tramite;
use Livewire\Component;
use App\Models\Servicio;
use App\Constantes\Constantes;
use App\Models\CategoriaServicio;

class Recaudacion extends Component
{

    public $categorias;
    public $categoria;
    public $servicios;
    public $servicio_id;
    public $fecha1;
    public $fecha2;
    public $tipo_servicio;
    public $ubicaciones;

    public $tramites;

    public $montoConsultas;

    public $array = [];

    public function updatedCategoria(){

        $this->servicios = Servicio::where('estado', 'activo')
                                    ->where('categoria_servicio_id', $this->categoria)
                                    ->orderBy('nombre')->get();

    }

    public function updated(){

        $this->reset(['tramites']);

        $count = 0;
        $count2 = 0;

        $conjunto = Tramite::with('servicio', 'adicionadoPor.servicio', 'creadoPor')
                            ->whereNotNull('fecha_pago')
                            ->when(isset($this->servicio_id) && $this->servicio_id != "", function($q){
                                return $q->where('id_servicio', $this->servicio_id);
                            })
                            ->when(isset($this->categoria) && $this->categoria != "", function($q){
                                return $q->whereHas('servicio', function($q){
                                    $q->where('categoria_servicio_id', $this->categoria);
                                });
                            })
                            ->when(isset($this->tipo_servicio) && $this->tipo_servicio != "", function($q){
                                return $q->where('tipo_servicio', $this->tipo_servicio);
                            })
                            ->whereBetween('fecha_pago', [$this->fecha1 . ' 00:00:00', $this->fecha2 . ' 23:59:59'])
                            ->get();

        $this->array = [];

        foreach($conjunto as $tramite){

            if($tramite->id_servicio === 1 && $tramite->adicionadoPor->count() > 0){

                if($tramite->adicionadoPor->first()->id_servicio == 2){

                    $count ++;

                    $this->array[$tramite->creadoPor->ubicacion][$tramite->adicionadoPor->first()->servicio->nombre] = $count;

                }
                elseif($tramite->adicionadoPor->first()->id_servicio == 6){

                    $count2 ++;

                    $this->array[$tramite->creadoPor->ubicacion][$tramite->adicionadoPor->first()->servicio->nombre] = $count2;

                }

            }

        }

        $tramites = $conjunto->map(function($tramite){

            $object = (object)[];

            $object->servicio = $tramite->servicio->nombre;

            $object->ubicacion = $tramite->creadoPor->ubicacion;

            $object->monto = $tramite->monto;

            return $object;

        });

        foreach ($this->ubicaciones as $ubicacion) {

            foreach ($tramites as $tramite) {

                $this->tramites[$ubicacion][$tramite->servicio] = $tramites->where('ubicacion', $ubicacion)->where('servicio', $tramite->servicio)->sum('monto');

            }

        }

        if($this->servicio_id != 1){


            foreach ($this->array as $key1 => $item) {

                foreach ($item as $key2 => $value) {

                    $this->tramites[$key1][$key2] = (float)$this->tramites[$key1][$key2] - ($value * (float)Servicio::find(1)->ordinario);

                }

            }

        }

    }

    public function mount(){

        $this->servicios = Servicio::where('estado', 'activo')
                                        ->orderBy('nombre')->get();

        $this->categorias = CategoriaServicio::orderBy('nombre')->get();

        $this->ubicaciones = Constantes::UBICACIONES;

        unset($this->ubicaciones[0]);

    }

    public function render()
    {
        return view('livewire.reportes.recaudacion');
    }

}
