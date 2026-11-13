<!DOCTYPE html>
<html lang="pt-BR">
<head>
    @include('partials.head')
</head>
<body class="auth">
<div class="auth-grid">
    <aside class="auth-marca">
    <img src="assets/img/logo-branco.png" alt="Arizona Pet Care" width="180" height="65">
    <div class="auth-story">
      <p class="eyebrow">Gente que cuida. Pets que encantam.</p>
      <p class="auth-titulo">Todo cuidado começa com uma boa companhia.</p>
      <p>Mais tempo para cuidar deles. Um só lugar para organizar o dia da clínica.</p>
    </div>
    <div class="pet-scene" aria-hidden="true">
      <span class="scene-sun"></span><span class="scene-spark spark-one">✧</span><span class="scene-spark spark-two">✧</span>
      <span class="scene-caption">Aqui, cada patinha importa.</span>
      <img class="scene-dog" src="assets/img/pets/dog.svg" alt="" width="260" height="260">
      <img class="scene-cat" src="assets/img/pets/cat.svg" alt="" width="200" height="150">
      <span class="scene-ground"></span>
    </div>
    <p class="auth-signoff">Arizona Pet Care <span>Feito de cuidado, todos os dias.</span></p>
    </aside> 
    <main class="auth-conteudo" id="conteudo">
    <div id="conteudo" class="auth-card">
    <img class="auth-peek" src="assets/img/pets/cat.svg" alt="" aria-hidden="true" width="200" height="150">
        @if (session('success'))
            <div class="alerta alerta-sucesso" role="status">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alerta alerta-erro" role="alert">{{ session('error') }}</div>
        @endif

        @yield('conteudo')
    </div>
    </main>
</div>

</body>
</html>