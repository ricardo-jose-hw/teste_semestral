@extends('layouts.app')
@section('titulo', 'Adicionar exames')

@section('conteudo')
<div class="page-head">
  <div>
    <h1>Adicionar exames</h1>
    <p class="sub">Só consultas concluídas podem receber exames.</p>
  </div>
  <a class="btn btn-secundario" href="{{ $consulta ? route('consultas.index') : route('exames.index') }}">Voltar</a>
</div>

@if (! $consulta)
  <div class="card tabela-card">
    @if ($concluidas->isEmpty())
      <p class="vazio">Nenhuma consulta concluída ainda.</p>
    @else
      <ul class="lista-itens lista-links">
        @foreach ($concluidas as $item)
          <li>
            <time class="item-hora" datetime="{{ $item->dt_atendimento?->format('Y-m-d') }}">{{ $item->dt_atendimento?->format('d/m/Y') }}</time>
            <p class="item-info">
              <strong>{{ $item->pet?->nm_pet ?: 'Pet não informado' }}</strong>
              <small>{{ $item->ds_motivo ?: 'Sem motivo' }} · {{ $item->pet?->tutor?->nm_tutor ?: 'Cliente não informado' }}</small>
            </p>
            <a class="btn btn-secundario btn-sm" href="{{ route('exames.create', ['consulta_id' => $item->id_atendimento]) }}">Adicionar exames</a>
          </li>
        @endforeach
      </ul>
    @endif
  </div>
@elseif ($consulta->ic_status_atendimento !== 'Concluída')
  <div class="alerta alerta-erro" role="alert">Esta consulta está como “{{ $consulta->ic_status_atendimento }}”. Conclua a consulta antes de adicionar exames.</div>
@else
  <section class="card resumo" aria-label="Consulta">
    <p>
      <strong>{{ $consulta->pet?->nm_pet ?: 'Pet não informado' }}</strong>
      <small>{{ $consulta->pet?->tutor?->nm_tutor ?: 'Cliente não informado' }}</small>
    </p>
    <p>
      <strong>{{ $consulta->dt_atendimento?->format('d/m/Y') }} às {{ $consulta->hora_curta }}</strong>
      <small>{{ $consulta->ds_motivo ?: 'Sem motivo informado' }}</small>
    </p>
    <p>
      <strong>{{ $consulta->funcionario?->nm_funcionario ?: 'Profissional não informado' }}</strong>
      <small>{{ $consulta->clinica?->nome ?: 'Clínica não informada' }}</small>
    </p>
  </section>

  @if ($consulta->exames->isNotEmpty())
    <section class="card" aria-labelledby="ja-anexados">
      <h2 id="ja-anexados">Já anexados</h2>
      <ul class="chips">
        @foreach ($consulta->exames as $exame)
          <li>
            <a href="{{ $exame->urlArquivo() }}" download="{{ $exame->nm_arquivo_original }}"><x-icone nome="arquivo" />{{ $exame->nm_exame }}</a>
          </li>
        @endforeach
      </ul>
    </section>
  @endif

  <form class="card form" method="POST" action="{{ route('exames.store') }}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="consulta_id" value="{{ $consulta->id_atendimento }}">

    <h2>Novos exames</h2>
    <div id="exames-lista">@include('exame._linha')</div>
    <template id="exame-modelo">@include('exame._linha')</template>

    <button class="btn btn-secundario btn-sm" type="button" data-add-exame><x-icone nome="mais" />Adicionar outro exame</button>
    <x-datalist id="exames-sugeridos" :options="config('painel.exames_sugeridos')" />
    <p class="dica">Formatos aceitos: PDF e imagens. Tamanho máximo de 5 MB por arquivo.</p>

    <div class="form-acoes">
      <a class="btn btn-secundario" href="{{ route('consultas.index') }}">Cancelar</a>
      <button class="btn btn-primario" type="submit">Salvar exames</button>
    </div>
  </form>
@endif
@endsection
