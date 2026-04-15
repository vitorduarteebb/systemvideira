<?php
// atualizar_app_service_provider.php
// Script para atualizar AppServiceProvider no servidor

echo "=== Atualizando AppServiceProvider ===\n\n";

$appServiceProviderPath = __DIR__ . '/app/Providers/AppServiceProvider.php';

$newContent = <<<'PHP'
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Registrar binding para 'files' que o ViewServiceProvider precisa
        $this->app->singleton('files', function () {
            return new \Illuminate\Filesystem\Filesystem();
        });
        
        // Registrar binding para 'cookie' que o EncryptCookies middleware precisa
        if (!$this->app->bound('cookie')) {
            $this->app->singleton('cookie', function ($app) {
                return new \Illuminate\Cookie\CookieJar();
            });
        }
    }

    public function boot(): void
    {
        //
    }
}
PHP;

// Garantir que o diretório existe
$dir = dirname($appServiceProviderPath);
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
    echo "✓ Diretório criado: $dir\n";
}

file_put_contents($appServiceProviderPath, $newContent);
chmod($appServiceProviderPath, 0644);

echo "✓ AppServiceProvider atualizado com sucesso!\n\n";

// Limpar cache
echo "Limpando cache...\n";
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
