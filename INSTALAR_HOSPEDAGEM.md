# 🚀 Instalação Completa na Hospedagem

## ❌ Problemas Encontrados

1. ✗ Arquivo `.env` não existe
2. ✗ `vendor/autoload.php` não existe (dependências não instaladas)

## ✅ Solução Completa

### Opção 1: Via SSH (Recomendado)

Execute estes comandos na ordem:

```bash
cd ~/public_html

# 1. Criar .env
php criar_env.php
# OU criar manualmente:
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

# 2. Instalar dependências
composer install --no-dev --optimize-autoloader

# 3. Gerar APP_KEY
php artisan key:generate

# 4. Limpar cache
php artisan config:clear
php artisan cache:clear

# 5. Verificar
ls -la vendor/autoload.php
grep APP_KEY .env
```

### Opção 2: Via FTP + SSH

1. **Faça upload do arquivo `criar_env.php`**
2. **Via SSH, execute:**
   ```bash
   php criar_env.php
   composer install --no-dev --optimize-autoloader
   php artisan key:generate
   php artisan config:clear
   ```

### Opção 3: Se Composer Não Funcionar

1. **Instale localmente no seu computador:**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

2. **Faça upload da pasta `vendor/` completa via FTP**

3. **Crie o `.env` manualmente via FTP ou SSH**

---

## 📋 Checklist Final

Após executar os comandos, verifique:

```bash
# 1. .env existe?
ls -la .env

# 2. APP_KEY preenchido?
grep APP_KEY .env

# 3. vendor existe?
ls -la vendor/autoload.php

# 4. Testar
curl -I https://yellow-spoonbill-121332.hostingersite.com
```

---

## 🎯 Comandos Rápidos (Copie Tudo)

```bash
cd ~/public_html && \
php criar_env.php && \
composer install --no-dev --optimize-autoloader && \
php artisan key:generate && \
php artisan config:clear && \
php artisan cache:clear && \
echo "✓ Instalação completa!"
```

---

## ✅ Após Instalar

Teste acessando:
- `https://yellow-spoonbill-121332.hostingersite.com/debug.php`
- `https://yellow-spoonbill-121332.hostingersite.com`

O sistema deve funcionar!
