{{-- pill-ativo.blade.php: ativo/inativo --}}
@props(['ativo'])
<span class="pill {{ $ativo ? 'pill-ativo' : 'pill-inativo' }}">{{ $ativo ? 'Ativo' : 'Inativo' }}</span>