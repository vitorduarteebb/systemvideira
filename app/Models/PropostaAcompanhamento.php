<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropostaAcompanhamento extends Model
{
    use HasFactory;

    protected $table = 'proposta_acompanhamentos';

    protected $fillable = [
        'proposta_id',
        'usuario_id',
        'descricao',
        'data_retorno',
        'data_evento',
        'tipo',
    ];

    protected $casts = [
        'data_retorno' => 'date',
        'data_evento' => 'date',
    ];

    public function proposta()
    {
        return $this->belongsTo(Proposta::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
