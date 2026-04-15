<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantaEquipamentoMarcador extends Model
{
    use HasFactory;

    protected $table = 'planta_equipamento_marcadores';

    protected $fillable = [
        'planta_baixa_id',
        'equipamento_id',
        'pos_x',
        'pos_y',
        'status',
        'realizado_em',
        'mes_ref',
    ];

    protected $casts = [
        'pos_x' => 'decimal:2',
        'pos_y' => 'decimal:2',
        'mes_ref' => 'date',
        'realizado_em' => 'datetime',
    ];

    public function plantaBaixa()
    {
        return $this->belongsTo(PlantaBaixa::class);
    }

    public function equipamento()
    {
        return $this->belongsTo(Equipamento::class);
    }

    public function getStatusLabelAttribute(): string
    {
        $s = $this->status;

        return match ($s) {
            'realizado' => 'Realizado',
            'pendente' => 'Pendente',
            'duplicado' => 'Duplicado',
            default => $s !== null && $s !== '' ? (string) $s : '—',
        };
    }

    /**
     * Marca como pendente (manutenção mensal vencida) quando passou 1 mês desde realizado_em.
     */
    /**
     * Data de referência do “verde”: realizado_em ou, em registros antigos, updated_at.
     */
    public function dataReferenciaRealizacao(): ?Carbon
    {
        if ($this->realizado_em instanceof Carbon) {
            return $this->realizado_em;
        }
        if ($this->status === 'realizado' && $this->updated_at instanceof Carbon) {
            return $this->updated_at;
        }

        return null;
    }

    public static function aplicarVencimentoManutencaoPorPlanta(int $plantaBaixaId): int
    {
        $n = 0;
        static::query()
            ->where('planta_baixa_id', $plantaBaixaId)
            ->where('status', 'realizado')
            ->orderBy('id')
            ->get()
            ->each(function (self $m) use (&$n) {
                $ref = $m->dataReferenciaRealizacao();
                if ($ref instanceof Carbon && $ref->copy()->addMonth()->lte(now())) {
                    $m->update(['status' => 'pendente', 'realizado_em' => null]);
                    $n++;
                }
            });

        return $n;
    }

    /**
     * Processa todas as plantas (ex.: agendamento diário).
     */
    public static function aplicarVencimentoManutencaoGlobal(): int
    {
        $n = 0;
        static::query()
            ->where('status', 'realizado')
            ->orderBy('id')
            ->chunkById(200, function ($items) use (&$n) {
                foreach ($items as $m) {
                    $ref = $m->dataReferenciaRealizacao();
                    if ($ref instanceof Carbon && $ref->copy()->addMonth()->lte(now())) {
                        $m->update(['status' => 'pendente', 'realizado_em' => null]);
                        $n++;
                    }
                }
            });

        return $n;
    }
}
