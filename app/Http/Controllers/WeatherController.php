<?php

namespace App\Http\Controllers;

use App\Http\Requests\WeatherRequest;
use App\Services\WeatherService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    protected WeatherService $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    /**
     * Rota GET '/'
     * - Se não houver parâmetros 'cidade' e 'estado' na query, exibe só o formulário.
     * - Se 'cidade' e 'estado' vierem na query, chama o WeatherService e exibe o resultado.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        // Verifica se há parâmetros cidade e estado na query string
        $cidadeParam = $request->query('cidade');
        $estadoParam = $request->query('estado');

        // Se não vierem, apenas exibe a página com o formulário
        if (!$cidadeParam || !$estadoParam) {
            return view('weather.index');
        }

        // Monta a string de busca para o WeatherAPI ("Cidade, Estado, Brazil")
        $consulta = "{$cidadeParam}, {$estadoParam}, Brazil";

        try {
            $dadosClima = $this->weatherService->getWeatherByCity($consulta);

            if (is_null($dadosClima)) {
                // Se não encontrou dados, redireciona sem query, com toast de erro
                return redirect()
                    ->route('weather.index')
                    ->with('toast_error', "Não foi possível encontrar dados para '{$cidadeParam}'.");
            }

            // Se obteve dados, exibe a mesma view, passando $dados,
            // e flash de sucesso (o toast será disparado no Blade).
            return view('weather.index', [
                'dados' => $dadosClima,
            ])->with('toast_success', "Clima de {$cidadeParam}, {$estadoParam} carregado com sucesso.");
        }
        catch (\Exception $e) {
            logger()->error("Erro ao obter clima: " . $e->getMessage());
            return redirect()
                ->route('weather.index')
                ->with('toast_error', 'Ocorreu um erro ao consultar o serviço de clima. Tente novamente mais tarde.');
        }
    }

    /**
     * Rota POST '/buscar'
     * - Recebe o POST do formulário (via WeatherRequest).
     * - Redireciona para GET '/', adicionando 'cidade' e 'estado' na query string.
     *
     * @param WeatherRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function search(WeatherRequest $request)
    {
        $cidade  = $request->input('cidade_selected');
        $estado  = $request->input('uf_selected');

        // Redireciona para a mesma rota index, agora como GET, passando os parâmetros
        return redirect()
            ->route('weather.index', [
                'cidade' => $cidade,
                'estado' => $estado,
            ]);
    }
}
