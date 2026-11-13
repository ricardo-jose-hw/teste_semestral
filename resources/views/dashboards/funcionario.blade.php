@extends('layouts.app')
@section('titulo', 'Painel')

@section('conteudo')
@include('dashboards._painel', ['subtitulo' => 'Resumo da agenda da clínica hoje.'])
@endsection
