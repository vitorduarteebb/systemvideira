<?php
// criar_config_view.php
// Script para criar config/view.php no servidor

$configViewPath = __DIR__ . '/config/view.php';

if (file_exists($configViewPath)) {
    echo "✓ config/view.php já existe\n";
    echo "Conteúdo atual:\n";
    echo file_get_contents($configViewPath);
    exit;
}

echo "Criando config/view.php...\n";

$content = <<<'PHP'
<?php

return [
    'paths' => [
        resource_path('views'),
    ],
    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views'))
    ),
];
PHP;

// Garantir que o diretório config existe
$configDir = __DIR__ . '/config';
if (!is_dir($configDir)) {
    mkdir($configDir, 0755, true);
    echo "✓ Diretório config/ criado\n";
}

file_put_contents($configViewPath, $content);
chmod($configViewPath, 0644);

echo "✓ config/view.php criado com sucesso!\n";
echo "Conteúdo:\n";
echo $content . "\n";
