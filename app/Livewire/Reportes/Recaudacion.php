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

    public $rpp;
    public $regional1;
    public $regional2;
    public $regional3;
    public $regional4;
    public $regional5;
    public $regional6;
    public $regional7;

    public $montoConsultas;

    public $array = [];

    public function updatedCategoria(){

        $this->servicios = Servicio::where('estado', 'activo')
                                    ->where('categoria_servicio_id', $this->categoria)
                                    ->orderBy('nombre')->get();

    }

    public function updated(){

        set_time_limit(60);

        $this->reset([
            'rpp',
            'regional1',
            'regional2',
            'regional3',
            'regional4',
            'regional5',
            'regional6',
            'regional7',
        ]);

        $this->rpp = $this->tramites('RPP');
        $this->regional1 = $this->tramites('Regional 1');
        $this->regional2 = $this->tramites('Regional 2');
        $this->regional3 = $this->tramites('Regional 3');
        $this->regional4 = $this->tramites('Regional 4');
        $this->regional5 = $this->tramites('Regional 5');
        $this->regional6 = $this->tramites('Regional 6');
        $this->regional7 = $this->tramites('Regional 7');

    }

    public function tramites($string){

        $count = 0;
        $count2 = 0;

        $conjunto = Tramite::with('servicio:id,nombre', 'adicionadoPor:id,adiciona,id_servicio', 'creadoPor:id:ubicacion')
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
                            ->whereHas('creadoPor', function($q) use ($string){
                                $q->where('ubicacion', $string);
                            })
                            ->whereBetween('fecha_pago', [$this->fecha1 . ' 00:00:00', $this->fecha2 . ' 23:59:59'])
                            ->get();

        $array = [];

        $array2 = [];

        foreach($conjunto as $tramite){

            if($tramite->id_servicio === 1 && $tramite->adicionadoPor->count() > 0){

                if($tramite->adicionadoPor->first()->id_servicio == 2){

                    $count ++;

                    $array[$tramite->servicio->nombre] = $count;

                }
                elseif($tramite->adicionadoPor->first()->id_servicio == 6){

                    $count2 ++;

                    $array[$tramite->servicio->nombre] = $count2;

                }

            }

        }

        $tramites = $conjunto->map(function($tramite){

            $object = (object)[];

            $object->servicio = $tramite->servicio->nombre;

            $object->monto = $tramite->monto;

            return $object;

        });

        foreach ($tramites as $tramite) {

            $array2[$tramite->servicio] = $tramites->where('servicio', $tramite->servicio)->sum('monto');

        }

        if($this->servicio_id != 1){

            foreach ($array as $key1 => $value) {

                $array2[$key1] = (float)$array2[$key1] - ($value * (float)Servicio::find(1)->ordinario);

            }

        }

        return $array2;

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
