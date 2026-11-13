@extends('layouts.app')
@section('titulo', 'Consultas')

@section('conteudo')
<div class="page-head">
  <div>
    <h1>Consultas</h1>
    <p class="sub">Agende, reagende e acompanhe o status de cada atendimento.</p>
  </div>
  <a class="btn btn-primario" href="{{ route('consultas.create') }}"><x-icone nome="mais" />Agendar consulta</a>
</div>

<form class="card filtros" method="GET" action="{{ route('consultas.index') }}" autocomplete="off">
  <x-campo name="data" label="Data" type="date" :value="$filtros['data'] ?? ''" />
  <x-campo name="cliente" label="Cliente" type="search" :value="$filtros['cliente'] ?? ''" placeholder="Nome do cliente" />
  <x-campo name="pet" label="Pet" type="search" :value="$filtros['pet'] ?? ''" placeholder="Nome do pet" />
  <x-campo-select name="status" label="Status" :options="config('painel.status_consulta')" :selected="$filtros['status'] ?? ''" placeholder="Todos" />
  <div class="filtros-acoes">
    <button class="btn btn-primario" type="submit">Filtrar</button>
    <a class="btn btn-secundario" href="{{ route('consultas.index') }}">Limpar</a>
  </div>
</form>

@if ($consultas->isEmpty())
  <div class="card">
    <p class="vazio">Nenhuma consulta encontrada com esses filtros.</p>
  </div>
@else
  <div class="card tabela-card">
    <div class="tabela-wrap">
      <table>
        <thead>
          <tr>
            <th>Data e hora</th>
            <th>Pet</th>
            <th>Cliente</th>
            <th>Veterinário</th>
            <th>Clínica</th>
            <th>Status</th>
            <th><span class="sr-only">Ações</span></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($consultas as $consulta)
            @php
              $pet = $consulta->pet;
              $aberta = $consulta->estaAberta();
              $classe = config('painel.classe_status')[$consulta->ic_status_atendimento] ?? 'neutro';
            @endphp
            <tr>
              <td>
                <strong>{{ $consulta->dt_atendimento?->format('d/m/Y') }}</strong>
                <small>{{ $consulta->hora_curta }}</small>
              </td>
              <td>{{ $pet?->nm_pet ?: '—' }}</td>
              <td>{{ $pet?->tutor?->nm_tutor ?: '—' }}</td>
              <td>{{ $consulta->funcionario?->nm_funcionario ?: '—' }}</td>
              <td>{{ $consulta->clinica?->nome ?: '—' }}</td>
              <td>
                <form method="POST" action="{{ route('consultas.status', $consulta) }}">
                  @csrf
                  @method('PATCH')
                  <select
                    class="pill-select pill-{{ $classe }}"
                    name="status"
                    aria-label="Status da consulta de {{ $pet?->nm_pet }}"
                    data-autosubmit
                  >
                    @foreach (config('painel.status_consulta') as $status)
                      <option value="{{ $status }}" @selected($consulta->ic_status_atendimento === $status)>{{ $status }}</option>
                    @endforeach
                  </select>
                </form>
              </td>
              <td class="acoes">
                @if ($aberta)
                  <x-link-icone :href="route('consultas.edit', $consulta)" icone="reagendar" :titulo="'Reagendar consulta de '.$pet?->nm_pet" />
                  <x-botao-acao
                    :action="route('consultas.status', $consulta)"
                    metodo="PATCH"
                    :campos="['status' => 'Cancelada']"
                    icone="cancelar"
                    :titulo="'Cancelar consulta de '.$pet?->nm_pet"
                    :confirmar="'Cancelar a consulta de '.$pet?->nm_pet.'?'"
                    :perigo="true"
                  />
                @elseif ($consulta->ic_status_atendimento === 'Concluída')
                  <x-link-icone :href="route('exames.create', ['consulta_id' => $consulta->id_atendimento])" icone="exames" :titulo="'Adicionar exames à consulta de '.$pet?->nm_pet" />
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endif
@endsection
