<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrearTramiteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tipo_tramite' => 'required',
            'tipo_servicio' => 'required',
            'servicio_id' => 'required',
            'solicitante' => 'required',
            'nombre_solicitante' => 'required',
            'monto' => 'required',
            'cantidad' => 'required',
            'usuario_tramites_linea_id' => 'required',
        ];
    }
}
