<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{

    public function __invoke()
    {

        if(auth()->user()->hasRole('Administrador')){

            if(Cache::get('tramties_estado_dashboard_admin')){

                $tramtiesEstado = Cache::get('tramties_estado_dashboard_admin');

            }else{

                $tramtiesEstado = Cache::remember('tramties_estado_dashboard_admin', now()->addHour(), function(){

                    return Tramite::selectRaw('estado, count(estado) count')
                                        ->whereMonth('created_at', now()->month)
                                        ->groupBy('estado')
                                        ->get();

                });

            }

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

            if(Cache::get('tramites_uruapan_dashboard_admin')){

                $tramites_uruapan = Cache::get('tramites_uruapan_dashboard_admin');

            }else{

                $tramites_uruapan = Cache::remember('tramites_uruapan_dashboard_admin', now()->addHour(), function(){

                    return Tramite::selectRaw('estado, count(estado) count')
                                        ->where('distrito', 2)
                                        ->whereMonth('created_at', now()->month)
                                        ->groupBy('estado')
                                        ->get();

                });

            }

            return view('dashboard', compact('data', 'tramtiesEstado', 'tramtiesUruapan'));

        }elseif(auth()->user()->ubicacion == 'Regional 4'){

            if(Cache::get('tramites_uruapan_dashboard_admin')){

                $tramites_uruapan = Cache::get('tramites_uruapan_dashboard_admin');

            }else{

                $tramites_uruapan = Cache::remember('tramites_uruapan_dashboard_admin', now()->addHour(), function(){

                    return Tramite::selectRaw('estado, count(estado) count')
                                        ->where('distrito', 2)
                                        ->whereMonth('created_at', now()->month)
                                        ->groupBy('estado')
                                        ->get();

                });

            }

            if(Cache::get('tramites_uruapan_dashboard_user')){

                $tramites_diarios_uruapan = Cache::get('tramites_uruapan_dashboard_user');

            }else{

                $tramites_diarios_uruapan = Cache::remember('tramites_uruapan_dashboard_user', now()->addHour(), function(){

                    return Tramite::select('id', 'id_servicio', 'distrito','created_at')
                                        ->with('servicio:id,nombre')
                                        ->where('distrito', 2)
                                        ->where('creado_por', auth()->id())
                                        ->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])
                                        ->get()
                                        ->groupBy('id_servicio')
                                        ->map(function($tramite){
                                            return [
                                                    'servicio' => $tramite[0]->servicio->nombre,
                                                    'cantidad' => count($tramite)
                                                ];
                                        });

                });

            }

            return view('dashboard', compact('tramites_uruapan', 'tramites_diarios_uruapan'));

        }

        return view('dashboard');
    }

}
