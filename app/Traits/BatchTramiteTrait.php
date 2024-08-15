<?php

namespace App\Traits;

use Throwable;
use App\Models\Tramite;
use Illuminate\Bus\Batch;
use App\Jobs\GenerarFolioTramite;
use Illuminate\Support\Facades\Bus;

trait BatchTramiteTrait
{

    public $job = false;
    public $batchId;
    public $batch_counter;
    public $tramiteId;

    public function crearBatch($id){

        $this->batch_counter = 0;

        $this->tramiteId = $id;

        $this->job = true;

        $batch = Bus::batch([

            new GenerarFolioTramite($this->tramiteId)

        ])->dispatch();

        $this->batchId = $batch->id;

    }

    public function getBatchProperty(){

        if(!$this->batchId){

            return null;
        }

        return Bus::findBatch($this->batchId);

    }

    public function checkBatch(){

        $this->batch_counter ++;

        if($this->batch_counter == 60) $this->job = false;

        if($this->batch && $this->batch->finished()){

            if($this->batch->failedJobs >= 1){

                $this->dispatch('mostrarMensaje', ['error', 'Hubo un error.']);

                $this->batch->delete();

                $this->job = false;

                return;

            }else{

                $this->batch->delete();

                $this->job = false;

                $this->dispatch('imprimir_recibo', ['tramite' => $this->tramiteId]);

            }

        }

    }

}
