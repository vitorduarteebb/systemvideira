<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposta extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo_proposta',
        'cliente_id',
        'responsavel_id',
        'valor_final',
        'estado',
        'titulo',
        'descricao_inicial',
        'configuracoes_tecnicas',
        'data_criacao',
        'data_fechamento',
        'motivo_ganho',
        'motivo_perda',
        'motivo_negociacao',
    ];

    protected $casts = [
        'valor_final' => 'decimal:2',
        'data_criacao' => 'date',
        'data_fechamento' => 'date',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function responsavel()
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }

    public function acompanhamentos()
    {
        return $this->hasMany(PropostaAcompanhamento::class)->orderBy('created_at', 'desc');
    }

    public static function gerarCodigo()
    {
        try {
            $ultimo = self::orderBy('id', 'desc')->first();
            
            if ($ultimo && $ultimo->codigo_proposta) {
                // Extrair número do código existente (ex: PROP-000001 -> 1)
                $numeroStr = substr($ultimo->codigo_proposta, 5); // Remove "PROP-"
                $numero = (int) $numeroStr;
                $numero++;
            } else {
                $numero = 1;
            }
            
            return 'PROP-' . str_pad($numero, 6, '0', STR_PAD_LEFT);
        } catch (\Exception $e) {
            // Em caso de erro, gerar código baseado em timestamp
            return 'PROP-' . date('Ymd') . '-' . rand(1000, 9999);
        }
    }
}
