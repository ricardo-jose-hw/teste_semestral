@extends('layouts.app')
@section('titulo', 'Página não encontrada')

@section('conteudo')
<div class="page-head">
  <div>
    <h1>Página não encontrada</h1>
    <p class="sub">O endereço acessado não existe neste painel.</p>
  </div>
  <a class="btn btn-primario" href="{{ route('dashboard') }}">Ir para o painel</a>
</div>
@endsection
