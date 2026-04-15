<?php
/**
 * Verificar erro ao acessar via web
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "<h1>Teste de Carregamento Web</h1>";
echo "<pre>";

try {
    require __DIR__ . '/vendor/autoload.php';
    echo "✓ Autoload OK\n";
    
    $app = require __DIR__ . '/bootstrap/app.php';
    echo "✓ Bootstrap OK\n";
    
    // Criar request
    $request = Illuminate\Http\Request::create('/');
    echo "✓ Request criado\n";
    
    // Criar kernel
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "✓ HTTP Kernel criado\n";
    
    // Processar request
    $response = $kernel->handle($request);
    echo "✓ Request processado\n";
    echo "Status: " . $response->getStatusCode() . "\n";
    
    // Enviar resposta
    $response->send();
    
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
