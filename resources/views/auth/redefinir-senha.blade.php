@extends('layouts.auth')
@section('titulo', 'Nova senha')

@section('conteudo')
<img class="auth-logo" src="{{ asset('assets/img/logo-marrom.png') }}" alt="Arizona Pet Care" width="150" height="54">
<h1>Nova senha</h1>
<p class="sub">Escolha uma senha com pelo menos 8 caracteres.</p>

<form class="form" method="POST" action="{{ route('password.update') }}">
  @csrf
  <input type="hidden" name="token" value="{{ $token }}">
  <input type="hidden" name="email" value="{{ old('email', $email) }}">
  @error('token')<div class="alerta alerta-erro" role="alert">{{ $message }}</div>@enderror
  @error('email')<div class="alerta alerta-erro" role="alert">{{ $message }}</div>@enderror

  <div class="campo">
    <label for="password">Nova senha</label>
    <input id="password" name="password" type="password" required minlength="8" autocomplete="new-password">
    @error('password')<small class="erro">{{ $message }}</small>@enderror
  </div>
  <div class="campo">
    <label for="password_confirmation">Confirmar nova senha</label>
    <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" autocomplete="new-password">
  </div>
  <button class="btn btn-primario btn-bloco" type="submit">Salvar senha</button>
  <a class="link" href="{{ route('login') }}">Voltar ao login</a>
</form>
@endsection