@php
  $reagendar = $consulta !== null;
  $petAtual = old('pet_id', $petSelecionado);
  $clienteAtual = old('cliente_id', $clienteSelecionado);
@endphp

<form class="card form" method="POST" action="{{ $reagendar ? route('consultas.update', $consulta) : route('consultas.store') }}">
  @csrf
  @if ($reagendar)
    @method('PUT')
  @endif

  <div class="form-grid">
    <x-campo-select
      name="funcionario_id"
      label="Veterinário"
      :options="$funcionarios->pluck('nm_funcionario', 'id_funcionario')"
      :selected="$consulta?->FUNCIONARIO_id_funcionario"
      placeholder="Selecione"
      required
    />

    <div class="campo">
      <label for="cliente_id">Cliente</label>
      <select id="cliente_id" name="cliente_id" data-filtra-pets @disabled($reagendar)>
        <option value="">Selecione</option>
        @foreach ($clientes as $id => $nome)
          <option value="{{ $id }}" @selected((string) $clienteAtual === (string) $id)>{{ $nome }}</option>
        @endforeach
      </select>
      @if ($reagendar)
        <small class="dica">Cliente e pet não mudam ao reagendar.</small>
      @endif
    </div>

    <div class="campo">
      <label for="pet_id">Pet</label>
      <select id="pet_id" name="pet_id" required @disabled($reagendar)>
        <option value="">Selecione</option>
        @foreach ($pets as $pet)
          <option value="{{ $pet->id_pet }}" data-tutor="{{ $pet->id_tutor }}" @selected((string) $petAtual === (string) $pet->id_pet)>
            {{ $pet->nm_pet }} ({{ $pet->nm_especie_pet }})
          </option>
        @endforeach
      </select>
      <x-erro name="pet_id" />
    </div>

    <x-campo
      name="data"
      label="Data"
      type="date"
      :value="$consulta?->dt_atendimento?->format('Y-m-d') ?? now()->toDateString()"
      required
      min="{{ now()->toDateString() }}"
    />
    <x-campo name="hora" label="Horário" type="time" :value="$consulta?->hora_curta" required step="900" />

    <div class="campo campo-full">
      <label for="motivo">Motivo / observações</label>
      <textarea id="motivo" name="motivo" rows="3">{{ old('motivo', $consulta?->ds_motivo) }}</textarea>
      <x-erro name="motivo" />
    </div>
  </div>

  <div class="form-acoes">
    <a class="btn btn-secundario" href="{{ route('consultas.index') }}">Cancelar</a>
    <button class="btn btn-primario" type="submit">{{ $reagendar ? 'Reagendar' : 'Agendar' }}</button>
  </div>
</form>
