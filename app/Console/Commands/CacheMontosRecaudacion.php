<?php

namespace App\Console\Commands;

use App\Models\Tramite;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CacheMontosRecaudacion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:recaudacion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Guardar en cache la información de recaudación calculada diariamente';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        Cache::forget('graficaRecaudacion');

        $tramites = Tramite::selectRaw('year(created_at) year, monthname(created_at) month, count(*) data, sum(monto) sum')
                                ->whereNotNull('documento_de_pago')
                                ->groupBy('year', 'month')
                                ->orderBy('year', 'asc')
                                ->get();

        cache()->put('graficaRecaudacion', $tramites);

    }
}
