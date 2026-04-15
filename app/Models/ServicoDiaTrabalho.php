<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicoDiaTrabalho extends Model
{
    use HasFactory;

    protected $table = 'servico_dias_trabalho';

    protected $fillable = [
        'servico_id',
        'dia_numero',
        'data',
        'hora_inicio',
        'hora_fim',
        'intervalo_minutos',
        'escalavel',
        'observacoes',
    ];

    protected $casts = [
        'data' => 'date',
        'hora_inicio' => 'string',
        'hora_fim' => 'string',
        'intervalo_minutos' => 'integer',
        'escalavel' => 'boolean',
    ];

    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }
}
