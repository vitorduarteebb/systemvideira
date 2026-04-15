# 📤 Upload Completo para Hospedagem

## ⚠️ Problema Identificado

Os arquivos do Laravel **não foram enviados** para a hospedagem. Você está no diretório certo (`public_html`), mas os arquivos do projeto não estão lá.

## ✅ Solução: Fazer Upload de TODOS os Arquivos

### Arquivos que DEVEM ser enviados:

```
public_html/
├── index.php              ← ESSENCIAL
├── .htaccess             ← ESSENCIAL
├── artisan               ← ESSENCIAL
├── composer.json         ← ESSENCIAL
├── .env                  ← Criar na hospedagem
├── app/                  ← TODA a pasta
├── bootstrap/            ← TODA a pasta
├── config/               ← TODA a pasta
├── database/             ← TODA a pasta
├── resources/            ← TODA a pasta
├── routes/               ← TODA a pasta
├── storage/              ← TODA a pasta
├── vendor/               ← Será criado após composer install
└── package.json          ← Opcional (para assets)
```

### Arquivos que NÃO devem ser enviados:

- `node_modules/`
- `.git/`
- `.env.example` (use o `.env` criado)
- Arquivos de teste/debug

---

## 📋 Passo a Passo Completo

### 1. Preparar Arquivos Localmente

No seu computador, na pasta do projeto:

```bash
# Verificar se composer.json existe
ls -la composer.json

# Instalar dependências localmente (opcional, mas recomendado)
composer install --no-dev --optimize-autoloader
```

### 2. Fazer Upload via FTP/SFTP

**Opção A: Upload Manual**
1. Conecte via FTP (FileZilla, WinSCP, etc.)
2. Navegue até `public_html/`
3. Faça upload de **TODAS** as pastas e arquivos:
   - `app/`
   - `bootstrap/`
   - `config/`
   - `database/`
   - `resources/`
   - `routes/`
   - `storage/`
   - `index.php`
   - `.htaccess`
   - `artisan`
   - `composer.json`
   - `package.json`
   - `vite.config.js`

**Opção B: Upload via ZIP**
1. Compacte todos os arquivos (exceto `node_modules`, `.git`)
2. Faça upload do ZIP
3. Extraia no `public_html/`

### 3. Após Upload, via SSH

```bash
cd ~/public_html

# 1. Verificar se arquivos foram enviados
ls -la composer.json artisan index.php

# 2. Criar .env (se ainda não criou)
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

# 3. Instalar dependências
composer install --no-dev --optimize-autoloader
# OU se não funcionar:
php composer.phar install --no-dev --optimize-autoloader

# 4. Gerar APP_KEY
php artisan key:generate

# 5. Corrigir permissões
chmod -R 755 storage bootstrap/cache
chmod 666 storage/logs/laravel.log

# 6. Limpar cache
php artisan config:clear
php artisan cache:clear

# 7. Verificar
ls -la vendor/autoload.php
grep APP_KEY .env
```

---

## 🔍 Verificação Rápida

Após upload, execute:

```bash
cd ~/public_html

# Verificar arquivos essenciais
ls -la composer.json artisan index.php .htaccess

# Se algum não existir, o upload está incompleto!
```

---

## ⚠️ Se Composer Não Funcionar na Hospedagem

1. **Instale localmente** no seu computador:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

2. **Faça upload da pasta `vendor/` completa** via FTP

3. **Verifique se `vendor/autoload.php` existe** após upload

---

## ✅ Checklist de Upload

- [ ] `index.php` enviado
- [ ] `.htaccess` enviado
- [ ] `artisan` enviado
- [ ] `composer.json` enviado
- [ ] Pasta `app/` enviada
- [ ] Pasta `bootstrap/` enviada
- [ ] Pasta `config/` enviada
- [ ] Pasta `database/` enviada
- [ ] Pasta `resources/` enviada
- [ ] Pasta `routes/` enviada
- [ ] Pasta `storage/` enviada
- [ ] `.env` criado na hospedagem
- [ ] `vendor/` instalado ou enviado

---

**IMPORTANTE:** Faça upload de TODOS os arquivos do projeto para `public_html/` antes de executar os comandos!
