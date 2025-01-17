<?php

namespace App\Observers;

use App\Models\Tramite;

class TramiteObserver
{

    public function creating(Tramite $tramite){

        $tramite->año = now()->format('Y');

        $tramite->tomo = $tramite->tomo ? (int)$tramite->tomo : null;
        $tramite->registro = $tramite->registro ? (int)$tramite->registro : null;
        $tramite->numero_propiedad = $tramite->numero_propiedad ? (int)$tramite->numero_propiedad : null;
        $tramite->numero_propiedad = $tramite->numero_propiedad ? (int)$tramite->numero_propiedad : null;
        $tramite->tomo_gravamen = $tramite->tomo_gravamen ? (int)$tramite->tomo_gravamen : null;
        $tramite->registro_gravamen = $tramite->registro_gravamen ? (int)$tramite->registro_gravamen : null;

        if(auth()->check()){

            $tramite->observaciones = 'Calificó: ' . auth()->user()->name . ', Área: ' . auth()->user()->area . '. ' . $tramite->observaciones;

            $tramite->creado_por = auth()->user()->id;

        }

    }

    /**
     * Handle the Tramite "created" event.
     */
    public function created(Tramite $tramite): void
    {



    }

    public function updating(Tramite $tramite){

        if(auth()->check()){

            $tramite->actualizado_por = auth()->user()->id;

        }

    }

    public function updated(Tramite $tramite): void
    {
        //
    }

    /**
     * Handle the Tramite "deleted" event.
     */
    public function deleted(Tramite $tramite): void
    {
        //
    }

    /**
     * Handle the Tramite "restored" event.
     */
    public function restored(Tramite $tramite): void
    {
        //
    }

    /**
     * Handle the Tramite "force deleted" event.
     */
    public function forceDeleted(Tramite $tramite): void
    {
        //
    }
}
