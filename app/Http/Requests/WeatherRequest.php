<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WeatherRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'cidade' => 'required|string|max:100',
        ];
    }

    public function messages()
    {
        return [
            'cidade.required' => 'Você deve informar o nome de uma cidade.',
            'cidade.string'   => 'O nome da cidade precisa ser um texto válido.',
            'cidade.max'      => 'O nome da cidade não pode ter mais de 100 caracteres.',
        ];
    }

    public function attributes()
    {
        return [
            'cidade' => 'cidade',
        ];
    }
}
