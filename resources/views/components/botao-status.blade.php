@props(['action', 'ativo', 'nome'])
@php $titulo = ($ativo ? 'Inativar ' : 'Reativar ').$nome; @endphp
<x-botao-acao :action="$action" metodo="PATCH" :icone="$ativo ? 'inativar' : 'reativar'" :titulo="$titulo" />