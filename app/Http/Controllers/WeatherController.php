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
        $cidadeInput = $request->input('cidade');

        try {
            $dadosClima = $this->weatherService->getWeatherByCity($cidadeInput);

            if (is_null($dadosClima)) {
                // Se não retornar dados (por exemplo, cidade não encontrada), redireciona com erro
                return redirect()
                    ->route('weather.index')
                    ->with('error', "Não foi possível encontrar dados para '{$cidadeInput}'.");
            }

            // Passa o array de dados para a view results
            return view('weather.results', [
                'dados' => $dadosClima,
            ]);
        }
        catch (\Exception $e) {
            // Loga internamente
            logger()->error("Erro ao obter clima: " . $e->getMessage());
            return redirect()
                ->route('weather.index')
                ->with('error', 'Ocorreu um erro ao consultar o serviço de clima. Tente novamente mais tarde.');
        }
    }
}
