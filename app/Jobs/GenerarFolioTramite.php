<?php

namespace App\Jobs;

use Throwable;
use App\Models\Tramite;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class GenerarFolioTramite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    private Tramite $tramite;

    public function __construct(int $tramiteId)
    {

        $this->onQueue('tramiteFolioQueue');

        $this->tramite = Tramite::findOrFail($tramiteId);

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $this->tramite->numero_control = (Tramite::where('año', $this->tramite->año)->max('numero_control') ?? 0) + 1;

        $this->tramite->save();

    }

    public function failed(Throwable $exception): void
    {
        // Send user notification of failure, etc...
    }
}
