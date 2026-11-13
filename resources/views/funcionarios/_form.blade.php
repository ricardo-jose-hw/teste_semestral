@php
  $editar = isset($funcionario);
  $conta = $editar ? $funcionario->user : null;
  $perfilSelecionado = $conta?->perfil ?? 'funcionario';

  $formatarCpf = function ($valor) {
      $digitos = preg_replace('/\D/', '', (string) $valor);

      return strlen($digitos) === 11 ? preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $digitos) : $valor;
  };

  $formatarTelefone = function ($valor) {
      $digitos = preg_replace('/\D/', '', (string) $valor);
      $tamanho = strlen($digitos);

      if ($tamanho === 11) {
          return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $digitos);
      }

      return $tamanho === 10 ? preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $digitos) : $valor;
  };
@endphp

<form class="card form" method="POST" action="{{ $editar ? route('funcionarios.update', $funcionario) : route('funcionarios.store') }}" autocomplete="off">
  @csrf
  @if ($editar)
    @method('PUT')
  @endif

  <div class="form-grid">
    <x-campo name="nome" label="Nome completo" :value="$editar ? $funcionario->nm_funcionario : null" required maxlength="100" />
    <x-campo name="email" label="E-mail" type="email" :value="$editar ? $funcionario->nm_email_funcionario : null" required maxlength="120" />
    <x-campo name="cpf" label="CPF" :value="$editar ? $formatarCpf($funcionario->cd_cpf_funcionario) : null" required inputmode="numeric" data-mascara="cpf" placeholder="000.000.000-00" />
    <x-campo name="telefone" label="Telefone" type="tel" :value="$editar ? $formatarTelefone($funcionario->cd_telefone_funcionario) : null" required data-mascara="telefone" placeholder="(00) 00000-0000" />
    <x-campo-select name="especialidade" label="Especialidade" :options="config('painel.especialidades')" :selected="$editar ? $funcionario->nm_especialidade_funcionario : null" placeholder="Não se aplica" />
    <x-campo name="crmv" label="CRMV" :value="$editar ? $funcionario->cd_crmv_funcionario : null" maxlength="80" />
    <x-campo-select name="perfil" label="Perfil de acesso" :options="config('painel.perfis')" :selected="$perfilSelecionado" placeholder="Selecione" required />
    <x-campo
      name="senha"
      :label="$editar ? 'Nova senha (deixe em branco para manter)' : 'Senha de acesso'"
      type="password"
      :required="! $editar"
      minlength="8"
      autocomplete="new-password"
    />
    <x-campo
      name="senha_confirmation"
      label="Confirmar senha"
      type="password"
      :required="! $editar"
      minlength="8"
      autocomplete="new-password"
    />
    <p class="nota campo-full"><x-icone nome="funcionarios" />O funcionário entra no sistema com este e-mail e a senha definida aqui. O que ele enxerga depende do perfil de acesso.</p>
  </div>

  <div class="form-acoes">
    <a class="btn btn-secundario" href="{{ route('funcionarios.index') }}">Cancelar</a>
    <button class="btn btn-primario" type="submit">{{ $editar ? 'Salvar alterações' : 'Cadastrar funcionário' }}</button>
  </div>
</form>
