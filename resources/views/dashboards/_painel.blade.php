@php($subtitulo = $subtitulo ?? 'Resumo da operação da clínica hoje.')

<div class="page-head">
  <div>
    <h1>Painel</h1>
    <p class="sub">{{ $subtitulo }}</p>
  </div>
  <a class="btn btn-primario" href="{{ route('consultas.create') }}"><x-icone nome="mais" />Agendar consulta</a>
</div>

<section class="welcome-care" aria-labelledby="welcome-title">
  <div class="welcome-copy">
    <p class="eyebrow">NA COMPANHIA DO CUIDADO</p>
    <h2 id="welcome-title">Um bom dia começa com carinho.</h2>
    <p>A agenda em dia. O cuidado sempre por perto.</p>
  </div>
  <div class="welcome-pets" aria-hidden="true">
    <span class="welcome-orbit"></span>
    <img class="welcome-dog" src="{{ asset('assets/img/pets/dog.svg') }}" alt="" width="260" height="260">
    <img class="welcome-cat" src="{{ asset('assets/img/pets/cat.svg') }}" alt="" width="200" height="150">
    <span class="welcome-star">✧</span>
  </div>
</section>

<section class="kpis" aria-label="Indicadores">
  @foreach ($indicadores as [$icone, $rotulo, $valor])
    <article class="kpi">
      <span class="kpi-icone"><x-icone :nome="$icone" /></span>
      <p><strong>{{ $valor }}</strong><span>{{ $rotulo }}</span></p>
    </article>
  @endforeach
</section>

<div class="grid-2">
  <section class="card" aria-labelledby="agenda-titulo">
    <div class="card-head">
      <h2 id="agenda-titulo">Agenda de hoje</h2>
      <a class="link" href="{{ route('consultas.index', ['data' => $hoje]) }}">Ver todas</a>
    </div>

    @if ($agenda->isEmpty())
      <p class="vazio">Nenhuma consulta agendada para hoje.</p>
    @else
      <ul class="lista-itens">
        @foreach ($agenda as $consulta)
          <li>
            <time class="item-hora" datetime="{{ $consulta->dt_atendimento?->format('Y-m-d') }}T{{ $consulta->hr_atendimento }}">{{ $consulta->hora_curta }}</time>
            <p class="item-info">
              <strong>
                @if ($consulta->pet)
                  <a class="link" href="{{ route('pets.show', $consulta->pet) }}">{{ $consulta->pet->nm_pet }}</a>
                @else
                  Pet não informado
                @endif
              </strong>
              <small>{{ $consulta->pet?->tutor?->nm_tutor ?: 'Cliente não informado' }} · {{ $consulta->funcionario?->nm_funcionario ?: 'Profissional não informado' }}</small>
            </p>
            <x-pill :status="$consulta->ic_status_atendimento" />
          </li>
        @endforeach
      </ul>
    @endif
  </section>

  <section class="card" aria-labelledby="exames-titulo">
    <div class="card-head">
      <h2 id="exames-titulo">Exames recentes</h2>
      <a class="link" href="{{ route('exames.index') }}">Ver todos</a>
    </div>

    @if ($examesRecentes->isEmpty())
      <p class="vazio">Nenhum exame anexado ainda.</p>
    @else
      <ul class="lista-itens">
        @foreach ($examesRecentes as $exame)
          <li>
            <span class="item-icone"><x-icone nome="arquivo" /></span>
            <p class="item-info">
              <strong><a class="link" href="{{ $exame->urlArquivo() }}" download="{{ $exame->nm_arquivo_original }}">{{ $exame->nm_exame }}</a></strong>
              <small>{{ $exame->consulta?->pet?->nm_pet ?: 'Pet não informado' }} · {{ $exame->nm_arquivo_original }}</small>
            </p>
          </li>
        @endforeach
      </ul>
    @endif
  </section>
</div>
