<?php
/**
 * DIAGNÓSTICO COMPLETO DO SERVIDOR
 * Acesse: https://yellow-spoonbill-121332.hostingersite.com/diagnostico_completo.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Diagnóstico - Sistema VIDEIRA</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1a1a1a; color: #0f0; }
        h1 { color: #0ff; }
        .ok { color: #0f0; }
        .erro { color: #f00; }
        .aviso { color: #ff0; }
        pre { background: #000; padding: 10px; border: 1px solid #333; }
    </style>
</head>
<body>
<h1>🔍 DIAGNÓSTICO COMPLETO DO SERVIDOR</h1>
<pre>
<?php

echo "========================================\n";
echo "1. INFORMAÇÕES DO SERVIDOR\n";
echo "========================================\n";
echo "Diretório atual: " . __DIR__ . "\n";
echo "Script executado: " . __FILE__ . "\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "\n";
echo "\n";

echo "========================================\n";
echo "2. ARQUIVOS NA RAIZ\n";
echo "========================================\n";
$arquivos_raiz = [
    'index.php',
    '.htaccess',
    'artisan',
    'composer.json',
    'package.json',
    'vite.config.js',
];

foreach ($arquivos_raiz as $arquivo) {
    $existe = file_exists(__DIR__ . '/' . $arquivo);
    $status = $existe ? '✓' : '✗';
    $cor = $existe ? 'ok' : 'erro';
    echo "<span class='$cor'>$status $arquivo</span>\n";
    
    if ($existe && $arquivo === 'index.php') {
        echo "   Tamanho: " . filesize(__DIR__ . '/' . $arquivo) . " bytes\n";
    }
}
echo "\n";

echo "========================================\n";
echo "3. PASTAS PRINCIPAIS\n";
echo "========================================\n";
$pastas = [
    'app',
    'bootstrap',
    'config',
    'database',
    'resources',
    'routes',
    'storage',
    'vendor',
];

foreach ($pastas as $pasta) {
    $caminho = __DIR__ . '/' . $pasta;
    $existe = is_dir($caminho);
    $status = $existe ? '✓' : '✗';
    $cor = $existe ? 'ok' : 'erro';
    echo "<span class='$cor'>$status $pasta/</span>\n";
    
    if ($existe) {
        $arquivos = glob($caminho . '/*');
        echo "   Itens: " . count($arquivos) . "\n";
        
        // Verificar arquivos específicos importantes
        if ($pasta === 'vendor' && file_exists($caminho . '/autoload.php')) {
            echo "   <span class='ok'>  ✓ vendor/autoload.php existe</span>\n";
        } elseif ($pasta === 'vendor') {
            echo "   <span class='erro'>  ✗ vendor/autoload.php NÃO existe</span>\n";
            echo "   <span class='aviso'>  ⚠ Execute: composer install</span>\n";
        }
        
        if ($pasta === 'bootstrap' && file_exists($caminho . '/app.php')) {
            echo "   <span class='ok'>  ✓ bootstrap/app.php existe</span>\n";
        } elseif ($pasta === 'bootstrap') {
            echo "   <span class='erro'>  ✗ bootstrap/app.php NÃO existe</span>\n";
        }
    }
}
echo "\n";

echo "========================================\n";
echo "4. ARQUIVOS ESSENCIAIS DO LARAVEL\n";
echo "========================================\n";
$arquivos_essenciais = [
    'vendor/autoload.php' => 'Autoload do Composer',
    'bootstrap/app.php' => 'Bootstrap do Laravel',
    'app/Http/Kernel.php' => 'HTTP Kernel',
    'app/Console/Kernel.php' => 'Console Kernel',
    'app/Exceptions/Handler.php' => 'Exception Handler',
    'routes/web.php' => 'Rotas Web',
    'config/app.php' => 'Config App',
    'config/database.php' => 'Config Database',
    '.env' => 'Arquivo de Configuração',
];

foreach ($arquivos_essenciais as $arquivo => $descricao) {
    $caminho = __DIR__ . '/' . $arquivo;
    $existe = file_exists($caminho);
    $status = $existe ? '✓' : '✗';
    $cor = $existe ? 'ok' : 'erro';
    echo "<span class='$cor'>$status $arquivo</span> ($descricao)\n";
}
echo "\n";

echo "========================================\n";
echo "5. TESTE DE CARREGAMENTO DO LARAVEL\n";
echo "========================================\n";

// Testar vendor/autoload.php
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo "✓ vendor/autoload.php encontrado\n";
    try {
        require __DIR__ . '/vendor/autoload.php';
        echo "✓ Autoload carregado com sucesso\n";
    } catch (Throwable $e) {
        echo "✗ ERRO ao carregar autoload: " . $e->getMessage() . "\n";
    }
} else {
    echo "✗ vendor/autoload.php NÃO encontrado\n";
    echo "  ⚠ Execute: composer install\n";
}

// Testar bootstrap/app.php
if (file_exists(__DIR__ . '/bootstrap/app.php')) {
    echo "✓ bootstrap/app.php encontrado\n";
    try {
        $app = require __DIR__ . '/bootstrap/app.php';
        echo "✓ Bootstrap carregado com sucesso\n";
        echo "  Tipo: " . get_class($app) . "\n";
    } catch (Throwable $e) {
        echo "✗ ERRO ao carregar bootstrap:\n";
        echo "  Tipo: " . get_class($e) . "\n";
        echo "  Mensagem: " . $e->getMessage() . "\n";
        echo "  Arquivo: " . $e->getFile() . "\n";
        echo "  Linha: " . $e->getLine() . "\n";
    }
} else {
    echo "✗ bootstrap/app.php NÃO encontrado\n";
}

echo "\n";

echo "========================================\n";
echo "6. VERIFICAR .ENV\n";
echo "========================================\n";
if (file_exists(__DIR__ . '/.env')) {
    echo "✓ .env existe\n";
    $env = parse_ini_file(__DIR__ . '/.env');
    echo "  APP_KEY: " . (empty($env['APP_KEY']) ? "✗ VAZIO" : "✓ Configurado") . "\n";
    echo "  DB_DATABASE: " . ($env['DB_DATABASE'] ?? 'não definido') . "\n";
    echo "  DB_HOST: " . ($env['DB_HOST'] ?? 'não definido') . "\n";
} else {
    echo "✗ .env NÃO existe\n";
}

echo "\n";

echo "========================================\n";
echo "7. PERMISSÕES\n";
echo "========================================\n";
$permissoes = [
    'storage' => 'storage/',
    'bootstrap/cache' => 'bootstrap/cache/',
];

foreach ($permissoes as $nome => $caminho) {
    $full_path = __DIR__ . '/' . $caminho;
    if (is_dir($full_path)) {
        $perm = substr(sprintf('%o', fileperms($full_path)), -4);
        $gravavel = is_writable($full_path);
        $status = $gravavel ? '✓' : '✗';
        $cor = $gravavel ? 'ok' : 'erro';
        echo "<span class='$cor'>$status $nome</span> - Permissões: $perm - " . ($gravavel ? 'Gravável' : 'NÃO gravável') . "\n";
    } else {
        echo "<span class='erro'>✗ $nome</span> - Pasta não existe\n";
    }
}

echo "\n";

echo "========================================\n";
echo "8. PROCURAR ARQUIVOS EM SUBPASTAS\n";
echo "========================================\n";
$procurar = ['composer.json', 'artisan', 'vendor/autoload.php'];
foreach ($procurar as $arquivo) {
    $resultado = shell_exec("find " . escapeshellarg(__DIR__) . " -name " . escapeshellarg(basename($arquivo)) . " -type f 2>/dev/null | head -3");
    if ($resultado) {
        echo "✓ $arquivo encontrado em:\n";
        echo "  " . trim($resultado) . "\n";
    } else {
        echo "✗ $arquivo NÃO encontrado\n";
    }
}

echo "\n";
echo "========================================\n";
echo "FIM DO DIAGNÓSTICO\n";
echo "========================================\n";

?>
</pre>
</body>
</html>
