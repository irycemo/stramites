<?php

namespace App\Console\Commands;

use App\Models\Tramite;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ConcluidConsultasDiariamente extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'concluir:consultas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tarea programada para concluir todos las consultas que llevan 5 dias desde su registro';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        try {

            $tramites = Tramite::with('adicionaAlTramite')
                                    ->whereHas('servicio', function($q){
                                        $q->where('clave_ingreso', 'DC93');
                                    })
                                    ->whereIn('estado', ['pagado', 'nuevo', 'rechazado'])
                                    ->whereDate('created_at', '>', $this->calcularDia())
                                    ->get();

            foreach($tramites as $item){

                $item->update(['estado' => 'expirado']);

            }

            info('Proceso para concluir consultas que tienen 5 dias desde su registro concluido con éxito.');

        } catch (\Throwable $th) {

            Log::error("Error en el proceso para concluir consultas que tienen 5 dias desde su registro. " . $th);

        }

    }

    public function calcularDia(){

        $actual = now();

            for ($i=5; $i < 0; $i--) {

                $actual->subDay();

                while($actual->isWeekend()){

                    $actual->subDay();

                }

            }

            return $actual->toDateString();

    }

}
