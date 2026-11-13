<!DOCTYPE html>
<html lang="pt-BR">
<head>
    @include('partials.head')
</head>

<body class="app">
<a class="skip-link" href="#conteudo">Pular para o conteúdo</a>
<div class="fundo-menu" data-menu-fechar></div>

<aside class="sidebar" id="menu" aria-label="Menu principal">
  <a class="sidebar-marca" href="{{ route('dashboard') }}" aria-label="Arizona Pet Care — painel">
    <img src="{{ asset('assets/img/logo-branco.png') }}" alt="Arizona Pet Care" width="150" height="54">
  </a>

  <nav class="sidebar-nav">
    @php
      $perfilAtual = auth()->user()?->perfil;
      $grupos = collect(config('painel.menu'))
          ->filter(fn ($item) => in_array($perfilAtual, $item[4], true))
          ->groupBy(3);
    @endphp

    @foreach ($grupos as $grupo => $itens)
      <p class="sidebar-grupo">{{ $grupo }}</p>
      @foreach ($itens as [$rota, $rotulo, $icone])
        @php($ativo = request()->routeIs($rota, \Illuminate\Support\Str::beforeLast($rota, '.').'.*'))
        <a href="{{ route($rota) }}" @if ($ativo) class="ativo" aria-current="page" @endif>
          <x-icone :nome="$icone" /><span>{{ $rotulo }}</span>
        </a>
      @endforeach
    @endforeach
  </nav>

  <div class="sidebar-companion" aria-hidden="true">
    <img src="{{ asset('assets/img/pets/cat.svg') }}" alt="" width="200" height="150">
    <p>Uma boa companhia<br>para o seu dia.</p>
  </div>

  <form action="{{ route('logout') }}" method="POST">
    @csrf
    <button class="sidebar-sair" type="submit"><x-icone nome="sair" /><span>Sair</span></button>
  </form>
</aside>

<div class="principal">
  <header class="topbar">
    <button class="menu-toggle" type="button" data-menu aria-controls="menu" aria-label="Abrir menu">
      <x-icone nome="menu" />
    </button>
    <p class="topbar-data">{{ \Illuminate\Support\Str::ucfirst(now()->translatedFormat('l, j \d\e F \d\e Y')) }}</p>
    @auth
    <div class="usuario">
      <span class="avatar" aria-hidden="true">{{ auth()->user()->iniciais }}</span>
      <p>
        <strong>{{ auth()->user()->name }}</strong>
        <small>{{ config('painel.perfis')[auth()->user()->perfil] ?? auth()->user()->perfil }}</small>
      </p>
    </div>
    @endauth
  </header>

  <main id="conteudo">
    @if (session('success'))
      <div class="alerta alerta-sucesso" role="status">{{ session('success') }}</div>
    @endif
    @if (session('error'))
      <div class="alerta alerta-erro" role="alert">{{ session('error') }}</div>
    @endif

    @yield('conteudo')

    <footer class="care-footer">
      <img src="{{ asset('assets/img/pets/paw.svg') }}" alt="" aria-hidden="true" width="20" height="20">
      Pequenos pacientes. Grandes cuidados.
    </footer>
  </main>
</div>

<dialog id="confirmar" aria-labelledby="confirmar-msg">
  <form method="dialog">
    <p id="confirmar-msg" data-msg></p>
    <div class="dialog-acoes">
      <button class="btn btn-secundario" value="cancelar">Voltar</button>
      <button class="btn btn-primario" value="ok">Confirmar</button>
    </div>
  </form>
</dialog>

</body>
</html>