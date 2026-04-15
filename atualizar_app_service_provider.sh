#!/bin/bash
# atualizar_app_service_provider.sh
# Script para atualizar AppServiceProvider no servidor

cd ~/domains/yellow-spoonbill-121332.hostingersite.com/public_html

cat > app/Providers/AppServiceProvider.php << 'EOF'
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
EOF

echo "✓ AppServiceProvider atualizado!"
echo "Limpando cache..."
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/*
php artisan config:clear
php artisan cache:clear
echo "✓ Cache limpo!"
echo "Teste: curl -I https://yellow-spoonbill-121332.hostingersite.com"
