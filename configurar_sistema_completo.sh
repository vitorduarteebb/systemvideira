#!/bin/bash

echo "=========================================="
echo "  CONFIGURAÇÃO COMPLETA DO SISTEMA"
echo "=========================================="
echo ""

cd ~/domains/yellow-spoonbill-121332.hostingersite.com/public_html

# 1. Verificar arquivos essenciais
echo "1. Verificando arquivos essenciais..."
if [ ! -f "composer.json" ]; then
    echo "   ✗ composer.json não encontrado!"
    exit 1
fi
if [ ! -f "artisan" ]; then
    echo "   ✗ artisan não encontrado!"
    exit 1
fi
echo "   ✓ Arquivos essenciais OK"
echo ""

# 2. Verificar se vendor existe
echo "2. Verificando vendor/..."
if [ ! -d "vendor" ]; then
    echo "   ⚠ vendor/ não existe - será criado"
else
    echo "   ✓ vendor/ já existe"
fi
echo ""

# 3. Instalar dependências
echo "3. Instalando dependências do Composer..."
if [ -f "composer.phar" ]; then
    php composer.phar install --no-dev --optimize-autoloader
else
    echo "   ✗ composer.phar não encontrado!"
    echo "   Baixando composer.phar..."
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php composer-setup.php --install-dir=. --filename=composer.phar
    php composer.phar install --no-dev --optimize-autoloader
fi

if [ ! -f "vendor/autoload.php" ]; then
    echo "   ✗ ERRO: vendor/autoload.php não foi criado!"
    exit 1
fi
echo "   ✓ Dependências instaladas"
echo ""

# 4. Verificar .env
echo "4. Verificando .env..."
if [ ! -f ".env" ]; then
    echo "   ⚠ .env não existe - criando..."
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
else
    echo "   ✓ .env existe"
fi
echo ""

# 5. Gerar APP_KEY
echo "5. Gerando APP_KEY..."
if grep -q "APP_KEY=$" .env || ! grep -q "APP_KEY=" .env; then
    php artisan key:generate --force
    echo "   ✓ APP_KEY gerado"
else
    echo "   ✓ APP_KEY já configurado"
fi
echo ""

# 6. Criar estrutura de storage
echo "6. Criando estrutura de storage..."
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache
chmod -R 755 storage
chmod -R 755 bootstrap/cache
touch storage/logs/laravel.log
chmod 666 storage/logs/laravel.log
echo "   ✓ Estrutura criada"
echo ""

# 7. Executar migrations
echo "7. Executando migrations..."
php artisan migrate --force
if [ $? -eq 0 ]; then
    echo "   ✓ Migrations executadas"
else
    echo "   ⚠ Erro nas migrations (pode ser normal se já foram executadas)"
fi
echo ""

# 8. Popular banco de dados
echo "8. Populando banco de dados..."
php artisan db:seed --force
if [ $? -eq 0 ]; then
    echo "   ✓ Banco populado"
else
    echo "   ⚠ Erro ao popular (pode ser normal se já foi populado)"
fi
echo ""

# 9. Limpar cache
echo "9. Limpando cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo "   ✓ Cache limpo"
echo ""

# 10. Verificação final
echo "=========================================="
echo "  VERIFICAÇÃO FINAL"
echo "=========================================="
echo ""

echo "Arquivos essenciais:"
[ -f "vendor/autoload.php" ] && echo "   ✓ vendor/autoload.php" || echo "   ✗ vendor/autoload.php"
[ -f "bootstrap/app.php" ] && echo "   ✓ bootstrap/app.php" || echo "   ✗ bootstrap/app.php"
[ -f "app/Http/Kernel.php" ] && echo "   ✓ app/Http/Kernel.php" || echo "   ✗ app/Http/Kernel.php"
[ -f ".env" ] && echo "   ✓ .env" || echo "   ✗ .env"

echo ""
echo "APP_KEY:"
grep "APP_KEY=" .env | head -1

echo ""
echo "Permissões:"
ls -ld storage bootstrap/cache | awk '{print "   " $1 " " $9}'

echo ""
echo "=========================================="
echo "  CONFIGURAÇÃO CONCLUÍDA!"
echo "=========================================="
echo ""
echo "Acesse: https://yellow-spoonbill-121332.hostingersite.com"
echo "Login: admin@videira.com"
echo "Senha: admin123"
echo ""
