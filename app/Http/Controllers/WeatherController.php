<?php

namespace App\Http\Controllers;

use App\Http\Requests\WeatherRequest;
use App\Services\WeatherService;

class WeatherController extends Controller
{
    protected WeatherService $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    /**
     * Exibe o formulário de busca de cidade.
     */
    public function index()
    {
        return view('weather.index');
    }

    /**
     * Recebe a busca (POST), chama o WeatherService e exibe o resultado.
     *
     * @param WeatherRequest $request
     */
    public function search(WeatherRequest $request)
    {
        // Agora lemos os campos ocultos definidos pelo autocomplete
        $cidade      = $request->input('cidade_selected');
        $estado      = $request->input('uf_selected');
        $consulta    = "{$cidade}, {$estado}, Brazil";

        try {
            $dadosClima = $this->weatherService->getWeatherByCity($consulta);

            if (is_null($dadosClima)) {
                return redirect()
                    ->route('weather.index')
                    ->with('error', "Não foi possível encontrar dados para '{$cidade}'.");
            }

            return view('weather.results', [
                'dados' => $dadosClima,
            ]);
        }
        catch (\Exception $e) {
            logger()->error("Erro ao obter clima: " . $e->getMessage());
            return redirect()
                ->route('weather.index')
                ->with('error', 'Ocorreu um erro ao consultar o serviço de clima. Tente novamente mais tarde.');
        }
    }
}
