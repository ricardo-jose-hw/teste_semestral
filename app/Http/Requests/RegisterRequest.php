<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => Str::lower(trim((string) $this->input('email')))
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $perfil = $this->route('perfil');

        $rules = [
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email'
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'max:72',
                'confirmed'
            ],
        ];

        if ($perfil === 'funcionario') {
            $rules += [
                'crmv' => ['required', 'string', 'max:255'],

                'cd_cpf_funcionario' => [
                    'required',
                    'digits:11',
                    'unique:funcionario,cd_cpf_funcionario'
                ],

                'cd_telefone_funcionario' => [
                    'required',
                    'digits:11'
                ],

                'nm_especialidade_funcionario' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'clinica_id' => [
                    'nullable',
                    'integer',
                    'exists:clinicas,id'
                ],
            ];

            $rules['email'][] = 'unique:funcionario,nm_email_funcionario';

        } else {
            $rules['nome_clinica'] = [
                'required',
                'string',
                'max:255'
            ];
        }

        return $rules;
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'unique' => 'Este :attribute já está cadastrado.',
            'confirmed' => 'A confirmação da senha não confere.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'digits' => 'O campo :attribute deve ter :digits dígitos.',
        ];
    }
}