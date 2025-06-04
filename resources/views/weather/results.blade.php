@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <!-- Cabeçalho: cidade, estado e país -->
                <h5 class="card-title mb-3">
                    {{ $dados['name'] }}
                    @if($dados['region'])
                    , {{ $dados['region'] }}
                    @endif
                    @if($dados['country'])
                    - {{ $dados['country'] }}
                    @endif
                </h5>
                <!-- Exibição do horário local, se disponível -->
                @if($dados['localtime'])
                <p class="text-muted small mb-4">
                    Horário local: {{ $dados['localtime'] }}
                </p>
                @endif

                <div class="row align-items-center">
                    <!-- Coluna do ícone e descrição -->
                    <div class="col-sm-4 text-center d-flex flex-column align-items-center justify-content-center">
                        @if($dados['condition_icon'])
                        <img src="{{ $dados['condition_icon'] }}"
                            alt="{{ $dados['condition_text'] }}"
                            class="mb-2">
                        @endif
                        @if($dados['condition_text'])
                        <p class="text-capitalize">{{ $dados['condition_text'] }}</p>
                        @endif
                    </div>

                    <!-- Coluna com detalhes numéricos -->
                    <div class="col-sm-8">
                        <ul class="list-unstyled">
                            @if(!is_null($dados['temp_c']))
                            <li><strong>Temperatura:</strong> {{ $dados['temp_c'] }} °C</li>
                            @endif

                            @if(!is_null($dados['feelslike_c']))
                            <li><strong>Sensação térmica:</strong> {{ $dados['feelslike_c'] }} °C</li>
                            @endif

                            @if(!is_null($dados['humidity']))
                            <li><strong>Umidade:</strong> {{ $dados['humidity'] }} %</li>
                            @endif

                            @if(!is_null($dados['wind_kph']))
                            <li><strong>Vento:</strong> {{ $dados['wind_kph'] }} km/h</li>
                            @endif

                            @if(!is_null($dados['cloud']))
                            <li><strong>Nuvens:</strong> {{ $dados['cloud'] }} %</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('weather.index') }}" class="btn btn-secondary">Nova busca</a>
        </div>

    </div>
</div>
@endsection