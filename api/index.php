<?php

// TEMPORÁRIO: mostrar qualquer erro do PHP (remover depois de depurar)
ini_set('display_errors', '1');
ini_set('log_errors', '1');
ini_set('error_log', 'php://stderr');
error_reporting(E_ALL);

// Na Vercel só /tmp é gravável
foreach ([
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Valores padrão (as variáveis do painel da Vercel, se existirem, têm prioridade)
$defaults = [
    'LARAVEL_STORAGE_PATH' => '/tmp/storage',
    'VIEW_COMPILED_PATH'   => '/tmp/storage/framework/views',
    'APP_CONFIG_CACHE'     => '/tmp/bootstrap/cache/config.php',
    'APP_ROUTES_CACHE'     => '/tmp/bootstrap/cache/routes.php',
    'APP_EVENTS_CACHE'     => '/tmp/bootstrap/cache/events.php',
    'APP_PACKAGES_CACHE'   => '/tmp/bootstrap/cache/packages.php',
    'APP_SERVICES_CACHE'   => '/tmp/bootstrap/cache/services.php',
    'LOG_CHANNEL'          => 'stderr',
    'SESSION_DRIVER'       => 'cookie',
    'CACHE_STORE'          => 'array',
];
foreach ($defaults as $chave => $valor) {
    if (getenv($chave) === false) {
        putenv("$chave=$valor");
        $_ENV[$chave] = $valor;
        $_SERVER[$chave] = $valor;
    }
}

try {
    require __DIR__ . '/../public/index.php';
} catch (Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo get_class($e) . ': ' . $e->getMessage() . "\n"
        . $e->getFile() . ':' . $e->getLine() . "\n\n"
        . $e->getTraceAsString();
}
