<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Clinica;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function loginForm(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $this->authService->login($request->validated(), $request);

        return redirect()->route('dashboard');
    }

    public function registerForm(string $perfil): View
    {
        $clinicas = $perfil === 'funcionario'
            ? Clinica::orderBy('nome')->get()
            : collect();

        return view('auth.register', compact('perfil', 'clinicas'));
    }

    public function register(RegisterRequest $request, string $perfil): RedirectResponse
    {
        $user = $this->authService->register(
            $request->validated(),
            $perfil
        );

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        $this->authService->logout($request);

        return redirect()->route('home');
    }
    }