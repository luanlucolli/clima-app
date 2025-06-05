#!/usr/bin/env php
<?php
/**
 * setup.php
 *
 * Script para popular o ambiente sem nunca hardcodar chaves sensíveis.
 * Fluxo:
 * 1) composer install
 * 2) copiar .env.example → .env (se não existir)
 * 3) tentar ler getenv('WEATHER_API_KEY'); se existir, injetar no .env
 * 4) gerar app key
 * 5) php artisan migrate
 * 6) php artisan importar:municipios
 * 7) npm install
 * 8) npm run dev
 */

function run($command)
{
    echo ">>> Executando: $command\n";
    passthru($command, $ret);
    if ($ret !== 0) {
        echo "\nErro ao executar: $command\n";
        exit($ret);
    }
}

// 1) composer install
run('composer install --no-interaction --prefer-dist');

// 2) Copiar .env.example para .env (se .env não existir)
if (!file_exists('.env')) {
    echo ">>> Copiando .env.example → .env\n";
    if (!copy('.env.example', '.env')) {
        echo "Erro: não foi possível copiar .env.example para .env\n";
        exit(1);
    }
} else {
    echo ">>> .env já existe, pulando cópia.\n";
}

// 3) Preencher WEATHER_API_KEY se estiver como variável de ambiente
$envContent = file_get_contents('.env');

// Verifique se a chave já está definida no .env (não faz nada se já tiver valor)
if (preg_match('/^WEATHER_API_KEY\s*=\s*(.*)$/m', $envContent, $matches)) {
    $current = trim($matches[1]);
    if ($current === '') {
        // Verifica se existe no ambiente do sistema
        $apiKey = getenv('WEATHER_API_KEY');
        if ($apiKey && trim($apiKey) !== '') {
            echo ">>> Inserindo WEATHER_API_KEY do environment no .env\n";
            // Substitui a linha WEATHER_API_KEY= por WEATHER_API_KEY=$apiKey
            $novaLinha = "WEATHER_API_KEY={$apiKey}";
            $envContent = preg_replace(
                '/^WEATHER_API_KEY\s*=\s*.*$/m',
                $novaLinha,
                $envContent
            );
            file_put_contents('.env', $envContent);
        } else {
            echo ">>> Atenção: WEATHER_API_KEY não definido no ambiente.\n";
            echo "    Você pode definir manualmente em .env ou exportar a variável e rodar setup novamente:\n";
            echo "    export WEATHER_API_KEY=suachaveaqui\n";
        }
    } else {
        echo ">>> WEATHER_API_KEY já definido no .env, pulando.\n";
    }
} else {
    // Caso não exista a linha `WEATHER_API_KEY=`, apenas adiciona no final
    $apiKey = getenv('WEATHER_API_KEY');
    $linha = "\n# Configurações do WeatherAPI\n";
    $linha .= "WEATHER_BASE_URL=https://api.weatherapi.com/v1\n";
    $linha .= "WEATHER_LANG=pt\n";
    if ($apiKey && trim($apiKey) !== '') {
        $linha .= "WEATHER_API_KEY={$apiKey}\n";
        echo ">>> Adicionando WEATHER_API_KEY do environment ao final de .env\n";
    } else {
        $linha .= "WEATHER_API_KEY=\n";
        echo ">>> Adicionando placeholder de WEATHER_API_KEY ao final de .env\n";
        echo "    Defina sua chave em .env ou exporte a variável WEATHER_API_KEY antes de rodar setup.\n";
    }
    file_put_contents('.env', $envContent . $linha);
}

// 4) Gerar chave de aplicação (se ainda não houver)
$envContent = file_get_contents('.env');
if (!preg_match('/^APP_KEY\s*=\s*.+$/m', $envContent)) {
    run('php artisan key:generate --ansi');
} else {
    echo ">>> APP_KEY já existente, pulando key:generate.\n";
}

// 5) php artisan migrate
run('php artisan migrate --force --ansi');

// 6) php artisan importar:municipios
run('php artisan importar:municipios --ansi');

// 7) npm install
run('npm install');

// 8) npm run dev
run('npm run build');

echo "\n>>> Setup concluído! Agora basta executar:\n";
echo "    php artisan serve\n";
echo "E abrir no navegador: http://127.0.0.1:8000\n\n";
