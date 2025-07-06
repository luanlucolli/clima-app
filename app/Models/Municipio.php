<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    // nome da tabela
    protected $table = 'municipios';

   
    public $timestamps = false;

    // campos 
    protected $fillable = [
        'ibge_id',
        'nome',
        'uf_sigla',
        'uf_nome',
    ];
}
