<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login(array $credentials, Request $request): void
    {
        $email = Str::lower(trim($credentials['email']));

        $key = 'login:' . hash('sha256', $email . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages([
                'email' => 'Muitas tentativas. Aguarde um minuto e tente novamente.'
            ]);
        }

        if (!Auth::attempt([
            'email' => $email,
            'password' => $credentials['password'],
        ])) {
            RateLimiter::hit($key, 60);

            throw ValidationException::withMessages([
                'email' => 'E-mail ou senha inválidos.'
            ]);
        }

        RateLimiter::clear($key);

        $request->session()->regenerate();
    }

   
    public function register(array $data, string $perfil): User
    {
        return DB::transaction(function () use ($data, $perfil): User {

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'perfil' => $perfil
            ]);

            if ($perfil === 'funcionario') {

                $user->funcionario()->create([
                    'nm_funcionario' => $data['name'],
                    'nm_email_funcionario' => $data['email'],
                    'cd_cpf_funcionario' => $data['cd_cpf_funcionario'],
                    'cd_telefone_funcionario' => $data['cd_telefone_funcionario'],
                    'nm_especialidade_funcionario' => $data['nm_especialidade_funcionario'],
                    'cd_crmv_funcionario' => $data['crmv'],
                    'clinica_id' => $data['clinica_id'] ?? null
                ]);

            } else {

                $user->clinicas()->create([
                    'nome' => $data['nome_clinica']
                ]);

            }

            return $user;
        });
    }

 
    public function logout(Request $request): void
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}