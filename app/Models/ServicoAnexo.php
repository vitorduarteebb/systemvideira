<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicoAnexo extends Model
{
    use HasFactory;

    protected $table = 'servico_anexos';

    protected $fillable = [
        'servico_id',
        'usuario_id',
        'tipo',
        'nome_original',
        'path',
    ];

    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
