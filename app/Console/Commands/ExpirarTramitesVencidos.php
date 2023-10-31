<?php

namespace App\Console\Commands;

use App\Models\Tramite;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpirarTramitesVencidos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expirar:tramites-vencidos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tárea para expirar tramites cuya linea de captura ha expirado';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        try {

            $tramites = Tramite::with('adicionaALTramite')
                                    ->whereDate('limite_de_pago', '<=', now()->toDateString())
                                    ->get();

            foreach($tramites as $item)
                (new TramiteService($item))->cambiarEstado('expirado');

        } catch (\Throwable $th) {

            Log::error("Error al expirar trámites. " . $th);

        }

    }
}
