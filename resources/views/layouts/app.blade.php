<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Clima Já</title>
    <!-- Inclui CSS/JS compilados pelo Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('weather.index') }}">Clima Já</a>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            <!-- Exibe mensagem de erro, se existir -->
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="text-center py-3 text-muted">
        © {{ date('Y') }} Clima Já
    </footer>
</body>
</html>
