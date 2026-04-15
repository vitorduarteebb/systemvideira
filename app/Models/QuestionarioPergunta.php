<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionarioPergunta extends Model
{
    use HasFactory;

    protected $table = 'questionario_perguntas';

    protected $fillable = [
        'questionario_id',
        'ordem',
        'texto',
        'tipo_resposta',
        'opcoes',
        'resposta_obrigatoria',
        'descricao_pergunta',
    ];

    protected $casts = [
        'opcoes' => 'array',
        'resposta_obrigatoria' => 'boolean',
        'descricao_pergunta' => 'boolean',
    ];

    public function questionario()
    {
        return $this->belongsTo(Questionario::class);
    }
}
