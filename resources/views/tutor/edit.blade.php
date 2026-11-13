@extends('layouts.app')
@section('titulo', 'Editar cliente')

@section('conteudo')
<div class="page-head">
  <div>
    <h1>Editar cliente</h1>
    <p class="sub">Atualize os dados de {{ $tutor->nm_tutor }}.</p>
  </div>
  <a class="btn btn-secundario" href="{{ route('tutores.show', $tutor) }}">Voltar</a>
</div>

@include('tutor._form')
@endsection
