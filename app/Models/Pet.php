<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('pet', 'id_pet')]
#[Fillable('nm_pet', 'nm_especie_pet', 'nm_raca_pet', 'qt_peso_pet', 'id_tutor', 'ic_ativo_pet')]
class Pet extends Model
{
    protected function casts(): array
    {
        return [
            'qt_peso_pet' => 'decimal:2',
            'ic_ativo_pet' => 'boolean',
        ];
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(Tutor::class, 'id_tutor', 'id_tutor');
    }

    public function consultas(): HasMany
    {
        return $this->hasMany(Consulta::class, 'PET_id_pet', 'id_pet');
    }

    public function getPesoFormatadoAttribute(): string
    {
        if ($this->qt_peso_pet === null) {
            return '—';
        }

        return number_format((float) $this->qt_peso_pet, 2, ',', '').' kg';
    }
}
