@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3 text-center">
                    Clima em {{ $dados['cidade'] }}, {{ $dados['pais'] }}
                </h5>

                <div class="row align-items-center">
                    {{-- Coluna do nome da cidade --}}
                    {{-- Coluna do ícone e descrição --}}
                    <div class="col-sm-4 text-center mb-3 mb-sm-0 justify-content-center d-flex flex-column align-items-center">
                        <img
                            src="https://openweathermap.org/img/wn/{{ $dados['icone'] }}@2x.png"
                            alt="{{ $dados['descricao'] }}"
                            class="mb-2">
                        <p class="text-capitalize fw-semibold">{{ $dados['descricao'] }}</p>
                    </div>

                    {{-- Coluna dos dados --}}
                    <div class="col-sm-8">
                        <ul class="list-unstyled mb-0">
                            <li><strong>Temperatura:</strong> {{ $dados['temperatura'] }} °C</li>
                            <li><strong>Sensação térmica:</strong> {{ $dados['sensacao'] }} °C</li>
                            <li><strong>Temperatura mínima:</strong> {{ $dados['temp_min'] }} °C</li>
                            <li><strong>Temperatura máxima:</strong> {{ $dados['temp_max'] }} °C</li>
                            <li><strong>Umidade:</strong> {{ $dados['umidade'] }} %</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('weather.index') }}" class="btn btn-secondary">
                Nova busca
            </a>
        </div>
    </div>
</div>
@endsection