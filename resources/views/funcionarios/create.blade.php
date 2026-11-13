@extends('layouts.app')
@section('titulo', 'Novo funcionário')

@section('conteudo')
<div class="page-head">
  <div>
    <h1>Novo funcionário</h1>
    <p class="sub">CPF e e-mail devem ser únicos. O e-mail será o login do funcionário.</p>
  </div>
  <a class="btn btn-secundario" href="{{ route('funcionarios.index') }}">Voltar</a>
</div>

@include('funcionarios._form')
@endsection
