<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('climalogo.png') }}">
    <title>Clima Já</title>
 
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
            @yield('content')
        </div>
    </main>

  
    <div aria-live="polite" aria-atomic="true" class="position-relative">
        <div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3">
          
        </div>
    </div>

    <footer class="text-center py-3 text-muted">
        © {{ date('Y') }} Clima Já
    </footer>

   
    @stack('scripts')
</body>

</html>