@extends('layouts.app')
@section('titulo', 'Clientes')

@section('conteudo')
<div class="page-head">
  <div>
    <h1>Clientes</h1>
    <p class="sub">Tutores cadastrados, com acesso aos pets e ao histórico médico.</p>
  </div>
  <a class="btn btn-primario" href="{{ route('tutores.create') }}"><x-icone nome="mais" />Novo cliente</a>
</div>

<form id="filtro-tutores" class="card filtros" method="GET" action="{{ route('tutores.index') }}" autocomplete="off">
  <x-campo name="nome" label="Nome" type="search" :value="$filtros['nome'] ?? ''" placeholder="Nome do cliente" />
  <x-campo name="cpf" label="CPF" type="search" :value="$filtros['cpf'] ?? ''" placeholder="000.000.000-00" />
  <x-campo name="email" label="E-mail" type="search" :value="$filtros['email'] ?? ''" placeholder="E-mail do cliente" />
  <x-campo name="pet" label="Pet" type="search" :value="$filtros['pet'] ?? ''" placeholder="Nome do pet" />
  <div class="filtros-acoes">
    <button class="btn btn-primario" type="submit">Filtrar</button>
    <button class="btn btn-secundario" type="button" id="limpar-filtro">Limpar</button>
  </div>
</form>

<div id="lista-tutores">
  @include('tutor._lista')
</div>
@endsection
