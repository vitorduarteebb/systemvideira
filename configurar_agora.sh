#!/bin/bash

echo "=========================================="
echo "  CONFIGURAÇÃO RÁPIDA"
echo "=========================================="
echo ""

cd ~/domains/yellow-spoonbill-121332.hostingersite.com/public_html

# 1. Baixar composer.phar se não existir
echo "1. Verificando composer.phar..."
if [ ! -f "composer.phar" ]; then
    echo "   Baixando composer.phar..."
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php composer-setup.php --install-dir=. --filename=composer.phar
    rm -f composer-setup.php
    echo "   ✓ composer.phar baixado"
else
    echo "   ✓ composer.phar já existe"
fi
echo ""

# 2. Criar .env
echo "2. Criando .env..."
cat > .env << 'EOF'
APP_NAME="Sistema VIDEIRA"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yellow-spoonbill-121332.hostingersite.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u494944867_videiradb
DB_USERNAME=u494944867_videira
DB_PASSWORD=Blade1411@20

SESSION_DRIVER=file
SESSION_LIFETIME=120
EOF
echo "   ✓ .env criado"
echo ""

# 3. Instalar dependências
echo "3. Instalando dependências..."
php composer.phar install --no-dev --optimize-autoloader
if [ $? -eq 0 ]; then
    echo "   ✓ Dependências instaladas"
else
    echo "   ✗ Erro ao instalar dependências"
    exit 1
fi
echo ""

# 4. Verificar vendor
if [ ! -f "vendor/autoload.php" ]; then
    echo "   ✗ ERRO: vendor/autoload.php não foi criado!"
    exit 1
fi
echo "   ✓ vendor/autoload.php existe"
echo ""

# 5. Gerar APP_KEY
echo "4. Gerando APP_KEY..."
php artisan key:generate --force
echo "   ✓ APP_KEY gerado"
echo ""

# 6. Criar estrutura storage
echo "5. Criando estrutura de storage..."
mkdir -p storage/logs storage/framework/{cache,sessions,views} bootstrap/cache
chmod -R 755 storage bootstrap/cache
touch storage/logs/laravel.log
chmod 666 storage/logs/laravel.log
echo "   ✓ Estrutura criada"
echo ""

# 7. Migrations
echo "6. Executando migrations..."
php artisan migrate --force
echo ""

# 8. Seeders
echo "7. Populando banco..."
php artisan db:seed --force
echo ""

# 9. Limpar cache
echo "8. Limpando cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
echo "   ✓ Cache limpo"
echo ""

echo "=========================================="
echo "  CONCLUÍDO!"
echo "=========================================="
echo ""
echo "Teste: curl -I https://yellow-spoonbill-121332.hostingersite.com"
echo ""
