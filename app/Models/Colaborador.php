<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Colaborador extends Model
{
    use HasFactory;

    protected $table = 'colaboradores';

    protected $fillable = [
        'user_id',
        'nome_profissional',
        'departamento',
        'valor_hora',
        'ativo',
        'cpf',
        'telefone',
        'email',
        'observacoes',
    ];

    protected $casts = [
        'valor_hora' => 'decimal:2',
        'ativo' => 'boolean',
    ];

    public function documentos()
    {
        return $this->hasMany(ColaboradorDocumento::class);
    }

    public function pastas()
    {
        return $this->hasMany(ColaboradorPasta::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function resolveFromUser(?User $user): ?self
    {
        if (! $user) {
            return null;
        }
        $porId = static::query()->where('user_id', $user->id)->where('ativo', true)->first();
        if ($porId) {
            return $porId;
        }
        if ($user->email) {
            return static::query()
                ->where('ativo', true)
                ->whereRaw('LOWER(TRIM(email)) = ?', [mb_strtolower(trim($user->email))])
                ->first();
        }

        return null;
    }

    public function getDepartamentoLabelAttribute()
    {
        $departamentos = [
            'operacional' => 'Operacional',
            'comercial' => 'Comercial',
            'administrativo' => 'Administrativo',
            'tecnico' => 'Técnico',
            'outro' => 'Outro',
        ];

        return $departamentos[$this->departamento] ?? $this->departamento;
    }
}
