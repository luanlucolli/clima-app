@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">Buscar clima em cidade brasileira</h5>
                    <form action="{{ route('weather.search') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="cidade" class="form-label">Cidade</label>
                            <input
                                type="text"
                                name="cidade"
                                id="cidade"
                                value="{{ old('cidade') }}"
                                class="form-control @error('cidade') is-invalid @enderror"
                                placeholder="Ex: São Paulo"
                                required
                            >
                            @error('cidade')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Buscar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
