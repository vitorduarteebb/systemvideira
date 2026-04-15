<?php
/**
 * Script de debug para identificar erro 500
 * Acesse: https://yellow-spoonbill-121332.hostingersite.com/debug.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "<h1>Debug - Sistema VIDEIRA</h1>";
echo "<pre>";

// 1. Verificar PHP
echo "1. PHP: " . phpversion() . "\n";

// 2. Verificar arquivos essenciais
echo "\n2. Arquivos:\n";
$files = ['index.php', '.env', 'vendor/autoload.php', 'bootstrap/app.php'];
foreach ($files as $file) {
    echo "   " . (file_exists($file) ? "✓" : "✗") . " $file\n";
}

// 3. Tentar carregar Laravel
echo "\n3. Carregando Laravel...\n";
try {
    if (!file_exists('vendor/autoload.php')) {
        throw new Exception("vendor/autoload.php não existe");
    }
    
    require 'vendor/autoload.php';
    echo "   ✓ Autoload OK\n";
    
    $app = require 'bootstrap/app.php';
    echo "   ✓ Bootstrap OK\n";
    
    // Tentar criar kernel
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "   ✓ Kernel OK\n";
    
    // Tentar processar request
    $request = Illuminate\Http\Request::create('/');
    $response = $kernel->handle($request);
    echo "   ✓ Request processado!\n";
    echo "   Status: " . $response->getStatusCode() . "\n";
    
} catch (Throwable $e) {
    echo "   ✗ ERRO:\n";
    echo "   Tipo: " . get_class($e) . "\n";
    echo "   Mensagem: " . $e->getMessage() . "\n";
    echo "   Arquivo: " . $e->getFile() . "\n";
    echo "   Linha: " . $e->getLine() . "\n";
    
    echo "\n   Stack trace:\n";
    $trace = $e->getTrace();
    foreach (array_slice($trace, 0, 5) as $i => $frame) {
        $file = $frame['file'] ?? 'unknown';
        $line = $frame['line'] ?? '?';
        $function = $frame['function'] ?? 'unknown';
        echo "   #$i $file($line): $function()\n";
    }
}

// 4. Verificar .env
echo "\n4. .env:\n";
if (file_exists('.env')) {
    $env = parse_ini_file('.env');
    echo "   APP_KEY: " . (empty($env['APP_KEY']) ? "✗ VAZIO" : "✓ Configurado") . "\n";
    echo "   APP_DEBUG: " . ($env['APP_DEBUG'] ?? 'não definido') . "\n";
    echo "   DB_DATABASE: " . ($env['DB_DATABASE'] ?? 'não definido') . "\n";
} else {
    echo "   ✗ .env não existe\n";
}

// 5. Verificar views
echo "\n5. Views:\n";
$views = [
    'resources/views/auth/login.blade.php',
    'resources/views/dashboard.blade.php',
];
foreach ($views as $view) {
    echo "   " . (file_exists($view) ? "✓" : "✗") . " $view\n";
}

// 6. Verificar permissões
echo "\n6. Permissões:\n";
$dirs = ['storage', 'bootstrap/cache'];
foreach ($dirs as $dir) {
    $writable = is_writable($dir) ? "gravável" : "NÃO gravável";
    echo "   $dir: $writable\n";
}

// 7. Ver logs
echo "\n7. Últimas linhas do log:\n";
if (file_exists('storage/logs/laravel.log')) {
    $log = file_get_contents('storage/logs/laravel.log');
    $lines = explode("\n", $log);
    $last = array_slice($lines, -10);
    foreach ($last as $line) {
        if (!empty(trim($line))) {
            echo "   " . trim($line) . "\n";
        }
    }
} else {
    echo "   Log não existe\n";
}

echo "</pre>";
