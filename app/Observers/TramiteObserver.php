<?php

namespace App\Observers;

use App\Models\Tramite;

class TramiteObserver
{

    public function creating(Tramite $tramite){

        $tramite->año = now()->format('Y');

        if(auth()->check()){

            $tramite->observaciones = 'Calificó: ' . auth()->user()->name . ', Área: ' . auth()->user()->area . $tramite->observaciones;

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
