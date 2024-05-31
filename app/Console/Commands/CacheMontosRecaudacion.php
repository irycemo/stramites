<?php

namespace App\Console\Commands;

use Carbon\Carbon;
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
                                ->whereNotNUll('fecha_pago')
                                ->whereNotIn('id_servicio', [2,6])
                                ->groupBy('year', 'month')
                                ->orderBy('year', 'asc')
                                ->get();

        $copias = Tramite::select('id', 'monto', 'adiciona', 'created_at')
                ->with('adicionaAlTramite')
                ->whereNotNUll('fecha_pago')
                ->whereIn('id_servicio', [2,6])
                ->get()
                ->map(function($tramite){

                    if($tramite->adicionaAlTramite->id_servicio == 1){

                        $tramite->monto = $tramite->monto - $tramite->adicionaAlTramite->monto;

                    }

                    return $tramite;

                });

        foreach($tramites as $tramite){

            foreach($copias as $copia){

                if($tramite->year == Carbon::parse($copia->created_at)->format('Y') && $tramite->month == Carbon::parse($copia->created_at)->format('F')){

                    $tramite->sum += $copia->monto;

                }

            }

        }

        cache()->put('graficaRecaudacion',$tramites);

    }
}
