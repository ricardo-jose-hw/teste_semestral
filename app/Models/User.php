<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'perfil'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function funcionario(): HasOne
    {
        return $this->hasOne(Funcionario::class, 'user_id', 'id');
    }

    public function clinicas(): HasMany
    {
        return $this->hasMany(Clinica::class, 'responsavel_id', 'id');
    }

    protected function iniciais(): Attribute
    {
        return Attribute::get(function (): string {
            $partes = preg_split('/\s+/', trim((string) $this->name)) ?: [];
            $primeira = mb_substr($partes[0] ?? '', 0, 1);
            $ultima = count($partes) > 1 ? mb_substr(end($partes), 0, 1) : '';

            return mb_strtoupper($primeira.$ultima);
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
