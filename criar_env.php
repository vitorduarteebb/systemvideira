<?php
/**
 * Script para criar arquivo .env automaticamente
 * Execute: php criar_env.php
 */

$env_content = <<<'EOF'
APP_NAME="Sistema VIDEIRA"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://videiraengenharia.com.br

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=videiradb.mysql.dbaas.com.br
DB_PORT=3306
DB_DATABASE=videiradb
DB_USERNAME=videiradb
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
EOF;

if (file_exists('.env')) {
    echo "⚠ Arquivo .env já existe!\n";
    echo "Deseja sobrescrever? (s/n): ";
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    if (trim($line) !== 's') {
        echo "Cancelado.\n";
        exit;
    }
}

file_put_contents('.env', $env_content);
echo "✓ Arquivo .env criado com sucesso!\n\n";

echo "Próximos passos:\n";
echo "1. Gerar APP_KEY: php artisan key:generate\n";
echo "2. Instalar dependências: composer install --no-dev --optimize-autoloader\n";
echo "3. Limpar cache: php artisan config:clear\n";
