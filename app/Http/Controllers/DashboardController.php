<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Tramite;
use App\Models\Servicio;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function __invoke()
    {

        if(auth()->user()->hasRole('Administrador')){

            $tramtiesEstado = Tramite::selectRaw('estado, count(estado) count')
                                        ->whereMonth('created_at', Carbon::now()->month)
                                        ->groupBy('estado')
                                        ->get();

            $tramites = cache()->get('graficaRecaudacion');

            if(!$tramites) $$tramites = [];

            $data = [];

            $labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

            foreach($tramites as $tramite){
                foreach($labels as $label){
                    $data[$tramite->year][$label] = 0;
                }
            }

            foreach($tramites as $tramite){

                foreach($labels as $label){

                    if($tramite->month === $label ){
                        if($data[$tramite->year][$label] == 0)
                            $data[$tramite->year][$label] = $tramite->sum;
                    }
                }

            }

            return view('dashboard', compact('data', 'tramtiesEstado'));

        }

        return view('dashboard');
    }

}
