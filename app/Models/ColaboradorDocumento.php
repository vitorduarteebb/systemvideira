<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ColaboradorDocumento extends Model
{
    use HasFactory;

    protected $table = 'colaborador_documentos';

    protected $fillable = [
        'colaborador_id',
        'nome_documento',
        'data_vencimento',
        'cert_proximo_alerta_em',
        'cert_vencido_alerta_em',
        'arquivo_path',
        'arquivo_nome_original',
        'arquivo_mime',
        'arquivo_tamanho',
        'caminho_relativo',
        'observacoes',
        'ativo',
    ];

    protected $casts = [
        'data_vencimento' => 'date',
        'cert_proximo_alerta_em' => 'datetime',
        'cert_vencido_alerta_em' => 'datetime',
        'arquivo_tamanho' => 'integer',
        'ativo' => 'boolean',
    ];

    public function colaborador()
    {
        return $this->belongsTo(Colaborador::class);
    }

    /** sem_data | ok | expirando | vencido */
    public function estadoCertificacao(?int $diasAlerta = null): string
    {
        if (! $this->data_vencimento) {
            return 'sem_data';
        }

        $dias = $diasAlerta ?? (int) config('colaboradores.certificacao_dias_alerta', 30);
        $v = $this->data_vencimento instanceof Carbon
            ? $this->data_vencimento->copy()->startOfDay()
            : Carbon::parse($this->data_vencimento)->startOfDay();
        $today = Carbon::now()->startOfDay();

        if ($v->lt($today)) {
            return 'vencido';
        }

        if ($v->lte($today->copy()->addDays($dias))) {
            return 'expirando';
        }

        return 'ok';
    }

    /**
     * 0 = ok, 1 = expirando, 2 = vencido (prioridade máxima na lista).
     *
     * @param  Collection<int, ColaboradorDocumento>|array<int, ColaboradorDocumento>  $documentos
     */
    public static function nivelPiorCertificacao(Collection|array $documentos, ?int $diasAlerta = null): int
    {
        $dias = $diasAlerta ?? (int) config('colaboradores.certificacao_dias_alerta', 30);
        $worst = 0;
        foreach ($documentos as $doc) {
            if (! $doc instanceof self) {
                continue;
            }
            if (! $doc->data_vencimento) {
                continue;
            }
            $e = $doc->estadoCertificacao($dias);
            if ($e === 'vencido') {
                return 2;
            }
            if ($e === 'expirando' && $worst < 1) {
                $worst = 1;
            }
        }

        return $worst;
    }
}
