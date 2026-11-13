<?php

namespace App\Http\Controllers\Concerns;

trait ValidaComMensagens
{
    protected function mensagensValidacao(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'string' => 'O campo :attribute deve ser um texto.',
            'max' => 'O campo :attribute não pode ter mais de :max caracteres.',
            'min' => 'O campo :attribute deve ter pelo menos :min caracteres.',
            'email' => 'Informe um e-mail válido.',
            'unique' => 'Este :attribute já está cadastrado.',
            'exists' => 'O :attribute selecionado não existe.',
            'integer' => 'O campo :attribute deve ser um número inteiro.',
            'numeric' => 'O campo :attribute deve ser numérico.',
            'confirmed' => 'A confirmação de :attribute não confere.',
            'in' => 'O :attribute selecionado é inválido.',
            'date' => 'Informe uma data válida.',
            'date_format' => 'Informe :attribute no formato correto.',
            'after_or_equal' => 'A :attribute deve ser hoje ou uma data futura.',
            'array' => 'O campo :attribute é inválido.',
            'file' => 'Envie um arquivo válido.',
            'mimes' => 'O arquivo deve ser PDF ou imagem.',
            'uploaded' => 'Falha no envio do arquivo.',
        ];
    }

    protected function atributosValidacao(): array
    {
        return [
            'nome' => 'nome',
            'email' => 'e-mail',
            'endereco' => 'endereço',
            'telefone' => 'telefone',
            'cpf' => 'CPF',
            'especie' => 'espécie',
            'raca' => 'raça',
            'peso' => 'peso',
            'perfil' => 'perfil',
            'senha' => 'senha',
            'senha_confirmation' => 'confirmação de senha',
            'especialidade' => 'especialidade',
            'crmv' => 'CRMV',
            'id_tutor' => 'cliente',
            'pet_id' => 'pet',
            'funcionario_id' => 'funcionário',
            'data' => 'data',
            'hora' => 'horário',
            'motivo' => 'motivo',
            'status' => 'status',
            'consulta_id' => 'consulta',
            'nome_exame' => 'exame',
            'obs_exame' => 'observação',
            'arquivo_exame' => 'arquivo',
        ];
    }
}
