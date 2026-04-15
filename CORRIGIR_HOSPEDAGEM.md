# 🔧 Corrigir Sistema na Hospedagem

## ❌ Problemas Identificados

1. ✗ Arquivo `.env` não existe
2. ✗ `vendor/autoload.php` não existe (dependências não instaladas)

## ✅ Solução Passo a Passo

### 1. Criar Arquivo .env

Via SSH, execute:

```bash
cd ~/public_html

# Criar arquivo .env
cat > .env << 'EOF'
APP_NAME="Sistema VIDEIRA"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yellow-spoonbill-121332.hostingersite.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u494944867_videiradb
DB_USERNAME=u494944867_videira
DB_PASSWORD=Blade1411@20

SESSION_DRIVER=file
SESSION_LIFETIME=120

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
EOF

# Verificar se foi criado
cat .env | head -5
```

### 2. Instalar Dependências do Composer

```bash
# Verificar se Composer está disponível
composer --version

# Instalar dependências
composer install --no-dev --optimize-autoloader
```

**Se Composer não estiver disponível:**
```bash
# Baixar Composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"

# Instalar dependências
php composer.phar install --no-dev --optimize-autoloader
```

### 3. Gerar APP_KEY

```bash
php artisan key:generate
```

### 4. Verificar Instalação

```bash
# Verificar vendor
ls -la vendor/autoload.php

# Verificar .env
grep APP_KEY .env

# Se APP_KEY ainda estiver vazio, gerar manualmente:
php -r "echo 'APP_KEY=base64:' . base64_encode(random_bytes(32)) . PHP_EOL;" >> temp_key.txt
# Copiar a chave gerada e editar .env
nano .env
# Substitua APP_KEY= por APP_KEY=base64:CHAVE_GERADA
```

### 5. Limpar Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### 6. Testar

```bash
# Testar via curl
curl -I https://yellow-spoonbill-121332.hostingersite.com

# Ou acessar debug.php novamente
# https://yellow-spoonbill-121332.hostingersite.com/debug.php
```

---

## 📋 Comandos Completos (Copie e Cole)

```bash
cd ~/public_html

# 1. Criar .env
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

# 3. Gerar chave
php artisan key:generate

# 4. Limpar cache
php artisan config:clear
php artisan cache:clear

# 5. Verificar
ls -la vendor/autoload.php
grep APP_KEY .env
```

---

## ⚠️ Se Composer Não Funcionar

Se `composer install` não funcionar, você precisa:

1. **Instalar localmente** no seu computador:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

2. **Fazer upload da pasta `vendor/`** completa via FTP

3. **Verificar se `vendor/autoload.php` existe** após upload

---

## ✅ Após Corrigir

Teste novamente acessando:
- `https://yellow-spoonbill-121332.hostingersite.com/debug.php`
- `https://yellow-spoonbill-121332.hostingersite.com`

O sistema deve funcionar!
