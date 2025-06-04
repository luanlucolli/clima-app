<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    // Nome da tabela
    protected $table = 'municipios';

    // Sem timestamps automáticos
    public $timestamps = false;

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'ibge_id',
        'nome',
        'uf_sigla',
        'uf_nome',
    ];
}
