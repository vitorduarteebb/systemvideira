<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicoQuestionario extends Model
{
    use HasFactory;

    protected $table = 'servico_questionarios';

    protected $fillable = [
        'servico_id',
        'questionario_id',
        'respostas',
        'usuario_id',
    ];

    protected $casts = [
        'respostas' => 'array',
    ];

    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }

    public function questionario()
    {
        return $this->belongsTo(Questionario::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
