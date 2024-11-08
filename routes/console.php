<?php

use App\Models\Tramite;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Artisan::command('copias', function(){

    $commun = [
        'estado' => 'nuevo',
        'año' => 2024,
        'usuario' => 2,
        'id_servicio' => 2,
        'solicitante' => 'Usuario',
        'nombre_solicitante' => 'Administración deportiva especializada S.A de C.V.',
        'distrito' => 1,
        'seccion' => 'Comercio',
        'tipo_servicio' => 'ordinario',
        'tipo_tramite' => 'normal',
    ];

    $array = [
        [
            'cantidad' => 1,
            'observaciones' => '',
            ''
        ],
    ];

    for ($i=0; $i < 665; $i++) {

        for ($i=0; $i < count($array); $i++) {

            Tramite::create($commun + $array[$i] + ['numero_control' => (Tramite::where('año', 2024)->where('usuario', 2)->max('numero_control') ?? 0) + 1]);

        }

    }

});
