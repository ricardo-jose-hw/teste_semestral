@extends('layouts.app')
@section('titulo', 'Novo cliente')

@section('conteudo')
<div class="page-head">
  <div>
    <h1>Novo cliente</h1>
    <p class="sub">Cadastre o tutor responsável pelo pet.</p>
  </div>
  <a class="btn btn-secundario" href="{{ route('tutores.index') }}">Voltar</a>
</div>

@include('tutor._form')
@endsection
