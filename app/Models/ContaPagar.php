<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContaPagar extends Model
{
    use HasFactory;

    protected $table = 'contas_pagar';

    protected $fillable = [
        'fornecedor',
        'descricao',
        'categoria',
        'numero_documento',
        'grupo_duplicata',
        'parcela',
        'total_parcelas',
        'valor_total',
        'valor_pago',
        'data_emissao',
        'data_vencimento',
        'data_pagamento',
        'status',
        'observacoes',
    ];

    protected $casts = [
        'valor_total' => 'decimal:2',
        'valor_pago' => 'decimal:2',
        'data_emissao' => 'date',
        'data_vencimento' => 'date',
        'data_pagamento' => 'date',
    ];

    protected $appends = [
        'saldo',
        'esta_vencida',
        'dias_atraso',
    ];

    public function getSaldoAttribute(): float
    {
        return (float) max(0, (float) $this->valor_total - (float) $this->valor_pago);
    }

    public function getEstaVencidaAttribute(): bool
    {
        return $this->saldo > 0 && $this->data_vencimento && $this->data_vencimento->isPast();
    }

    public function getDiasAtrasoAttribute(): int
    {
        if (! $this->esta_vencida) {
            return 0;
        }

        return (int) $this->data_vencimento->diffInDays(now());
    }
}
