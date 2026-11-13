@props(['action', 'icone', 'titulo', 'metodo' => 'POST', 'confirmar' => null, 'perigo' => false, 'campos' => []])
<form method="POST" action="{{ $action }}">
  @csrf
  @if (strtoupper($metodo) !== 'POST') @method($metodo) @endif
  @foreach ($campos as $nome => $valor)
    <input type="hidden" name="{{ $nome }}" value="{{ $valor }}">
  @endforeach
  <button class="btn-icone {{ $perigo ? 'perigo' : '' }}" type="submit" title="{{ $titulo }}" aria-label="{{ $titulo }}" @if ($confirmar) data-confirmar="{{ $confirmar }}" @endif><x-icone :nome="$icone" /></button>
</form>
