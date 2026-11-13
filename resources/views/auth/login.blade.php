@extends('layouts.auth')
@section('titulo', 'Entrar')

@section('conteudo')
<img class="auth-logo" src="{{ asset('assets/img/logo-marrom.png') }}" alt="Arizona Pet Care" width="150" height="54">
<h1>Entrar</h1>
<p class="sub">Acesse o painel de gestão da clínica.</p>

<form class="form" method="POST" action="{{ route('login.store') }}">
  @csrf
  <x-campo name="email" label="E-mail" type="email" required autocomplete="username" />
  <x-campo name="password" label="Senha" type="password" required autocomplete="current-password" />
  <a class="link" href="{{ route('password.request') }}">Esqueci minha senha</a>
  <button class="btn btn-primario btn-bloco" type="submit">Entrar</button>
</form>
@endsection