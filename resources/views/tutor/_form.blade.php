@php
  $editar = isset($tutor);

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

<form class="card form" method="POST" action="{{ $editar ? route('tutores.update', $tutor) : route('tutores.store') }}">
  @csrf
  @if ($editar)
    @method('PUT')
  @endif

  <div class="form-grid">
    <x-campo name="nome" label="Nome completo" :value="$editar ? $tutor->nm_tutor : null" required maxlength="100" classe="campo-full" />
    <x-campo name="email" label="E-mail" type="email" :value="$editar ? $tutor->nm_email_tutor : null" maxlength="120" />
    <x-campo name="telefone" label="Telefone" type="tel" :value="$editar ? $formatarTelefone($tutor->cd_telefone_tutor) : null" data-mascara="telefone" placeholder="(00) 00000-0000" />
    <x-campo name="cpf" label="CPF" :value="$editar ? $formatarCpf($tutor->cd_cpf_tutor) : null" inputmode="numeric" data-mascara="cpf" placeholder="000.000.000-00" />
    <x-campo name="endereco" label="Endereço" :value="$editar ? $tutor->nm_endereco_tutor : null" maxlength="150" classe="campo-full" />
  </div>

  <div class="form-acoes">
    <a class="btn btn-secundario" href="{{ $editar ? route('tutores.show', $tutor) : route('tutores.index') }}">Cancelar</a>
    <button class="btn btn-primario" type="submit">{{ $editar ? 'Salvar alterações' : 'Cadastrar cliente' }}</button>
  </div>
</form>
