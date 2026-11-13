@extends('layouts.app')
@section('titulo', 'Agendar consulta')

@section('conteudo')
<div class="page-head">
  <div>
    <h1>Agendar consulta</h1>
    <p class="sub">Escolha o cliente, o pet e o horário do atendimento.</p>
  </div>
  <a class="btn btn-secundario" href="{{ route('consultas.index') }}">Voltar</a>
</div>

@include('consulta._form')
@endsection
