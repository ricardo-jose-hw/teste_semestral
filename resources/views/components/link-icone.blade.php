@props(['href', 'icone', 'titulo'])
<a class="btn-icone" href="{{ $href }}" title="{{ $titulo }}" aria-label="{{ $titulo }}"><x-icone :nome="$icone" /></a>