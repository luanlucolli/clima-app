<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\RequestException;
// 1) Importar Str para remover acentos
use Illuminate\Support\Str;

class WeatherService
{
    /**
     * Busca o clima atual de uma cidade no WeatherAPI.com.
     *
     * @param string $city  Nome da cidade (ex: "São Paulo, SP" ou apenas "Curitiba")
     * @return array|null   Dados normalizados ou null se cidade não existir
     * @throws \Exception   Em caso de erro inesperado na API
     */
    public function getWeatherByCity(string $city): ?array
    {
        // 2) Remover múltiplos espaços e fazer trim
        $normalized = trim(preg_replace('/\s+/', ' ', $city));
        // 3) Remover acentos/diacríticos (belem → belem, pará → para, etc.)
        $cityParam = Str::ascii($normalized);

        // Chave de cache única por cidade (TTL: 10 minutos)
        $cacheKey = "weatherapi_city_" . strtolower(str_replace(' ', '_', $cityParam));

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($cityParam) {
            $baseUrl = config('services.weather.base_url');
            $apiKey  = config('services.weather.api_key');
            $lang    = config('services.weather.lang', 'pt');

            // Monta a URL completa: https://api.weatherapi.com/v1/current.json
            $endpoint = $baseUrl . '/current.json';

            try {
                $response = Http::timeout(5)
                    ->get($endpoint, [
                        'key' => $apiKey,
                        'q'   => $cityParam,  // já sem acentos, ex: “belem, para, Brazil”
                        'lang'=> $lang,
                    ]);

                // Se retornar erro HTTP (4xx ou 5xx), lança exceção
                $response->throw();
            }
            catch (RequestException $e) {
                $status = $e->response ? $e->response->status() : null;

                // 400/401/403/404 – devolve null para “cidade não encontrada”
                if ($status === 400 || $status === 401 || $status === 403 || $status === 404) {
                    return null;
                }
                throw new \Exception("Erro na API WeatherAPI.com: " . $e->getMessage());
            }

            $json = $response->json();

            if (!isset($json['current']) || !isset($json['location'])) {
                return null;
            }

            // Normaliza os campos para a view
            return [
                'name'           => $json['location']['name'] ?? $cityParam,
                'region'         => $json['location']['region'] ?? '',
                'country'        => $json['location']['country'] ?? '',
                'localtime'      => $json['location']['localtime'] ?? null,
                'temp_c'         => $json['current']['temp_c'] ?? null,
                'feelslike_c'    => $json['current']['feelslike_c'] ?? null,
                'humidity'       => $json['current']['humidity'] ?? null,
                'wind_kph'       => $json['current']['wind_kph'] ?? null,
                'cloud'          => $json['current']['cloud'] ?? null,
                'condition_text' => $json['current']['condition']['text'] ?? null,
                'condition_icon' => isset($json['current']['condition']['icon'])
                                    ? 'https:' . $json['current']['condition']['icon']
                                    : null,
            ];
        });
    }
}
