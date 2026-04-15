<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContaReceber extends Model
{
    use HasFactory;

    protected $table = 'contas_receber';

    protected $fillable = [
        'cliente_id',
        'cliente_nome',
        'descricao',
        'categoria',
        'numero_documento',
        'grupo_duplicata',
        'parcela',
        'total_parcelas',
        'valor_total',
        'valor_recebido',
        'data_emissao',
        'data_vencimento',
        'data_recebimento',
        'status',
        'observacoes',
    ];

    protected $casts = [
        'valor_total' => 'decimal:2',
        'valor_recebido' => 'decimal:2',
        'data_emissao' => 'date',
        'data_vencimento' => 'date',
        'data_recebimento' => 'date',
    ];

    protected $appends = [
        'saldo',
        'esta_vencida',
        'dias_atraso',
        'inadimplente',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function getSaldoAttribute(): float
    {
        return (float) max(0, (float) $this->valor_total - (float) $this->valor_recebido);
    }

    public function getEstaVencidaAttribute(): bool
    {
        return $this->saldo > 0 && $this->data_vencimento && $this->data_vencimento->isPast();
    }

    public function getInadimplenteAttribute(): bool
    {
        return $this->esta_vencida;
    }

    public function getDiasAtrasoAttribute(): int
    {
        if (! $this->esta_vencida) {
            return 0;
        }

        return (int) $this->data_vencimento->diffInDays(now());
    }
}
