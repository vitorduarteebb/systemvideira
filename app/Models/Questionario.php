<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questionario extends Model
{
    use HasFactory;

    protected $table = 'questionarios';

    protected $fillable = [
        'titulo',
        'incluir_cabecalho',
        'incluir_rodape',
        'exibir_na_os_digital',
        'perguntas_mesma_linha',
        'exibir_pergunta_resposta_mesma_linha',
        'exibir_nao_respondidas_relatorio',
        'questionario_pmoc',
        'habilitar_resposta_equipamento',
    ];

    protected $casts = [
        'incluir_cabecalho' => 'boolean',
        'incluir_rodape' => 'boolean',
        'exibir_na_os_digital' => 'boolean',
        'exibir_pergunta_resposta_mesma_linha' => 'boolean',
        'exibir_nao_respondidas_relatorio' => 'boolean',
        'questionario_pmoc' => 'boolean',
        'habilitar_resposta_equipamento' => 'boolean',
    ];

    public function perguntas()
    {
        return $this->hasMany(QuestionarioPergunta::class)->orderBy('ordem');
    }
}
