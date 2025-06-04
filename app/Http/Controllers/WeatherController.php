<?php

namespace App\Http\Controllers;

use App\Http\Requests\WeatherRequest;
use App\Services\WeatherService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    private WeatherService $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        // injeta weatherService via dependência
        $this->weatherService = $weatherService;
    }

    /**
     * formulário inicial para buscar o clima.
     */
    public function index()
    {
        return view('weather.index');
    }

    /**
     * Recebe o formulário, valida e exibe o resultado ou erro.
     *
     * @param WeatherRequest $request
     */
    public function search(WeatherRequest $request)
    {
        $cidade = $request->input('cidade');

        try {
            $dados = $this->weatherService->getWeatherByCity($cidade);

            if (is_null($dados)) {
                // cidade não encontrada (API retornou 404)
                return redirect()
                    ->route('weather.index')
                    ->with('error', "Cidade '{$cidade}' não encontrada ou sem dados.");
            }

            return view('weather.results', [
                'dados' => $dados,
            ]);
        } catch (\Exception $e) {
            
            logger()->error('Erro ao buscar clima: ' . $e->getMessage());

            return redirect()
                ->route('weather.index')
                ->with('error', 'Ocorreu um erro ao consultar o serviço de clima. Tente novamente mais tarde.');
        }
    }
}
