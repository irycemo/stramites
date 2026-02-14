<?php

use App\Http\Controllers\TramitesController;
use App\Http\Services\LineasDeCaptura\LineaCapturaApi;
use App\Http\Services\Tramites\TramiteService;
use App\Jobs\BienestarTramiteJob;
use App\Models\Tramite;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

Artisan::command('bienestar', function(){

    $this->info('Creando 2000 tramite de vivienda bienestar.');

    $progressBar = $this->getOutput()->createProgressBar(2000);

    for ($i=0; $i < 2000; $i++) {

        $tramite = Tramite::make();

        $tramite->id_servicio = 78;
        $tramite->cantidad = 1;
        $tramite->solicitante = 'Usuario';
        $tramite->nombre_solicitante = 'Vivienda para el bienestar';
        $tramite->monto = 331;
        $tramite->seccion = 'Propiedad';
        $tramite->tipo_servicio = 'ordinario';
        $tramite->distrito = 1;
        $tramite->tipo_tramite = 'normal';
        $tramite->estado = 'nuevo';
        $tramite->año = '2026';
        $tramite->usuario = 2;
        $tramite->fecha_entrega = '2026-02-16';
        $tramite->numero_control = (Tramite::where('año', '2026')->where('usuario', 2)->max('numero_control') ?? 0) + 1;
        $tramite->creado_por = 1;

        $array = (new LineaCapturaApi($tramite))->generarLineaDeCaptura();

        $tramite->orden_de_pago = $array['ES_OPAG']['NRO_ORD_PAGO'];
        $tramite->linea_de_captura = $array['ES_OPAG']['LINEA_CAPTURA'];
        $tramite->limite_de_pago = Str::substr($array['ES_OPAG']['FECHA_VENCIMIENTO'], 0, 4) . '-' . Str::substr($array['ES_OPAG']['FECHA_VENCIMIENTO'], 4, 2) . '-' . Str::substr($array['ES_OPAG']['FECHA_VENCIMIENTO'], 6, 2);

        $tramite->save();

        (new TramitesController())->ordenS3($tramite);

        $progressBar->advance(1);

    }

    $progressBar->finish();

    $this->info('Tramites generados!');

});
