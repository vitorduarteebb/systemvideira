<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTecnico(): bool
    {
        return $this->role === 'tecnico';
    }

    public function colaboradorVinculado(): ?Colaborador
    {
        return Colaborador::resolveFromUser($this);
    }

    /** Colaborador vinculado por `colaboradores.user_id` (um usuário ↔ no máximo um colaborador). */
    public function colaboradorConta(): HasOne
    {
        return $this->hasOne(Colaborador::class, 'user_id');
    }

}
