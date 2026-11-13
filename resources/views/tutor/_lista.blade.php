@if ($tutores->isEmpty())
  <div class="card">
    <p class="vazio">Nenhum cliente encontrado.</p>
  </div>
@else
  <div class="card tabela-card">
    <div class="tabela-wrap">
      <table>
        <thead>
          <tr>
            <th>Cliente</th>
            <th>Contato</th>
            <th>CPF</th>
            <th>Pets</th>
            <th><span class="sr-only">Ações</span></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($tutores as $tutor)
            <tr>
              <td>
                <strong>{{ $tutor->nm_tutor }}</strong>
                <small>{{ $tutor->nm_endereco_tutor ?: 'Endereço não informado' }}</small>
              </td>
              <td>
                {{ $tutor->nm_email_tutor ?: '—' }}
                <small>{{ $tutor->cd_telefone_tutor ?: 'Sem telefone' }}</small>
              </td>
              <td>{{ $tutor->cd_cpf_tutor ?: '—' }}</td>
              <td>
                @forelse ($tutor->pets as $petDoCliente)
                  <a class="link" href="{{ route('pets.show', $petDoCliente) }}">{{ $petDoCliente->nm_pet }}</a>
                @empty
                  <small>Sem pets</small>
                @endforelse
              </td>
              <td class="acoes">
                <x-link-icone :href="route('tutores.show', $tutor)" icone="clientes" :titulo="'Ver '.$tutor->nm_tutor" />
                <x-link-icone :href="route('pets.create', ['tutor_id' => $tutor->id_tutor])" icone="mais" :titulo="'Adicionar pet a '.$tutor->nm_tutor" />
                <x-link-icone :href="route('tutores.edit', $tutor)" icone="editar" :titulo="'Editar '.$tutor->nm_tutor" />
                <x-botao-acao
                  :action="route('tutores.destroy', $tutor)"
                  metodo="DELETE"
                  icone="lixo"
                  :titulo="'Excluir '.$tutor->nm_tutor"
                  :confirmar="'Excluir '.$tutor->nm_tutor.'? Os pets e o histórico também serão removidos.'"
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
