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
            'cidade_selected' => 'required|string|max:100',
            'uf_selected'     => 'required|string|max:100',
        ];
    }

    public function messages()
    {
        return [
            'cidade_selected.required' => 'Selecione uma cidade válida.',
            'cidade_selected.string'   => 'Valor inválido para cidade.',
            'cidade_selected.max'      => 'O nome da cidade é muito extenso.',

            'uf_selected.required'     => 'Estado não informado.',
            'uf_selected.string'       => 'Valor inválido para estado.',
            'uf_selected.max'          => 'O nome do estado é muito extenso.',
        ];
    }

    public function attributes()
    {
        return [
            'cidade_selected' => 'cidade',
            'uf_selected'     => 'estado',
        ];
    }
}
