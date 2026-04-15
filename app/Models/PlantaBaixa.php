<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class PlantaBaixa extends Model
{
    use HasFactory;

    protected $table = 'plantas_baixas';

    protected $fillable = [
        'cliente_id',
        'nome',
        'descricao',
        'imagem_path',
        'ativa',
    ];

    protected $casts = [
        'ativa' => 'boolean',
    ];

    protected $appends = [
        'imagem_url',
    ];

    public function getImagemUrlAttribute(): ?string
    {
        if (! $this->imagem_path || ! $this->getKey()) {
            return null;
        }

        return route('crm.plantas.imagem', ['planta' => $this->getKey()]);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function marcadores()
    {
        return $this->hasMany(PlantaEquipamentoMarcador::class, 'planta_baixa_id');
    }
}
