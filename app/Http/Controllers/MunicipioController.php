<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Municipio;

class MunicipioController extends Controller
{
    /**
     * get /municipios?q=XXX
     * Retorna até 10 municípios cujo 'nome' contenha o termo,
     * ordenados alfabeticamente.
     */
    public function search(Request $request)
    {
        $q = trim($request->query('q', ''));

        if (strlen($q) < 2) {
            return response()->json([], 200);
        }

        $resultados = Municipio::where('nome', 'LIKE', "%{$q}%")
            ->orderBy('nome')
            ->limit(10)
            ->get(['ibge_id', 'nome', 'uf_sigla', 'uf_nome']);

        return response()->json($resultados);
    }
}
