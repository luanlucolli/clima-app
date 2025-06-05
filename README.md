# Clima App - Setup e Informações Técnicas

Fala dev,

Esse projeto foi feito com foco em clareza, simplicidade e boa prática — especialmente pra facilitar a vida de quem for rodar e testar a aplicação. Aqui vão as instruções de uso e o que acontece por trás do `setup.php`, pra ninguém perder tempo e já sair testando.

---

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

## Considerações finais

A ideia foi deixar tudo o mais plug and play possível, mas sem abrir mão de segurança: nenhuma chave sensível fica hardcoded. O `setup.php` tenta facilitar, mas se a chave da API não estiver no ambiente, é você que precisa inserir no `.env`.

Se algo quebrar, é só apagar o `.env` e rodar `setup.php` de novo.

Qualquer coisa, tô por aqui.

—