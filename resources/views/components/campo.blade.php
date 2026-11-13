@props(['name', 'label', 'value' => null, 'type' => 'text', 'classe' => '', 'id' => null])
@php $campoId = $id ?? $name; @endphp
<div class="campo {{ $classe }}">
  <label for="{{ $campoId }}">{{ $label }}</label>
  <input id="{{ $campoId }}" name="{{ $name }}" type="{{ $type }}"
         @if ($type !== 'password') value="{{ old($name, $value) }}" @endif
         {{ $attributes }}>
  <x-erro :name="$name" />
</div>