@extends('layouts.app')
@section('titulo', 'Reagendar consulta')

@section('conteudo')
<div class="page-head">
  <div>
    <h1>Reagendar consulta</h1>
    <p class="sub">Altere data, horário ou veterinário. Cliente e pet não mudam.</p>
  </div>
  <a class="btn btn-secundario" href="{{ route('consultas.index') }}">Voltar</a>
</div>

@include('consulta._form')
@endsection
