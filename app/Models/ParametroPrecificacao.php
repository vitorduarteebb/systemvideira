<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametroPrecificacao extends Model
{
    use HasFactory;

    protected $table = 'parametros_precificacao';

    protected $fillable = [
        'custo_mo_hora',
        'aliquota_impostos',
        'taxa_adm_fixa',
        'refeicao_diaria_pessoa',
        'pernoite_diaria_pessoa',
        'locacao_veiculo_diaria',
    ];

    protected $casts = [
        'custo_mo_hora' => 'decimal:2',
        'aliquota_impostos' => 'decimal:2',
        'taxa_adm_fixa' => 'decimal:2',
        'refeicao_diaria_pessoa' => 'decimal:2',
        'pernoite_diaria_pessoa' => 'decimal:2',
        'locacao_veiculo_diaria' => 'decimal:2',
    ];

    public static function getParametros()
    {
        return static::first() ?? static::create([
            'custo_mo_hora' => 55,
            'aliquota_impostos' => 12,
            'taxa_adm_fixa' => 2,
            'refeicao_diaria_pessoa' => 50,
            'pernoite_diaria_pessoa' => 175,
            'locacao_veiculo_diaria' => 100,
        ]);
    }
}
