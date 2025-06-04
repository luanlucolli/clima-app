<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WeatherRequest extends FormRequest
{
    public function authorize()
    {
        return true; // não há necessidade de autenticação aqui
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
            'cidade.required' => 'Informe o nome de uma cidade.',
            'cidade.string'   => 'Valor inválido para cidade.',
            'cidade.max'      => 'O nome da cidade é muito extenso.',
        ];
    }

    public function attributes()
    {
        return [
            'cidade' => 'cidade',
        ];
    }
}
