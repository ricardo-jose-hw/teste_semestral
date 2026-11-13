<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    public function enviarLink(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        Password::sendResetLink(['email' => Str::lower(trim($request->email))]);

        // Mesma resposta exista ou não o e-mail, para não revelar quem tem conta.
        return back()->with('status', 'Se o e-mail estiver cadastrado, enviaremos um link para criar uma nova senha.');
    }

    public function formulario(Request $request, string $token): View
    {
        return view('auth.redefinir-senha', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function atualizar(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'max:72', 'confirmed'],
        ], [
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação da senha não confere.',
        ]);

        $status = Password::reset(
            $dados,
            function ($user, string $senha) {
                $user->forceFill(['password' => $senha])
                    ->setRememberToken(Str::random(60));
                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => 'Link inválido ou expirado. Peça um novo link de recuperação.',
            ]);
        }

        return redirect()->route('login')->with('success', 'Senha redefinida. Entre com a nova senha.');
    }
}