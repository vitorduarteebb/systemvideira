#!/bin/bash

echo "=========================================="
echo "  CORRIGINDO ERRO 'Target class [files]'"
echo "=========================================="
echo ""

cd ~/domains/yellow-spoonbill-121332.hostingersite.com/public_html

# 1. Limpar TODOS os caches manualmente
echo "1. Limpando caches manualmente..."
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/*
rm -rf storage/framework/views/*
rm -rf storage/framework/sessions/*
echo "   ✓ Caches removidos"
echo ""

# 2. Gerar APP_KEY manualmente (sem usar artisan)
echo "2. Gerando APP_KEY manualmente..."
APP_KEY=$(php -r "echo 'base64:'.base64_encode(random_bytes(32));")
sed -i "s/APP_KEY=.*/APP_KEY=$APP_KEY/" .env
echo "   ✓ APP_KEY gerado: $APP_KEY"
echo ""

# 3. Verificar .env
echo "3. Verificando .env..."
grep APP_KEY .env
echo ""

# 4. Criar estrutura de storage
echo "4. Criando estrutura de storage..."
mkdir -p storage/logs
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache
chmod -R 755 storage bootstrap/cache
touch storage/logs/laravel.log
chmod 666 storage/logs/laravel.log
echo "   ✓ Estrutura criada"
echo ""

# 5. Testar se artisan funciona agora
echo "5. Testando artisan..."
php artisan --version 2>&1 | head -5
echo ""

echo "=========================================="
echo "  TENTE AGORA:"
echo "=========================================="
echo ""
echo "php artisan migrate --force"
echo "php artisan db:seed --force"
echo ""
