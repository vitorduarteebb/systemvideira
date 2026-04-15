<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicoHoraRegistro extends Model
{
    use HasFactory;

    protected $table = 'servico_hora_registros';

    protected $fillable = [
        'servico_id',
        'colaborador_id',
        'usuario_id',
        'monitoramento',
        'horario',
        'tempo_corrido_minutos',
        'motivo',
        'justificativa',
        'ajuste_manual',
    ];

    protected $casts = [
        'horario' => 'datetime',
        'ajuste_manual' => 'boolean',
    ];

    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }

    public function colaborador()
    {
        return $this->belongsTo(Colaborador::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function getMonitoramentoLabelAttribute(): string
    {
        return match ($this->monitoramento) {
            'check_in' => 'Check-In',
            'check_out' => 'Check-Out',
            'pausa' => 'Pausa',
            'retorno' => 'Retorno',
            default => 'Ajuste',
        };
    }
}
