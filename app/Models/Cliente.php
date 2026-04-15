<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'razao_social',
        'cnpj',
        'cpf',
        'email',
        'telefone',
        'empresa',
        'endereco_completo',
        'emails_responsaveis',
        'observacoes',
    ];

    protected $casts = [
        'emails_responsaveis' => 'array',
    ];

    public function propostas()
    {
        return $this->hasMany(Proposta::class);
    }

    public function plantasBaixas()
    {
        return $this->hasMany(PlantaBaixa::class);
    }

    public function equipamentos()
    {
        return $this->hasMany(Equipamento::class);
    }

    public function servicos()
    {
        return $this->hasMany(Servico::class);
    }
}
