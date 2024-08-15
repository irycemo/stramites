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

    public $cantidadCopiasCertificadas;
    public $cantidadCopiasSimples;

    public $total;

    public function updatedCategoria(){

        $this->servicios = Servicio::where('estado', 'activo')
                                    ->where('categoria_servicio_id', $this->categoria)
                                    ->orderBy('nombre')->get();

    }

    public function updated(){

        set_time_limit(120);

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

        $this->total();

    }

    public function tramites($ubicacion){

        $tramites = Tramite::with('servicio:id,nombre,categoria_servicio_id', 'adicionadoPor:id,adiciona,id_servicio')
                            ->select('id', 'id_servicio', 'adiciona', 'fecha_pago', 'tipo_servicio', 'monto', 'creado_por')
                            ->whereNotNull('fecha_pago')
                            ->when(isset($this->servicio_id) && $this->servicio_id != "", function($q){
                                return $q->where('id_servicio', $this->servicio_id);
                            })
                            ->when(isset($this->categoria) && $this->categoria != "", function($q){
                                return $q->whereHas('servicio', function($q){
                                    $q->select('id', 'nombre', 'categoria_servicio_id')->where('categoria_servicio_id', $this->categoria);
                                });
                            })
                            ->when(isset($this->tipo_servicio) && $this->tipo_servicio != "", function($q){
                                return $q->where('tipo_servicio', $this->tipo_servicio);
                            })
                            ->withWhereHas('creadoPor', function($q) use ($ubicacion){
                                $q->select('id', 'ubicacion')->where('ubicacion', $ubicacion);
                            })
                            ->whereBetween('fecha_pago', [$this->fecha1, $this->fecha2])
                            ->get();

        $array2 = [];

        $this->cantidadCopiasCertificadas = Tramite::whereHas('adicionadoPor', function ($q){
                                                    $q->where('id_servicio', 2);
                                                })
                                                ->where('id_servicio', 1)
                                                ->whereHas('creadoPor', function($q) use ($ubicacion){
                                                    $q->where('ubicacion', $ubicacion);
                                                })
                                                ->when(isset($this->tipo_servicio) && $this->tipo_servicio != "", function($q){
                                                    return $q->where('tipo_servicio', $this->tipo_servicio);
                                                })
                                                ->whereBetween('fecha_pago', [$this->fecha1, $this->fecha2])
                                                ->count();

        $this->cantidadCopiasSimples = Tramite::whereHas('adicionadoPor', function ($q){
                                                    $q->where('id_servicio', 6);
                                                })
                                                ->where('id_servicio', 1)
                                                ->whereHas('creadoPor', function($q) use ($ubicacion){
                                                    $q->where('ubicacion', $ubicacion);
                                                })
                                                ->when(isset($this->tipo_servicio) && $this->tipo_servicio != "", function($q){
                                                    return $q->where('tipo_servicio', $this->tipo_servicio);
                                                })
                                                ->whereBetween('fecha_pago', [$this->fecha1, $this->fecha2])
                                                ->count();


        foreach ($tramites as $tramite) {

            if(isset($array2[$tramite->servicio->nombre])) continue;

            $array2[$tramite->servicio->nombre]['monto'] = $tramites->where('servicio', $tramite->servicio)->sum('monto');

            $array2[$tramite->servicio->nombre]['cantidad'] = $tramites->where('servicio', $tramite->servicio)->count();

        }

        if($this->servicio_id != 1){

            foreach ($array2 as $key => $item) {

                if(str_contains($key, 'Copias simples'))
                    $array2[$key]['monto'] = (float)$array2[$key]['monto'] - ($this->cantidadCopiasSimples * (float)Servicio::find(1)->ordinario);

                if(str_contains($key, 'Copias certificadas'))
                    $array2[$key]['monto'] = (float)$array2[$key]['monto'] - ($this->cantidadCopiasCertificadas * (float)Servicio::find(1)->ordinario);

            }

        }

        return $array2;

    }

    public function total(){

        $this->total =
            collect($this->rpp)->sum('monto') +
            collect($this->regional1)->sum('monto') +
            collect($this->regional2)->sum('monto') +
            collect($this->regional3)->sum('monto') +
            collect($this->regional4)->sum('monto') +
            collect($this->regional5)->sum('monto') +
            collect($this->regional6)->sum('monto') +
            collect($this->regional7)->sum('monto');


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
