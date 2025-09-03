<?php

namespace App\Livewire\Reportes;

use App\Models\User;
use App\Models\Tramite;
use Livewire\Component;
use App\Models\Servicio;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use App\Constantes\Constantes;
use App\Jobs\TramiteExportJob;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class Tramites extends Component
{

    use WithPagination;

    public $usuarios;
    public $servicios;
    public $estados;
    public $ubicaciones;
    public $ubicacion;
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

        $this->fecha1 = $this->fecha1 . ' 00:00:00';
        $this->fecha2 = $this->fecha2 . ' 23:59:59';

        $this->data = [
            $this->estado,
            $this->ubicacion,
            $this->servicio_id,
            $this->usuario_id,
            $this->tipo_servicio,
            $this->solicitante,
            $this->fecha1,
            $this->fecha2,
            auth()->user()->name
        ];

    }

    /* public function descargarExcel(){


        try {

            return Excel::download(new TramiteExport($this->estado, $this->ubicacion, $this->servicio_id, $this->usuario_id, $this->tipo_servicio, $this->solicitante, $this->fecha1, $this->fecha2), 'Reporte_de_tramites_' . now()->format('d-m-Y') . '.xlsx');
            $this->file_name = Str::random(40) . '.xlsx';

            $batch = Bus::batch([
                new TramiteExportJob($this->estado, $this->ubicacion, $this->servicio_id, $this->usuario_id, $this->tipo_servicio, $this->solicitante, $this->fecha1, $this->fecha2, auth()->user()->name, $this->file_name),
            ])->dispatch();

            $this->batchId = $batch->id;

        } catch (\Throwable $th) {

            Log::error("Error generar archivo de reporte de trámites por el usuario: (id: " . auth()->user()->id . ") " . auth()->user()->name . ". " . $th);

            $this->dispatch('mostrarMensaje', ['error', "Ha ocurrido un error."]);

        }

    } */

    public function mount(){

        $this->usuarios = User::all()->sortby('name');

        $this->servicios = Servicio::all()->sortby('nombre');

        $this->solicitantes = Constantes::SOLICITANTES;

        $this->ubicaciones = Constantes::UBICACIONES;

    }

    public function render()
    {

        $tramites = Tramite::with('servicio', 'creadoPor', 'actualizadoPor')
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

        return view('livewire.reportes.tramites', compact('tramites'));
    }

}
