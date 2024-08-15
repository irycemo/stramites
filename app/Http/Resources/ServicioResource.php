<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServicioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'clave_ingreso' => $this->clave_ingreso,
            'ordinario' => $this->ordinario,
            'urgente' => $this->urgente,
            'extra_urgente' => $this->extra_urgente
        ];
    }
}
