# Clima App - Setup e Informações Técnicas

Fala dev,

Esse projeto foi feito com foco em clareza, simplicidade e boa prática — especialmente pra facilitar a vida de quem for rodar e testar a aplicação. Aqui vão as instruções de uso e o que acontece por trás do `setup.php`, pra ninguém perder tempo e já sair testando.

---

## Pré-requisitos

Antes de começar, você precisa ter instalado:

- **PHP ≥ 8.1**  
  - Com extensões básicas habilitadas (pdo, pdo_sqlite, mbstring, json, ctype, tokenizer).  
- **Composer**  
  - Para instalar dependências Laravel.  
- **Node.js (≥ 14) e npm**  
  - Para instalar e compilar os assets via Vite.  
- **SQLite** (opcional)  
  - Usei SQLite por padrão para simplificar o banco; o arquivo `database/database.sqlite` será criado automaticamente.  
  


## Como rodar tudo em poucos passos

1. **Clone o projeto**  
2. **Rode o script de setup**  
3. **Adicione a chave da API no arquivo `.env`**  
4. **Suba o servidor**

---

## 1. Rodando o setup

```bash
php setup.php
```

Esse script faz o seguinte:

- Roda `composer install`  
- Copia `.env.example` para `.env` (se ainda não existir)  
- Tenta pegar a chave `WEATHER_API_KEY` da variável de ambiente e injetar no `.env`  
- Gera a chave da aplicação com `php artisan key:generate`  
- Roda as migrations com `php artisan migrate`  
- Importa a lista de municípios com o comando `php artisan importar:municipios`  
- Instala os pacotes do frontend (`npm install`)  
- Compila os assets (`npm run build`)  

No final, ele te avisa que é só rodar o `php artisan serve`.

---

## 2. Configurando a chave da API

Esse passo é **obrigatório**, senão o app não vai conseguir buscar os dados do clima e vai quebrar.

No arquivo `.env`, edite a linha abaixo:

```env
WEATHER_API_KEY=
```

Coloque sua chave do [WeatherAPI](https://www.weatherapi.com/) ali:

```env
WEATHER_API_KEY=92baea1ee3554a0abbc55358250406
```

---

## 3. Subindo o servidor

Simples:

```bash
php artisan serve
```

Depois é só abrir o navegador em:

```
http://127.0.0.1:8000
```

---

## Como funciona o projeto

Aqui vai um resumo rápido de como tudo está organizado e funcionando:

- **Estrutura básica**:  
  - `app/Http/Controllers/WeatherController.php` lida com as requisições e passa a resposta para a view.  
  - `app/Services/WeatherService.php` centraliza a lógica de comunicação com a WeatherAPI e tratamento dos dados.  
  - `app/Console/Commands/ImportarMunicipios.php` faz a importação dos municípios do IBGE para o banco.  
  - As migrations já criam a tabela `municipios` usada pelo autocomplete.

- **Autocomplete de municípios (IBGE)**:  
  - Quando o usuário digita no campo de busca, o JavaScript faz requisições a uma rota `/municipios?q=...`.  
  - O `MunicipioController` consulta a tabela `municipios` (importada do IBGE) e retorna até 10 resultados que batem com o termo digitado.  
  - A lista aparece em dropdown logo abaixo do input. Ao clicar em uma sugestão, o nome e o estado são preenchidos em campos escondidos para submeter a busca corretamente.

- **Dados de clima (WeatherAPI)**:  
  - Escolhi a **WeatherAPI.com** porque, dentre as APIs testadas, ela oferece cobertura confiável para cidades brasileiras, incluindo casos de nomes duplicados em estados diferentes.  
  - Algumas cidades muito pequenas (população < 5k) podem retornar inconsistências ou não aparecer, mas isso é raro e faz parte das limitações do provider de dados gratuitos.  
  - A requisição traz informações como temperatura, sensação térmica, umidade, vento, nuvens, ícone e descrição do clima em português.

- **Frontend e responsividade**:  
  - Usei **Bootstrap** para deixar a interface responsiva e simples de estilizar.  
  - Os assets (CSS e JS) são compilados pelo **Vite**, sem configurações extras.  
  - O template principal está em `resources/views/layouts/app.blade.php` e a view de busca/resultados em `resources/views/weather/index.blade.php`.

- **Banco de dados**:  
  - Por padrão, Usei **SQLite** para simplificar a configuração. Basta ter o arquivo `database/database.sqlite`.  
  - O comando `php artisan migrate` cria as tabelas, incluindo `municipios`.  
  - O import do IBGE (`php artisan importar:municipios`) preenche a tabela `municipios` uma única vez; depois, o autocomplete roda só selects simples.

---

## Considerações finais

A intenção aqui foi manter tudo bem enxuto e prático, mas sem sacrificar boas práticas de organização de código, separação de responsabilidades e segurança. Se achar qualquer coisa estranha ou quiser sugerir melhorias, é só mandar um toque.

Bom teste!

—
