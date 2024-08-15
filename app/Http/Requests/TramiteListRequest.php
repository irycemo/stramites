<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TramiteListRequest extends FormRequest
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
            'entidad' => 'required',
            'año' => 'nullable',
            'estado' => 'nullable',
            'folio' => 'nullable',
            'servicio' => 'nullable',
            'pagina' => 'required',
            'pagination' => 'required'
        ];
    }
}
