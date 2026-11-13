@extends('layouts.app')
@section('titulo', 'Acesso negado')

@section('conteudo')
<div class="page-head">
  <div>
    <h1>Acesso negado</h1>
    <p class="sub">Seu perfil não tem permissão para abrir esta tela.</p>
  </div>
  <a class="btn btn-primario" href="{{ route('dashboard') }}">Ir para o painel</a>
</div>
@endsection
