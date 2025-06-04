<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMunicipiosTable extends Migration
{
    public function up()
    {
        Schema::create('municipios', function (Blueprint $table) {
            $table->id(); // chave primária autoincrement
            $table->unsignedBigInteger('ibge_id')->unique(); // ID oficial do IBGE
            $table->string('nome', 150);
            $table->string('uf_sigla', 2);
            $table->string('uf_nome', 100);
            // índices para acelerar a busca por nome (LIKE)
            $table->index('nome');
            $table->index('uf_sigla');
        });
    }

    public function down()
    {
        Schema::dropIfExists('municipios');
    }
}
