<?php

namespace App\Traits;

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

            $this->batch->delete();

            $this->job = false;

            $this->dispatch('imprimir_recibo', ['tramite' => $this->tramiteId]);

        }

    }

}
