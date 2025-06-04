<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Clima Já</title>

    {{-- Injeta CSS e JS compilados pelo Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">
    {{-- Navbar simples --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('weather.index') }}">Clima Já</a>
        </div>
    </nav>

    {{-- Main content --}}
    <main class="py-4">
        <div class="container">
            {{-- Exibe mensagem de erro (flash) se existir --}}
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Conteúdo específico de cada página --}}
            @yield('content')
        </div>
    </main>

    {{-- Footer simples --}}
    <footer class="text-center text-muted py-3">
       
    </footer>
</body>
</html>
