<?php

namespace App\Livewire\Reportes;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Jobs\TramiteExportJob;
use Illuminate\Support\Facades\Bus;

class Descarga extends Component
{

    public $batchId;
    public $exporting = false;
    public $exportFinished = false;
    public $file_name;

    public $data;
    public $fiel_name;

    public function getExportBatchProperty()
    {

        if (!$this->batchId) {

            return null;

        }

        return Bus::findBatch($this->batchId);

    }

    public function updateExportProgress()
    {
        $this->exportFinished = $this->exportBatch->finished();

        if ($this->exportFinished) {

            $this->exporting = false;

        }
    }

    public function descargarExcel(){

        return response()->download(storage_path('app/livewire-tmp/' . $this->file_name), 'Reporte_de_tramites_' . now()->format('d-m-Y') . '.xlsx');

    }

    #[On('reciveData')]
    public function reciveData($data){

        $this->data = $data;

    }

    public function exportar(){

        $this->exporting = true;
        $this->exportFinished = false;

        $this->file_name = Str::random(40) . '.xlsx';

        $batch = Bus::batch([
            new TramiteExportJob($this->data, $this->file_name),
        ])->dispatch();

        $this->batchId = $batch->id;

    }

    public function render()
    {
        return view('livewire.reportes.descarga');
    }
}
