@extends('layouts.app')

@section('content')
    {{-- Formulário de busca (col-md-6) --}}
    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
            <form action="{{ route('weather.search') }}" method="POST" id="form-clima">
                @csrf

                {{-- Container com position-relative abrange input + botão --}}
                <div class="d-flex position-relative">
                  
                    <input
                        type="text"
                        id="cidade_autocomplete"
                        class="form-control flex-grow-1 @error('cidade_selected') is-invalid @enderror"
                        placeholder="Digite ao menos 2 letras para buscar a cidade..."
                        autocomplete="off"
                        style="border-radius: 0.375rem 0 0 0.375rem;"
                        required>
                    @error('cidade_selected')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror

                  
                    <button class="btn btn-primary" type="submit" id="btn-buscar" style="border-radius: 0 0.375rem 0.375rem 0;">
                        <i class="bi bi-search"></i> Buscar
                    </button>

                    {{-- Dropdown de sugestões ocupa 100% da largura do container d-flex --}}
                    <div
                        id="autocomplete-list"
                        class="list-group position-absolute w-100 shadow-sm"
                        style="top: 100%; left: 0; z-index: 1050; max-height: 240px; overflow-y: auto;"></div>
                </div>

               
                <input type="hidden" id="cidade_selected" name="cidade_selected" value="{{ old('cidade_selected') }}">
                <input type="hidden" id="uf_selected" name="uf_selected" value="{{ old('uf_selected') }}">
            </form>
        </div>
    </div>

    {{-- Card de resultado: só exibe se houver $dados --}}
    @isset($dados)
        <div class="row justify-content-center mb-4">
            <div class="col-md-6">
                <div class="card rounded-3 shadow-sm">
                    <div class="card-body">
                        {{-- Título: cidade, estado e país --}}
                        <h5 class="card-title text-center mb-1">
                            {{ $dados['name'] }}
                            @if($dados['region'])
                                , {{ $dados['region'] }}
                            @endif
                            @if($dados['country'])
                                - {{ $dados['country'] }}
                            @endif
                        </h5>

                        {{-- Horário local --}}
                        @if(!empty($dados['localtime']))
                            <p class="text-center text-muted small mb-4">
                                Horário local: {{ $dados['localtime'] }}
                            </p>
                        @endif

                        {{-- Linha superior do card: ícone/descrição à esquerda, temperatura à direita --}}
                        <div class="row align-items-center mb-4">
                            {{-- Ícone e descrição (col-6) --}}
                            <div class="col-6 text-center d-flex flex-column align-items-center justify-content-center">
                                @if(!empty($dados['condition_icon']))
                                    <img
                                        src="{{ $dados['condition_icon'] }}"
                                        alt="{{ $dados['condition_text'] }}"
                                        class="mb-2"
                                        style="width: 64px; height: 64px;">
                                @endif
                                @if(!empty($dados['condition_text']))
                                    <p class="text-capitalize mb-0">{{ $dados['condition_text'] }}</p>
                                @endif
                            </div>

                            {{-- Temperatura e sensação (col-6) --}}
                            <div class="col-6 text-center">
                                @if(isset($dados['temp_c']))
                                    <div class="h1 mb-1">
                                        {{ $dados['temp_c'] }}<sup>°C</sup>
                                    </div>
                                @endif
                                @if(isset($dados['feelslike_c']))
                                    <p class="small text-muted mb-0">
                                        Sensação térmica: {{ $dados['feelslike_c'] }} °C
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- Linha inferior do card: Nuvens, Umidade, Vento --}}
                        <div class="row text-center">
                            {{-- Nuvens --}}
                            <div class="col-4">
                                <p class="small text-muted mb-1">Nuvens</p>
                                @if(isset($dados['cloud']))
                                    <div class="h5 mb-0">{{ $dados['cloud'] }}<span class="small"> %</span></div>
                                @endif
                            </div>

                            {{-- Umidade --}}
                            <div class="col-4">
                                <p class="small text-muted mb-1">Umidade</p>
                                @if(isset($dados['humidity']))
                                    <div class="h5 mb-0">{{ $dados['humidity'] }}<span class="small"> %</span></div>
                                @endif
                            </div>

                            {{-- Vento --}}
                            <div class="col-4">
                                <p class="small text-muted mb-1">Vento</p>
                                @if(isset($dados['wind_kph']))
                                    <div class="h5 mb-0">{{ $dados['wind_kph'] }}<span class="small"> km/h</span></div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endisset
@endsection

{{-- Se há mensagem de sucesso na sessão, dispara um toast de sucesso --}}
@if(session('toast_success'))
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('success', '{{ session("toast_success") }}');
            });
        </script>
    @endpush
@endif

{{-- Se há mensagem de erro na sessão, dispara um toast de erro --}}
@if(session('toast_error'))
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('error', '{{ session("toast_error") }}');
            });
        </script>
    @endpush
@endif
