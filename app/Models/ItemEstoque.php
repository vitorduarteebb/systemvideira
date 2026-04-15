<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemEstoque extends Model
{
    use HasFactory;

    protected $table = 'itens_estoque';

    protected $fillable = [
        'nome',
        'unidade',
        'quantidade_atual',
        'estoque_minimo',
        'fornecedor',
        'codigo',
        'ativo',
    ];

    protected $casts = [
        'quantidade_atual' => 'decimal:3',
        'estoque_minimo' => 'decimal:3',
        'ativo' => 'boolean',
    ];

    public function scopeEstoqueBaixo($query)
    {
        return $query->whereRaw('quantidade_atual <= estoque_minimo')
            ->where('ativo', true);
    }

    public function scopePorFornecedor($query, ?string $fornecedor)
    {
        if (empty($fornecedor)) {
            return $query;
        }
        return $query->where('fornecedor', 'like', '%' . $fornecedor . '%');
    }
}
