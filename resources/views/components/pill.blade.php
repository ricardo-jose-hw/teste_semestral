{{-- pill.blade.php: status da consulta --}}
@props(['status'])
<span class="pill pill-{{ config('painel.classe_status')[$status] ?? 'neutro' }}">{{ $status }}</span>