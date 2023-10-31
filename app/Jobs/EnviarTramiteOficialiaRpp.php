<?php

namespace App\Jobs;

use App\Http\Services\SistemaRPP\SistemaRppService;
use App\Models\Tramite;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class EnviarTramiteOficialiaRpp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Tramite $tramite;

    public function __construct($tramiteId)
    {

        $this->tramite = Tramite::findOrFail($tramiteId);

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        (new SistemaRppService())->insertarSistemaRpp($this->tramite);
    }
}
