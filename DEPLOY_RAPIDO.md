# ⚡ Deploy Rápido - Sistema VIDEIRA

## 🎯 Estrutura Ajustada

✅ **SEM pasta `public`** - Tudo funciona diretamente em `public_html/`

## 📋 Passos Rápidos

### 1. Local (antes do upload)
```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
php artisan key:generate
```

### 2. Upload para Hospedagem
Faça upload de **TODOS** os arquivos para `public_html/`:
- ✅ Todos os arquivos e pastas
- ❌ **NÃO** envie: `node_modules/`, `.git/`, `.env.example`

### 3. No Servidor (via SSH ou Terminal cPanel)
```bash
cd public_html

# Configurar permissões
chmod -R 755 storage bootstrap/cache

# Criar banco de dados (ou via phpMyAdmin)
# Depois executar:
php artisan migrate
php artisan db:seed

# Limpar cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### 4. Configurar `.env`
Edite o arquivo `.env` na hospedagem:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seudominio.com.br

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=nome_do_banco
DB_USERNAME=usuario_db
DB_PASSWORD=senha_db
```

### 5. Acessar
- URL: `https://seudominio.com.br`
- Login: `admin@videira.com` / `admin123`

## ⚠️ Importante

- A pasta `build/` (criada após `npm run build`) **DEVE** ser enviada
- Permissões de `storage/` e `bootstrap/cache/` devem ser 755
- O arquivo `.htaccess` está na raiz (não em `public/`)

## 📖 Guia Completo
Veja [HOSPEDAGEM.md](HOSPEDAGEM.md) para instruções detalhadas.
