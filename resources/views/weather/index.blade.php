@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">Buscar clima em cidade brasileira</h5>
                    <form action="{{ route('weather.search') }}" method="POST" id="form-clima">
                        @csrf

                        <!-- Campo de autocomplete visível -->
                        <div class="mb-3 position-relative">
                            <label for="cidade_autocomplete" class="form-label">Cidade</label>
                            <input type="text"
                                   id="cidade_autocomplete"
                                   class="form-control @error('cidade_selected') is-invalid @enderror"
                                   placeholder="Digite ao menos 2 letras..."
                                   autocomplete="off"
                                   required>
                            @error('cidade_selected')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                            <!-- Lista de sugestões aparecendo abaixo do input -->
                            <div id="autocomplete-list" class="list-group position-absolute w-100" style="z-index: 1050;"></div>
                        </div>

                        <!-- Campos ocultos que serão submetidos -->
                        <input type="hidden" id="cidade_selected" name="cidade_selected" value="{{ old('cidade_selected') }}">
                        <input type="hidden" id="uf_selected" name="uf_selected" value="{{ old('uf_selected') }}">

                        <button type="submit" class="btn btn-primary w-100">Buscar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
