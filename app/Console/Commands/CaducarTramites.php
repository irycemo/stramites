<?php

namespace App\Console\Commands;

use App\Models\Tramite;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CaducarTramites extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'caducar:tramites';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Proceso para caducar trámites sin pago de los últimos 10 dias';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {

            $tramites = Tramite::with('adicionaAlTramite')
                                    ->where('estado', 'nuevo')
                                    ->whereDate('created_at', '<', $this->calcularDia())
                                    ->get();

            foreach($tramites as $item){

                $item->update(['estado' => 'caducado']);

            }

            info('Proceso para caducar trámites sin pago de los últimos 10 dias concluido con éxito.');

        } catch (\Throwable $th) {

            Log::error("Error en el proceso para caducar trámites sin pago de los últimos 10 dias. " . $th);

        }

    }

    public function calcularDia(){

        $actual = now();

            for ($i=10; $i < 0; $i--) {

                $actual->subDay();

                while($actual->isWeekend()){

                    $actual->subDay();

                }

            }

            return $actual->toDateString();

    }

}
