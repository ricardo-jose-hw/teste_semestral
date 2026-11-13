@extends('layouts.app')
@section('titulo', 'Histórico de '.$pet->nm_pet)

@section('conteudo')
<div class="page-head">
  <div>
    <h1>Histórico de {{ $pet->nm_pet }}</h1>
    <p class="sub">Consultas e exames registrados para este pet.</p>
  </div>
  <div class="page-head-acoes">
    <a class="btn btn-secundario" href="{{ route('pets.index', ['cliente_id' => $pet->id_tutor]) }}">Voltar</a>
    <a class="btn btn-secundario" href="{{ route('pets.edit', $pet) }}"><x-icone nome="editar" />Editar</a>
    <a class="btn btn-primario" href="{{ route('consultas.create', ['pet_id' => $pet->id_pet]) }}"><x-icone nome="mais" />Agendar consulta</a>
  </div>
</div>

<section class="card pet-resumo" aria-label="Dados do pet">
  <span class="avatar avatar-grande" aria-hidden="true">{{ mb_strtoupper(mb_substr($pet->nm_pet, 0, 1)) }}</span>
  <dl>
    <div><dt>Pet</dt><dd>{{ $pet->nm_pet }} <x-pill-ativo :ativo="$pet->ic_ativo_pet" /></dd></div>
    <div><dt>Espécie e raça</dt><dd>{{ $pet->nm_especie_pet }} · {{ $pet->nm_raca_pet ?: 'Raça não informada' }}</dd></div>
    <div><dt>Peso</dt><dd>{{ $pet->peso_formatado }}</dd></div>
    <div>
      <dt>Cliente</dt>
      <dd>
        @if ($pet->tutor)
          <a class="link" href="{{ route('tutores.show', $pet->tutor) }}">{{ $pet->tutor->nm_tutor }}</a> · {{ $pet->tutor->cd_telefone_tutor ?: 'Sem telefone' }}
        @else
          —
        @endif
      </dd>
    </div>
  </dl>
</section>

<section class="card" aria-labelledby="linha-titulo">
  <h2 id="linha-titulo">Linha do tempo</h2>

  @if ($pet->consultas->isEmpty())
    <p class="vazio">Este pet ainda não tem consultas.</p>
  @else
    <ol class="linha-tempo">
      @foreach ($pet->consultas as $consulta)
        <li>
          <p class="lt-topo">
            <strong>{{ $consulta->dt_atendimento?->format('d/m/Y') }} às {{ $consulta->hora_curta }}</strong>
            <x-pill :status="$consulta->ic_status_atendimento" />
          </p>
          <p>{{ $consulta->ds_motivo ?: 'Consulta sem motivo informado.' }}</p>
          <small>{{ $consulta->funcionario?->nm_funcionario ?: 'Profissional não informado' }} · {{ $consulta->clinica?->nome ?: 'Clínica não informada' }}</small>

          @if ($consulta->exames->isNotEmpty())
            <ul class="chips">
              @foreach ($consulta->exames as $exame)
                <li>
                  <a href="{{ $exame->urlArquivo() }}" download="{{ $exame->nm_arquivo_original }}" title="{{ $exame->ds_observacao ?: $exame->nm_arquivo_original }}">
                    <x-icone nome="arquivo" />{{ $exame->nm_exame }}
                  </a>
                </li>
              @endforeach
            </ul>
          @endif
        </li>
      @endforeach
    </ol>
  @endif
</section>
@endsection
