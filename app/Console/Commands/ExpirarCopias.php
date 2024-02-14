<?php

namespace App\Console\Commands;

use App\Models\Tramite;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpirarCopias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expirar:copias';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Proceso para expirar copias que su fecha de pago tiene mas de un mes.';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        try {

            $tramites = Tramite::whereHas('servicio', function($q){
                                    $q->whereIn('clave_ingreso', ['DL14', 'DL13']);
                                })
                                ->whereIn('estado', ['pagado', 'rechazado'])
                                ->whereDate('fecha_pago', '>', now()->subMonth()->format('Y-m-d'))
                                ->get();

            foreach($tramites as $item){

                $item->update(['estado' => 'expirado']);

            }

            info('Proceso para expirar copias que su fecha de pago tiene mas de un mes concluido con éxito.');

        } catch (\Throwable $th) {

            Log::error("Error en el proceso para expirar copias que su fecha de pago tiene mas de un mes. " . $th);

        }

    }

}
