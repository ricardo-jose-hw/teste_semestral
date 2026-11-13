<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClinicaController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExameController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\TutorController;
use Illuminate\Support\Facades\Route;

Route::pattern('perfil', 'funcionario|dono');

Route::get('/', fn () => redirect()->route(auth()->check() ? 'dashboard' : 'login'))->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.store');

    Route::get('/cadastro/{perfil}', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/cadastro/{perfil}', [AuthController::class, 'register'])->middleware('throttle:10,1')->name('register.store');

    Route::view('/recuperar-senha', 'auth.recuperar-senha')->name('password.request');
    Route::post('/recuperar-senha', [PasswordResetController::class, 'enviarLink'])->middleware('throttle:5,1')->name('password.email');
    Route::get('/redefinir-senha/{token}', [PasswordResetController::class, 'formulario'])->name('password.reset');
    Route::post('/redefinir-senha', [PasswordResetController::class, 'atualizar'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dono/dashboard', [DashboardController::class, 'dono'])->middleware('perfil:dono')->name('dashboard.dono');
    Route::get('/funcionario/dashboard', [DashboardController::class, 'funcionario'])->middleware('perfil:funcionario')->name('dashboard.funcionario');
    Route::post('/clinicas', [ClinicaController::class, 'store'])->middleware('perfil:dono')->name('clinicas.store');

    Route::resource('funcionarios', FuncionarioController::class)->middleware('perfil:dono');
    Route::patch('funcionarios/{funcionario}/status', [FuncionarioController::class, 'status'])
        ->middleware('perfil:dono')
        ->name('funcionarios.status');

    Route::resource('tutores', TutorController::class)->parameters(['tutores' => 'tutor']);

    Route::get('tutores/{tutor}/pets', [PetController::class, 'doTutor'])->name('tutores.pets.index');

    Route::get('pets', [PetController::class, 'index'])->name('pets.index');
    Route::get('pets/create', [PetController::class, 'create'])->name('pets.create');
    Route::post('pets', [PetController::class, 'store'])->name('pets.store');
    Route::get('pets/{pet}', [PetController::class, 'show'])->name('pets.show');
    Route::get('pets/{pet}/edit', [PetController::class, 'edit'])->name('pets.edit');
    Route::put('pets/{pet}', [PetController::class, 'update'])->name('pets.update');
    Route::patch('pets/{pet}/status', [PetController::class, 'status'])->name('pets.status');
    Route::delete('pets/{pet}', [PetController::class, 'destroy'])->name('pets.destroy');

    Route::get('consultas', [ConsultaController::class, 'index'])->name('consultas.index');
    Route::get('consultas/create', [ConsultaController::class, 'create'])->name('consultas.create');
    Route::post('consultas', [ConsultaController::class, 'store'])->name('consultas.store');
    Route::get('consultas/{consulta}/edit', [ConsultaController::class, 'edit'])->name('consultas.edit');
    Route::put('consultas/{consulta}', [ConsultaController::class, 'update'])->name('consultas.update');
    Route::patch('consultas/{consulta}/status', [ConsultaController::class, 'status'])->name('consultas.status');

    Route::get('exames', [ExameController::class, 'index'])->name('exames.index');
    Route::get('exames/create', [ExameController::class, 'create'])->name('exames.create');
    Route::post('exames', [ExameController::class, 'store'])->name('exames.store');
});
