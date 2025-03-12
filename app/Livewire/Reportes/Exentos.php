<?php

namespace App\Livewire\Reportes;

use App\Models\User;
use App\Models\Tramite;
use Livewire\Component;
use App\Models\Servicio;
use App\Constantes\Constantes;
use App\Exports\ExentosExport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class Exentos extends Component
{

    public $usuarios;
    public $servicios;
    public $ubicaciones;
    public $ubicacion;
    public $servicio_id;
    public $usuario_id;
    public $solicitantes;
    public $solicitante;
    public $fecha1;
    public $fecha2;

    public $pagination = 10;

    public function descargarExcel(){

        $this->fecha1 = $this->fecha1 . ' 00:00:00';
        $this->fecha2 = $this->fecha2 . ' 23:59:59';


        try {

            return Excel::download(new ExentosExport($this->ubicacion, $this->servicio_id, $this->usuario_id, $this->solicitante, $this->fecha1, $this->fecha2), 'Reporte_de_tramites_exentos ' . now()->format('d-m-Y') . '.xlsx');

        } catch (\Throwable $th) {

            Log::error("Error generar archivo de reporte de trámites por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    }

    public function mount(){

        $this->usuarios = User::all()->sortby('name');

        $this->servicios = Servicio::all()->sortby('nombre');

        $this->solicitantes = Constantes::SOLICITANTES;

        $this->ubicaciones = Constantes::UBICACIONES;

    }

    public function render()
    {

        $tramites = Tramite::with('servicio', 'creadoPor', 'actualizadoPor')
                                ->where('monto', 0)
                                ->when(isset($this->servicio_id) && $this->servicio_id != "", function($q){
                                    return $q->where('id_servicio', $this->servicio_id);
                                })
                                ->when(isset($this->usuario_id) && $this->usuario_id != "", function($q){
                                    return $q->where('creado_por', $this->usuario_id);
                                })
                                ->when(isset($this->estado) && $this->estado != "", function($q){
                                    return $q->where('estado', $this->estado);
                                })
                                ->when(isset($this->tipo_servicio) && $this->tipo_servicio != "", function($q){
                                    return $q->where('tipo_servicio', $this->tipo_servicio);
                                })
                                ->when(isset($this->solicitante) && $this->solicitante != "", function($q){
                                    return $q->where('solicitante', $this->solicitante);
                                })
                                ->when(isset($this->ubicacion) && $this->ubicacion != "", function($q){
                                    return $q->whereHas('creadoPor', function($q){
                                        $q->where('ubicacion', $this->ubicacion);
                                    });
                                })
                                ->whereBetween('created_at', [$this->fecha1 . ' 00:00:00', $this->fecha2 . ' 23:59:59'])
                                ->paginate($this->pagination);

        return view('livewire.reportes.exentos', compact('tramites'));
    }
}
