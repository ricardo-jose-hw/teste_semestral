@extends('layouts.app')
@section('titulo', 'Pets')

@section('conteudo')
<div class="page-head">
  <div>
    <h1>Pets</h1>
    <p class="sub">
      @if ($tutor)
        Pets de {{ $tutor->nm_tutor }}.
      @else
        Pets cadastrados por cliente, com acesso ao histórico médico.
      @endif
    </p>
  </div>
  <a class="btn btn-primario" href="{{ route('pets.create', isset($filtros['cliente_id']) && $filtros['cliente_id'] !== '' ? ['tutor_id' => $filtros['cliente_id']] : []) }}"><x-icone nome="mais" />Novo pet</a>
</div>

<form class="card filtros" method="GET" action="{{ route('pets.index') }}" autocomplete="off">
  <x-campo-select
    name="cliente_id"
    label="Cliente"
    :options="$clientes"
    :selected="$filtros['cliente_id'] ?? ''"
    placeholder="Todos os clientes"
    classe="filtro-largo"
  />
  <x-campo name="q" label="Buscar" type="search" :value="$filtros['q'] ?? ''" placeholder="Nome, espécie ou raça" classe="filtro-largo" />
  <div class="filtros-acoes">
    <button class="btn btn-primario" type="submit">Filtrar</button>
    <a class="btn btn-secundario" href="{{ route('pets.index') }}">Limpar</a>
  </div>
</form>

@if ($pets->isEmpty())
  <div class="card">
    <p class="vazio">Nenhum pet encontrado.</p>
  </div>
@else
  <div class="card tabela-card">
    <div class="tabela-wrap">
      <table>
        <thead>
          <tr>
            <th>Pet</th>
            <th>Espécie e raça</th>
            <th>Peso</th>
            <th>Cliente</th>
            <th>Status</th>
            <th><span class="sr-only">Ações</span></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($pets as $pet)
            <tr>
              <td><strong>{{ $pet->nm_pet }}</strong></td>
              <td>
                {{ $pet->nm_especie_pet }}
                <small>{{ $pet->nm_raca_pet ?: 'Raça não informada' }}</small>
              </td>
              <td>{{ $pet->peso_formatado }}</td>
              <td>
                @if ($pet->tutor)
                  <a class="link" href="{{ route('tutores.show', $pet->tutor) }}">{{ $pet->tutor->nm_tutor }}</a>
                @else
                  <small>Sem cliente</small>
                @endif
              </td>
              <td><x-pill-ativo :ativo="$pet->ic_ativo_pet" /></td>
              <td class="acoes">
                <x-link-icone :href="route('pets.show', $pet)" icone="historico" :titulo="'Histórico médico de '.$pet->nm_pet" />
                <x-link-icone :href="route('pets.edit', $pet)" icone="editar" :titulo="'Editar '.$pet->nm_pet" />
                <x-botao-status :action="route('pets.status', $pet)" :ativo="$pet->ic_ativo_pet" :nome="$pet->nm_pet" />
                <x-botao-acao
                  :action="route('pets.destroy', $pet)"
                  metodo="DELETE"
                  icone="lixo"
                  :titulo="'Excluir '.$pet->nm_pet"
                  :confirmar="'Excluir '.$pet->nm_pet.' definitivamente? Isso apaga todo o histórico do pet (consultas e exames). Não dá para desfazer.'"
                  :perigo="true"
                />
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endif
@endsection
