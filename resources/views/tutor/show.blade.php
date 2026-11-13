@extends('layouts.app')
@section('titulo', $tutor->nm_tutor)

@section('conteudo')
<div class="page-head">
  <div>
    <h1>{{ $tutor->nm_tutor }}</h1>
    <p class="sub">Cliente desde {{ $tutor->created_at?->format('d/m/Y') ?? '—' }}.</p>
  </div>
  <div class="page-head-acoes">
    <a class="btn btn-secundario" href="{{ route('tutores.index') }}">Voltar</a>
    <a class="btn btn-secundario" href="{{ route('tutores.edit', $tutor) }}"><x-icone nome="editar" />Editar</a>
    <a class="btn btn-primario" href="{{ route('pets.create', ['tutor_id' => $tutor->id_tutor]) }}"><x-icone nome="mais" />Novo pet</a>
  </div>
</div>

<section class="card pet-resumo" aria-label="Dados do cliente">
  <span class="avatar avatar-grande" aria-hidden="true">{{ mb_strtoupper(mb_substr($tutor->nm_tutor, 0, 1)) }}</span>
  <dl>
    <div><dt>Nome</dt><dd>{{ $tutor->nm_tutor }} <x-pill-ativo :ativo="$tutor->ic_ativo_tutor" /></dd></div>
    <div><dt>E-mail</dt><dd>{{ $tutor->nm_email_tutor ?: '—' }}</dd></div>
    <div><dt>Telefone</dt><dd>{{ $tutor->cd_telefone_tutor ?: '—' }}</dd></div>
    <div><dt>CPF</dt><dd>{{ $tutor->cd_cpf_tutor ?: '—' }}</dd></div>
    <div><dt>Endereço</dt><dd>{{ $tutor->nm_endereco_tutor ?: '—' }}</dd></div>
  </dl>
</section>

<section class="card" aria-labelledby="pets-titulo">
  <div class="card-head">
    <h2 id="pets-titulo">Pets</h2>
    <a class="link" href="{{ route('tutores.pets.index', $tutor) }}">Ver lista</a>
  </div>

  @if ($tutor->pets->isEmpty())
    <p class="vazio">Este cliente ainda não tem pets cadastrados.</p>
  @else
    <ul class="lista-itens">
      @foreach ($tutor->pets as $petDoCliente)
        <li>
          <span class="item-icone"><x-icone nome="pet" /></span>
          <p class="item-info">
            <strong>{{ $petDoCliente->nm_pet }}</strong>
            <small>{{ $petDoCliente->nm_especie_pet }} · {{ $petDoCliente->nm_raca_pet ?: 'Raça não informada' }}</small>
          </p>
          <x-pill-ativo :ativo="$petDoCliente->ic_ativo_pet" />
          <a class="link" href="{{ route('pets.show', $petDoCliente) }}">Histórico</a>
        </li>
      @endforeach
    </ul>
  @endif
</section>
@endsection
