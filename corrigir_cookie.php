<?php
// corrigir_cookie.php
// Script para corrigir o erro "Target class [cookie] does not exist"

echo "=== Corrigindo erro do Cookie ===\n\n";

$appServiceProviderPath = __DIR__ . '/app/Providers/AppServiceProvider.php';

if (!file_exists($appServiceProviderPath)) {
    die("ERRO: app/Providers/AppServiceProvider.php não encontrado!\n");
}

echo "1. Verificando AppServiceProvider...\n";
$content = file_get_contents($appServiceProviderPath);

// Verificar se o binding 'cookie' já está presente
if (strpos($content, "'cookie'") !== false && strpos($content, 'CookieJar') !== false) {
    echo "✓ Binding 'cookie' já está registrado\n";
} else {
    echo "✗ Binding 'cookie' NÃO está registrado\n";
    echo "Adicionando binding 'cookie'...\n";
    
    // Adicionar após o binding 'files'
    $pattern = '/(\$this->app->singleton\(\'files\', function \(\) \{[\s\S]*?\}\);\s*\})/';
    $replacement = "$1\n        \n        // Registrar binding para 'cookie' que o EncryptCookies middleware precisa\n        if (!\$this->app->bound('cookie')) {\n            \$this->app->singleton('cookie', function (\$app) {\n                return new \\Illuminate\\Cookie\\CookieJar();\n            });\n        }";
    
    $newContent = preg_replace($pattern, $replacement, $content);
    
    if ($newContent !== $content) {
        file_put_contents($appServiceProviderPath, $newContent);
        echo "✓ Binding 'cookie' adicionado com sucesso!\n";
    } else {
        // Tentar método alternativo
        $pattern = '/public function register\(\): void\s*\{([\s\S]*?)\s*public function boot/';
        $replacement = "public function register(): void\n    {\n\$1        \n        // Registrar binding para 'cookie' que o EncryptCookies middleware precisa\n        if (!\$this->app->bound('cookie')) {\n            \$this->app->singleton('cookie', function (\$app) {\n                return new \\Illuminate\\Cookie\\CookieJar();\n            });\n        }\n    }\n\n    public function boot";
        
        $newContent = preg_replace($pattern, $replacement, $content);
        
        if ($newContent !== $content) {
            file_put_contents($appServiceProviderPath, $newContent);
            echo "✓ Binding 'cookie' adicionado com sucesso (método alternativo)!\n";
        } else {
            echo "✗ ERRO: Não foi possível adicionar o binding automaticamente\n";
            echo "Adicione manualmente no método register() do AppServiceProvider:\n\n";
            echo "if (!\$this->app->bound('cookie')) {\n";
            echo "    \$this->app->singleton('cookie', function (\$app) {\n";
            echo "        return new \\Illuminate\\Cookie\\CookieJar();\n";
            echo "    });\n";
            echo "}\n";
        }
    }
}

// Limpar cache
echo "\n2. Limpando cache...\n";
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
