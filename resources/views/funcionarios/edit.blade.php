@extends('layouts.app')
@section('titulo', 'Editar funcionário')

@section('conteudo')
<div class="page-head">
  <div>
    <h1>Editar funcionário</h1>
    <p class="sub">Atualize os dados de {{ $funcionario->nm_funcionario }}.</p>
  </div>
  <a class="btn btn-secundario" href="{{ route('funcionarios.index') }}">Voltar</a>
</div>

@include('funcionarios._form')
@endsection
