#!/bin/bash

echo "=========================================="
echo "  DIAGNÓSTICO COMPLETO DO SERVIDOR"
echo "=========================================="
echo ""

# Verificar diretório atual
echo "1. Diretório atual:"
pwd
echo ""

# Verificar se estamos em public_html
echo "2. Verificando localização:"
if [[ "$PWD" == *"public_html"* ]]; then
    echo "   ✓ Estamos em public_html"
else
    echo "   ✗ NÃO estamos em public_html"
    echo "   Execute: cd ~/public_html"
fi
echo ""

# Listar TODOS os arquivos e pastas
echo "3. Listando TODOS os arquivos/pastas na raiz:"
ls -la
echo ""

# Procurar composer.json em qualquer lugar
echo "4. Procurando composer.json:"
find . -name "composer.json" -type f 2>/dev/null | head -5
echo ""

# Procurar artisan em qualquer lugar
echo "5. Procurando artisan:"
find . -name "artisan" -type f 2>/dev/null | head -5
echo ""

# Verificar estrutura de pastas
echo "6. Estrutura de pastas principais:"
for dir in app bootstrap config database resources routes storage; do
    if [ -d "$dir" ]; then
        echo "   ✓ $dir/ existe"
    else
        echo "   ✗ $dir/ NÃO existe"
    fi
done
echo ""

# Verificar index.php
echo "7. Verificando index.php:"
if [ -f "index.php" ]; then
    echo "   ✓ index.php existe"
    echo "   Primeiras linhas:"
    head -5 index.php | sed 's/^/      /'
else
    echo "   ✗ index.php NÃO existe"
fi
echo ""

# Verificar .htaccess
echo "8. Verificando .htaccess:"
if [ -f ".htaccess" ]; then
    echo "   ✓ .htaccess existe"
else
    echo "   ✗ .htaccess NÃO existe"
fi
echo ""

# Verificar permissões
echo "9. Verificando permissões:"
if [ -f "index.php" ]; then
    ls -l index.php | awk '{print "   index.php: " $1 " " $3 " " $4}'
fi
if [ -f "artisan" ]; then
    ls -l artisan | awk '{print "   artisan: " $1 " " $3 " " $4}'
fi
echo ""

# Verificar se há subpastas que podem conter os arquivos
echo "10. Verificando subpastas:"
for item in */; do
    if [ -d "$item" ]; then
        echo "   Pasta: $item"
        if [ -f "${item}composer.json" ] || [ -f "${item}artisan" ]; then
            echo "      ⚠️ ATENÇÃO: composer.json ou artisan pode estar aqui!"
        fi
    fi
done
echo ""

echo "=========================================="
echo "  FIM DO DIAGNÓSTICO"
echo "=========================================="
