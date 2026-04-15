<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColaboradorPasta extends Model
{
    use HasFactory;

    protected $table = 'colaborador_pastas';

    protected $fillable = [
        'colaborador_id',
        'nome',
        'caminho_relativo',
    ];

    public function colaborador()
    {
        return $this->belongsTo(Colaborador::class);
    }
}

