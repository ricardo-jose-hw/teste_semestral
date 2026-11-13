@extends('layouts.app')
@section('titulo', 'Funcionários')

@section('conteudo')
<div class="page-head">
  <div>
    <h1>Funcionários</h1>
    <p class="sub">Equipe da clínica, perfis de acesso e vínculos.</p>
  </div>
  <a class="btn btn-primario" href="{{ route('funcionarios.create') }}"><x-icone nome="mais" />Novo funcionário</a>
</div>

<form class="card filtros" method="GET" action="{{ route('funcionarios.index') }}" autocomplete="off">
  <x-campo name="q" label="Buscar" type="search" :value="$busca" placeholder="Nome, CPF ou e-mail" classe="filtro-largo" />
  <div class="filtros-acoes">
    <button class="btn btn-primario" type="submit">Filtrar</button>
    <a class="btn btn-secundario" href="{{ route('funcionarios.index') }}">Limpar</a>
  </div>
</form>

@if ($funcionarios->isEmpty())
  <div class="card">
    <p class="vazio">Nenhum funcionário encontrado.</p>
  </div>
@else
  <div class="card tabela-card">
    <div class="tabela-wrap">
      <table>
        <thead>
          <tr>
            <th>Funcionário</th>
            <th>CPF</th>
            <th>Telefone</th>
            <th>Especialidade</th>
            <th>Perfil</th>
            <th>Clínica</th>
            <th>Status</th>
            <th><span class="sr-only">Ações</span></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($funcionarios as $funcionario)
            <tr>
              <td>
                <strong>{{ $funcionario->nm_funcionario }}</strong>
                <small>{{ $funcionario->nm_email_funcionario }}</small>
              </td>
              <td>{{ $funcionario->cd_cpf_funcionario ?: '—' }}</td>
              <td>{{ $funcionario->cd_telefone_funcionario ?: '—' }}</td>
              <td>{{ $funcionario->nm_especialidade_funcionario ?: '—' }}</td>
              <td>{{ config('painel.perfis')[$funcionario->user?->perfil] ?? '—' }}</td>
              <td>{{ $funcionario->clinica?->nome ?: '—' }}</td>
              <td><x-pill-ativo :ativo="$funcionario->ic_ativo_funcionario" /></td>
              <td class="acoes">
                <x-link-icone :href="route('funcionarios.edit', $funcionario)" icone="editar" :titulo="'Editar '.$funcionario->nm_funcionario" />
                <x-botao-status :action="route('funcionarios.status', $funcionario)" :ativo="$funcionario->ic_ativo_funcionario" :nome="$funcionario->nm_funcionario" />
                <x-botao-acao
                  :action="route('funcionarios.destroy', $funcionario)"
                  metodo="DELETE"
                  icone="lixo"
                  :titulo="'Excluir '.$funcionario->nm_funcionario"
                  :confirmar="'Excluir '.$funcionario->nm_funcionario.'? A conta de acesso também será removida.'"
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
