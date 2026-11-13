@extends('layouts.app')
@section('titulo', 'Editar pet')

@section('conteudo')
<div class="page-head">
  <div>
    <h1>Editar pet</h1>
    <p class="sub">Atualize os dados de {{ $pet->nm_pet }}.</p>
  </div>
  <a class="btn btn-secundario" href="{{ route('pets.show', $pet) }}">Voltar</a>
</div>

@include('pet._form')
@endsection
