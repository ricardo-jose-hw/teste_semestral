@props(['id', 'options'])
<datalist id="{{ $id }}">
  @foreach ($options as $opcao)<option value="{{ $opcao }}">@endforeach
</datalist>