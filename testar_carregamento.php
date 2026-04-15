<?php
/**
 * Testar carregamento do Laravel
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testando carregamento do Laravel...\n\n";

try {
    require __DIR__ . '/vendor/autoload.php';
    echo "✓ Autoload OK\n";
    
    $app = require __DIR__ . '/bootstrap/app.php';
    echo "✓ Bootstrap OK\n";
    
    // Tentar fazer bindings manualmente antes de criar kernel
    $app->singleton('files', function () {
        return new \Illuminate\Filesystem\Filesystem();
    });
    echo "✓ Binding 'files' registrado\n";
    
    // Verificar se config existe
    try {
        $config = $app->make('config');
        echo "✓ Config OK\n";
    } catch (Exception $e) {
        echo "✗ Config erro: " . $e->getMessage() . "\n";
    }
    
    // Tentar criar kernel HTTP
    try {
        $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
        echo "✓ HTTP Kernel OK\n";
    } catch (Exception $e) {
        echo "✗ HTTP Kernel erro: " . $e->getMessage() . "\n";
    }
    
    // Tentar criar kernel Console
    try {
        $consoleKernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        echo "✓ Console Kernel OK\n";
    } catch (Exception $e) {
        echo "✗ Console Kernel erro: " . $e->getMessage() . "\n";
    }
    
} catch (Throwable $e) {
    echo "✗ ERRO: " . $e->getMessage() . "\n";
    echo "Arquivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
