<?php

namespace App\Jobs;

use Throwable;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use App\Exports\TramiteExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TramiteExportJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 0;

    public $estado;
    public $distrito;
    public $servicio_id;
    public $usuario_id;
    public $tipo_servicio;
    public $solicitante;
    public $fecha1;
    public $fecha2;
    public $creator;

    public function __construct(
        public $data,
        public $file_name,
    )
    {

        $this->estado = $this->data[0];
        $this->distrito = $this->data[1];
        $this->servicio_id = $this->data[2];
        $this->usuario_id = $this->data[3];
        $this->tipo_servicio = $this->data[4];
        $this->solicitante = $this->data[5];
        $this->fecha1 = $this->data[6];
        $this->fecha2 = $this->data[7];
        $this->creator = $this->data[8];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        (new TramiteExport(
                            $this->estado,
                            $this->distrito,
                            $this->servicio_id,
                            $this->usuario_id,
                            $this->tipo_servicio,
                            $this->solicitante,
                            $this->fecha1,
                            $this->fecha2,
                            $this->creator
                        ))->store('livewire-tmp/' . $this->file_name);

    }

    public function failed(?Throwable $exception): void
    {
        Log::error($exception);
    }

}
