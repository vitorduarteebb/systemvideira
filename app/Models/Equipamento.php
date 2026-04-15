<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'nome',
        'tag',
        'tipo_unidade',
        'capacidade_btus',
        'ultima_manutencao',
        'localizacao',
        'observacoes_tecnicas',
        'ativo',
    ];

    protected $casts = [
        'ultima_manutencao' => 'date',
        'ativo' => 'boolean',
    ];

    protected $appends = [
        'tipo_unidade_label',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function servicos()
    {
        return $this->hasMany(Servico::class);
    }

    public function plantaMarcadores()
    {
        return $this->hasMany(PlantaEquipamentoMarcador::class, 'equipamento_id');
    }

    public function getTipoUnidadeLabelAttribute()
    {
        $tipos = [
            'condensadora' => 'Condensadora',
            'evaporadora' => 'Evaporadora',
            'split' => 'Split',
            'chiller' => 'Chiller',
            'ar_condicionado' => 'Ar Condicionado',
            'outro' => 'Outro',
        ];

        return $tipos[$this->tipo_unidade] ?? $this->tipo_unidade;
    }
}
