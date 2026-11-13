@php($editar = isset($pet))

<form class="card form" method="POST" action="{{ $editar ? route('pets.update', $pet) : route('pets.store') }}">
  @csrf
  @if ($editar)
    @method('PUT')
  @endif

  <div class="form-grid">
    <x-campo-select
      name="id_tutor"
      label="Cliente (tutor)"
      :options="$clientes"
      :selected="$editar ? $pet->id_tutor : $tutorSelecionado"
      placeholder="Selecione"
      required
      classe="campo-full"
    />
    <x-campo name="nome" label="Nome do pet" :value="$editar ? $pet->nm_pet : null" required maxlength="50" />
    <x-campo name="especie" label="Espécie" :value="$editar ? $pet->nm_especie_pet : null" required maxlength="50" list="especies" />
    <x-campo name="raca" label="Raça" :value="$editar ? $pet->nm_raca_pet : null" maxlength="50" />
    <x-campo name="peso" label="Peso (kg)" type="number" :value="$editar ? $pet->qt_peso_pet : null" step="0.01" min="0" max="999.99" />
  </div>

  <x-datalist id="especies" :options="config('painel.especies')" />

  <div class="form-acoes">
    <a class="btn btn-secundario" href="{{ $editar ? route('pets.show', $pet) : route('pets.index') }}">Cancelar</a>
    <button class="btn btn-primario" type="submit">{{ $editar ? 'Salvar alterações' : 'Cadastrar pet' }}</button>
  </div>
</form>
