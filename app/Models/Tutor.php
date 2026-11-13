<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('tutor', 'id_tutor')]
#[Fillable('nm_tutor', 'nm_endereco_tutor', 'nm_email_tutor', 'cd_telefone_tutor', 'cd_cpf_tutor', 'ic_ativo_tutor')]
class Tutor extends Model
{
    protected function casts(): array
    {
        return [
            'ic_ativo_tutor' => 'boolean',
        ];
    }

    public function pets(): HasMany
    {
        return $this->hasMany(Pet::class, 'id_tutor', 'id_tutor');
    }
}
