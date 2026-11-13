@extends('layouts.app')
@section('titulo', 'Novo pet')

@section('conteudo')
<div class="page-head">
  <div>
    <h1>Novo pet</h1>
    <p class="sub">Todo pet pertence a um cliente.</p>
  </div>
  <a class="btn btn-secundario" href="{{ route('pets.index') }}">Voltar</a>
</div>

@include('pet._form')
@endsection
