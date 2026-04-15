<?php
// corrigir_encrypter.php
// Script para corrigir o erro "Target class [encrypter] does not exist"

echo "=== Corrigindo erro do Encrypter ===\n\n";

$configAppPath = __DIR__ . '/config/app.php';

if (!file_exists($configAppPath)) {
    die("ERRO: config/app.php não encontrado!\n");
}

echo "1. Verificando config/app.php...\n";
$content = file_get_contents($configAppPath);

// Verificar se EncryptionServiceProvider já está presente
if (strpos($content, 'Illuminate\\Encryption\\EncryptionServiceProvider::class') !== false) {
    echo "✓ EncryptionServiceProvider já está registrado\n";
} else {
    echo "✗ EncryptionServiceProvider NÃO está registrado\n";
    echo "Adicionando EncryptionServiceProvider...\n";
    
    // Adicionar após DatabaseServiceProvider
    $pattern = '/(Illuminate\\Database\\DatabaseServiceProvider::class,)/';
    $replacement = "$1\n        Illuminate\\Encryption\\EncryptionServiceProvider::class,";
    
    $newContent = preg_replace($pattern, $replacement, $content);
    
    if ($newContent !== $content) {
        file_put_contents($configAppPath, $newContent);
        echo "✓ EncryptionServiceProvider adicionado com sucesso!\n";
    } else {
        echo "✗ ERRO: Não foi possível adicionar o provider automaticamente\n";
        echo "Adicione manualmente após DatabaseServiceProvider:\n";
        echo "Illuminate\\Encryption\\EncryptionServiceProvider::class,\n";
    }
}

// Verificar se config/view.php existe
echo "\n2. Verificando config/view.php...\n";
$configViewPath = __DIR__ . '/config/view.php';

if (file_exists($configViewPath)) {
    echo "✓ config/view.php existe\n";
} else {
    echo "✗ config/view.php NÃO existe\n";
    echo "Criando config/view.php...\n";
    
    $viewConfig = <<<'PHP'
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
    
    file_put_contents($configViewPath, $viewConfig);
    chmod($configViewPath, 0644);
    echo "✓ config/view.php criado!\n";
}

// Limpar cache
echo "\n3. Limpando cache...\n";
$cacheDirs = [
    __DIR__ . '/bootstrap/cache',
    __DIR__ . '/storage/framework/cache',
];

foreach ($cacheDirs as $dir) {
    if (is_dir($dir)) {
        $files = glob($dir . '/*.php');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        echo "✓ Cache limpo em: $dir\n";
    }
}

echo "\n=== CONCLUÍDO! ===\n";
echo "Execute agora:\n";
echo "  php artisan config:clear\n";
echo "  php artisan cache:clear\n";
echo "  curl -I https://yellow-spoonbill-121332.hostingersite.com\n";
