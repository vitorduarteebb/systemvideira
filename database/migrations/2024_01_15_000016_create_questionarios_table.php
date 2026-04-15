<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questionarios', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->boolean('incluir_cabecalho')->default(false);
            $table->boolean('incluir_rodape')->default(false);
            $table->boolean('exibir_na_os_digital')->default(true);
            $table->unsignedTinyInteger('perguntas_mesma_linha')->default(1);
            $table->boolean('exibir_pergunta_resposta_mesma_linha')->default(false);
            $table->boolean('exibir_nao_respondidas_relatorio')->default(true);
            $table->boolean('questionario_pmoc')->default(false);
            $table->boolean('habilitar_resposta_equipamento')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questionarios');
    }
};
