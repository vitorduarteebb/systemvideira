<?php
// criar_controller_base.php
// Script para criar a classe Controller base

echo "=== Criando Controller base ===\n\n";

$controllerPath = __DIR__ . '/app/Http/Controllers/Controller.php';

if (file_exists($controllerPath)) {
    echo "✓ Controller.php já existe\n";
    echo "Conteúdo atual:\n";
    echo file_get_contents($controllerPath);
    exit;
}

echo "Criando Controller.php...\n";

$content = <<<'PHP'
<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
PHP;

// Garantir que o diretório existe
$dir = dirname($controllerPath);
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
    echo "✓ Diretório criado: $dir\n";
}

file_put_contents($controllerPath, $content);
chmod($controllerPath, 0644);

echo "✓ Controller.php criado com sucesso!\n";
echo "Conteúdo:\n";
echo $content . "\n";
