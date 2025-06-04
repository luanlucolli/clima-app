<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Municipio;

class ImportarMunicipiosIBGE extends Command
{
    // Nome do comando
    protected $signature = 'importar:municipios';

    // Descrição curta
    protected $description = 'Importa lista de municípios do IBGE para a tabela local';

    public function handle()
    {
        $this->info("Iniciando importação de municípios do IBGE...");

        $url = 'https://servicodados.ibge.gov.br/api/v1/localidades/municipios?view=nivelado';

        try {
            $response = Http::timeout(30)->get($url);
            $response->throw();
        } catch (\Exception $e) {
            $this->error("Erro ao acessar API do IBGE: " . $e->getMessage());
            return 1;
        }

        $municipios = $response->json();

        if (!is_array($municipios)) {
            $this->error("Formato inesperado de resposta do IBGE.");
            return 1;
        }

        $bar = $this->output->createProgressBar(count($municipios));
        $bar->start();

        foreach ($municipios as $item) {
            // Cada $item contém chaves: "municipio-id", "municipio-nome", "UF-sigla", "UF-nome", entre outras
            $ibgeId = $item['municipio-id'];
            $nome   = $item['municipio-nome'];
            $ufSigla = $item['UF-sigla'];
            $ufNome = $item['UF-nome'];

            Municipio::updateOrCreate(
                ['ibge_id' => $ibgeId],
                [
                    'nome'     => $nome,
                    'uf_sigla' => $ufSigla,
                    'uf_nome'  => $ufNome,
                ]
            );

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Importação concluída com sucesso.");

        return 0;
    }
}
