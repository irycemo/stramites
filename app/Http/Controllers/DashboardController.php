<?php

namespace App\Http\Controllers;

use App\Models\Tramite;

class DashboardController extends Controller
{

    public function __invoke()
    {

        if(auth()->user()->hasRole('Administrador')){

            $tramtiesEstado = Tramite::selectRaw('estado, count(estado) count')
                                        ->whereMonth('created_at', now()->month)
                                        ->groupBy('estado')
                                        ->get();

            $tramites = cache()->get('graficaRecaudacion');

            if(!$tramites) $tramites = [];

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

            $tramtiesUruapan = Tramite::selectRaw('estado, count(estado) count')
                                        ->where('distrito', 2)
                                        ->whereMonth('created_at', now()->month)
                                        ->groupBy('estado')
                                        ->get();

            return view('dashboard', compact('data', 'tramtiesEstado', 'tramtiesUruapan'));

        }elseif(auth()->user()->ubicacion == 'Regional 4'){

            $tramtiesUruapan = Tramite::selectRaw('estado, count(estado) count')
            ->where('distrito', 2)
            ->whereMonth('created_at', now()->month)
            ->groupBy('estado')
            ->get();

            return view('dashboard', compact('tramtiesUruapan'));

        }

        return view('dashboard');
    }

}
