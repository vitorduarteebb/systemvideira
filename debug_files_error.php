<?php
/**
 * Debug do erro "Target class [files] does not exist"
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Debug - Erro 'files'</h1>";
echo "<pre>";

try {
    // Carregar autoload
    require __DIR__ . '/vendor/autoload.php';
    echo "✓ Autoload carregado\n\n";
    
    // Carregar bootstrap
    $app = require __DIR__ . '/bootstrap/app.php';
    echo "✓ Bootstrap carregado\n";
    echo "Tipo: " . get_class($app) . "\n\n";
    
    // Verificar configurações
    echo "Verificando configurações:\n";
    $config = $app->make('config');
    
    // Session driver
    $sessionDriver = $config->get('session.driver');
    echo "Session driver: $sessionDriver\n";
    
    // Verificar se há binding problemático
    echo "\nVerificando bindings no container...\n";
    try {
        $app->make('files');
        echo "✗ 'files' está registrado como binding (PROBLEMA!)\n";
    } catch (Exception $e) {
        echo "✓ 'files' não está registrado (OK)\n";
    }
    
    // Tentar criar kernel
    echo "\nTentando criar HTTP Kernel...\n";
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "✓ HTTP Kernel criado\n";
    
    // Tentar criar Console Kernel
    echo "\nTentando criar Console Kernel...\n";
    $consoleKernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    echo "✓ Console Kernel criado\n";
    
    // Verificar service providers
    echo "\nService Providers registrados:\n";
    $providers = $app->getLoadedProviders();
    foreach ($providers as $provider => $loaded) {
        echo "  - $provider: " . ($loaded ? "✓" : "✗") . "\n";
    }
    
} catch (Throwable $e) {
    echo "✗ ERRO:\n";
    echo "Tipo: " . get_class($e) . "\n";
    echo "Mensagem: " . $e->getMessage() . "\n";
    echo "Arquivo: " . $e->getFile() . "\n";
    echo "Linha: " . $e->getLine() . "\n";
    echo "\nStack trace:\n";
    $trace = $e->getTrace();
    foreach (array_slice($trace, 0, 10) as $i => $frame) {
        $file = $frame['file'] ?? 'unknown';
        $line = $frame['line'] ?? '?';
        $function = $frame['function'] ?? 'unknown';
        $class = $frame['class'] ?? '';
        echo "  #$i $file($line): " . ($class ? "$class::$function()" : "$function()") . "\n";
    }
}

echo "</pre>";
