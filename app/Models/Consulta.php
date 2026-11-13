<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('atendimento', 'id_atendimento')]
#[Fillable('PET_id_pet', 'FUNCIONARIO_id_funcionario', 'clinica_id', 'dt_atendimento', 'hr_atendimento', 'ds_motivo', 'ic_status_atendimento', 'nm_tipo_atendimento', 'vl_atendimento')]
class Consulta extends Model
{
    protected function casts(): array
    {
        return [
            'dt_atendimento' => 'date',
            'vl_atendimento' => 'decimal:2',
        ];
    }

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class, 'PET_id_pet', 'id_pet');
    }

    public function funcionario(): BelongsTo
    {
        return $this->belongsTo(Funcionario::class, 'FUNCIONARIO_id_funcionario', 'id_funcionario');
    }

    public function clinica(): BelongsTo
    {
        return $this->belongsTo(Clinica::class, 'clinica_id', 'id');
    }

    public function exames(): HasMany
    {
        return $this->hasMany(Exame::class, 'id_atendimento', 'id_atendimento');
    }

    public function estaAberta(): bool
    {
        return in_array($this->ic_status_atendimento, ['Pendente', 'Confirmada'], true);
    }

    public function getHoraCurtaAttribute(): string
    {
        return substr((string) $this->hr_atendimento, 0, 5);
    }
}
