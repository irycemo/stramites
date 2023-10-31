<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SapActualizarPagoRequest extends FormRequest
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
            'folio_de_pago' => 'required|string',
            'fecha_de_pago' => 'required|string',
            'linea_de_captura' => 'required|string',
        ];
    }
}
