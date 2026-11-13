@extends('layouts.auth')
@section('titulo', 'Recuperar senha')

@section('conteudo')
<img class="auth-logo" src="{{ asset('assets/img/logo-marrom.png') }}" alt="Arizona Pet Care" width="150" height="54">
<h1>Recuperar senha</h1>
<p class="sub">Informe o e-mail da sua conta. Enviaremos um link para criar uma nova senha.</p>

@if (session('status'))
  <div class="alerta alerta-sucesso" role="status">{{ session('status') }}</div>
@endif

<form class="form" method="POST" action="{{ route('password.email') }}">
  @csrf
  <div class="campo">
    <label for="email">E-mail</label>
    <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username">
    @error('email')<small class="erro">{{ $message }}</small>@enderror
  </div>
  <button class="btn btn-primario btn-bloco" type="submit">Enviar link</button>
  <a class="link" href="{{ route('login') }}">Voltar ao login</a>
</form>
@endsection