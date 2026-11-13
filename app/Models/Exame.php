<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('exame', 'id_exame')]
#[Fillable('id_atendimento', 'nm_exame', 'nm_arquivo', 'nm_arquivo_original', 'ds_observacao')]
class Exame extends Model
{
    public function consulta(): BelongsTo
    {
        return $this->belongsTo(Consulta::class, 'id_atendimento', 'id_atendimento');
    }

    public function urlArquivo(): string
    {
        return asset('uploads/exames/'.$this->nm_arquivo);
    }
}
