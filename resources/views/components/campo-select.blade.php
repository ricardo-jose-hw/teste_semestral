@props(['name', 'label', 'options' => [], 'selected' => null, 'placeholder' => null, 'classe' => '', 'id' => null])
@php
    $campoId = $id ?? $name;
    $lista = $options instanceof \Illuminate\Support\Collection ? $options->all() : $options;
    $assoc = \Illuminate\Support\Arr::isAssoc($lista);
    $atual = (string) old($name, $selected);
@endphp
<div class="campo {{ $classe }}">
  <label for="{{ $campoId }}">{{ $label }}</label>
  <select id="{{ $campoId }}" name="{{ $name }}" {{ $attributes }}>
    @if ($placeholder !== null)<option value="">{{ $placeholder }}</option>@endif
    @foreach ($lista as $chave => $texto)
      @php $valor = $assoc ? $chave : $texto; @endphp
      <option value="{{ $valor }}" @selected($atual === (string) $valor)>{{ $texto }}</option>
    @endforeach
  </select>
  <x-erro :name="$name" />
</div>