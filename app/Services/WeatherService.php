<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\RequestException;

class WeatherService
{
    /**
     * Retorna dados meteorológicos de uma cidade brasileira.
     *
     * @param string $city
     * @return array|null
     * @throws \Exception em caso de erro não tratado pela API
     */
    public function getWeatherByCity(string $city): ?array
    {
        // remove espaços em excesso
        $city = trim($city);

        // chave única para cache, pra evitar chamadas repetidas em pouco tempo
        $cacheKey = 'weather_' . strtolower(str_replace(' ', '_', $city));

        // Tenta recuperar do cache (duração: 10 minutos)
        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($city) {
            $baseUrl = config('services.weather.base_url');
            $apiKey  = config('services.weather.api_key');
            $lang    = config('services.weather.lang', 'pt_br');
            $units   = config('services.weather.units', 'metric');

            try {
                $response = Http::timeout(5)
                    ->get($baseUrl . '/weather', [
                        'q'     => "{$city},BR",
                        'appid' => $apiKey,
                        'units' => $units,
                        'lang'  => $lang,
                    ]);

                $response->throw(); 
            } catch (RequestException $e) {
                // Se for 404 (cidade não encontrada), retorna null
                if ($e->response && $e->response->status() === 404) {
                    return null;
                }
               
                throw new \Exception('Erro ao acessar API de clima: ' . $e->getMessage());
            }

            $data = $response->json();

            // normaliza dados retornados
            return [
                'cidade'       => $data['name']      ?? $city,
                'pais'         => $data['sys']['country'] ?? 'BR',
                'temperatura'  => $data['main']['temp']       ?? null,
                'sensacao'     => $data['main']['feels_like'] ?? null,
                'temp_min'     => $data['main']['temp_min']   ?? null,
                'temp_max'     => $data['main']['temp_max']   ?? null,
                'umidade'      => $data['main']['humidity']   ?? null,
                'descricao'    => $data['weather'][0]['description'] ?? null,
                'icone'        => $data['weather'][0]['icon']        ?? null,
                'timestamp'    => $data['dt']            ?? null,
            ];
        });
    }
}
