@extends('layouts.app')
@section('titulo', 'Exames')

@section('conteudo')
<div class="page-head">
  <div>
    <h1>Exames</h1>
    <p class="sub">Exames anexados às consultas concluídas.</p>
  </div>
  <a class="btn btn-primario" href="{{ route('exames.create') }}"><x-icone nome="mais" />Adicionar exames</a>
</div>

<form class="card filtros" method="GET" action="{{ route('exames.index') }}" autocomplete="off">
  <x-campo-select name="pet_id" label="Pet" :options="$pets" :selected="$petId" placeholder="Todos os pets" classe="filtro-largo" />
  <div class="filtros-acoes">
    <button class="btn btn-primario" type="submit">Filtrar</button>
    <a class="btn btn-secundario" href="{{ route('exames.index') }}">Limpar</a>
  </div>
</form>

@if ($exames->isEmpty())
  <div class="card">
    <p class="vazio">Nenhum exame encontrado.</p>
  </div>
@else
  <div class="card tabela-card">
    <div class="tabela-wrap">
      <table>
        <thead>
          <tr>
            <th>Exame</th>
            <th>Pet</th>
            <th>Consulta</th>
            <th>Arquivo</th>
            <th>Observações</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($exames as $exame)
            @php($consulta = $exame->consulta)
            <tr>
              <td><strong>{{ $exame->nm_exame }}</strong></td>
              <td>
                @if ($consulta?->pet)
                  <a class="link" href="{{ route('pets.show', $consulta->pet) }}">{{ $consulta->pet->nm_pet }}</a>
                @else
                  —
                @endif
              </td>
              <td>
                {{ $consulta?->dt_atendimento?->format('d/m/Y') ?: '—' }}
                <small>{{ $consulta?->clinica?->nome ?: 'Clínica não informada' }}</small>
              </td>
              <td>
                <a class="link link-arquivo" href="{{ $exame->urlArquivo() }}" download="{{ $exame->nm_arquivo_original }}">
                  <x-icone nome="arquivo" />{{ $exame->nm_arquivo_original }}
                </a>
              </td>
              <td>{{ $exame->ds_observacao ?: '—' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endif
@endsection
