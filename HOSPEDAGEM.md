# 🚀 Guia de Deploy - Hospedagem Compartilhada

## Estrutura Ajustada para Hospedagem

Este sistema foi configurado para funcionar diretamente na pasta `public_html` (ou `htdocs`) da sua hospedagem compartilhada, **sem necessidade da pasta `public`**.

## 📁 Estrutura de Arquivos

```
public_html/              (raiz do domínio)
├── index.php            ← Arquivo principal (substitui public/index.php)
├── .htaccess            ← Configuração Apache
├── build/               ← Assets compilados (criado após npm run build)
├── app/                 ← Aplicação Laravel
├── bootstrap/           ← Bootstrap Laravel
├── config/              ← Configurações
├── database/            ← Migrations e Seeders
├── resources/           ← Views, CSS, JS
├── routes/              ← Rotas
├── storage/             ← Arquivos de sessão, cache, logs
├── vendor/              ← Dependências Composer
├── .env                 ← Configurações de ambiente
└── composer.json        ← Dependências PHP
```

## 📤 Passos para Upload

### 1. Preparar Arquivos Localmente

```bash
# Instalar dependências
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Gerar chave da aplicação (se ainda não tiver)
php artisan key:generate
```

### 2. Upload via FTP/SFTP

Faça upload de **TODOS** os arquivos e pastas para `public_html/`:
- ✅ `index.php`
- ✅ `.htaccess`
- ✅ `app/`
- ✅ `bootstrap/`
- ✅ `config/`
- ✅ `database/`
- ✅ `resources/`
- ✅ `routes/`
- ✅ `storage/`
- ✅ `vendor/`
- ✅ `.env`
- ✅ `composer.json`
- ✅ `artisan`
- ✅ `build/` (pasta criada após `npm run build`)

**⚠️ IMPORTANTE:** Não faça upload da pasta `node_modules/` e `public/` (não existe mais)

### 3. Configurar Permissões

Via FTP ou SSH, configure as permissões:

```bash
# Pasta storage precisa de escrita
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Arquivos principais
chmod 644 index.php
chmod 644 .htaccess
```

### 4. Configurar Banco de Dados

Edite o arquivo `.env` na hospedagem com as seguintes credenciais:

```env
APP_NAME="Sistema VIDEIRA"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seudominio.com.br

DB_CONNECTION=mysql
DB_HOST=127.0.0.1          # Ou localhost (verifique no cPanel)
DB_PORT=3306
DB_DATABASE=u494944867_videiradb
DB_USERNAME=u494944867_videira
DB_PASSWORD=Blade1411@20
```

**⚠️ IMPORTANTE:**
- Substitua `https://seudominio.com.br` pelo seu domínio real
- Se `127.0.0.1` não funcionar, tente `localhost`
- O host correto geralmente aparece no phpMyAdmin do cPanel

### 5. Criar Banco de Dados

1. Acesse o **phpMyAdmin** no cPanel
2. Crie um novo banco de dados: `videira_db`
3. Crie um usuário e senha para o banco
4. Atribua todas as permissões ao usuário no banco

### 6. Executar Migrations

Via **SSH** (se disponível) ou **Terminal do cPanel**:

```bash
cd public_html
php artisan migrate
php artisan db:seed
```

**OU** execute o arquivo SQL diretamente no phpMyAdmin:
- Abra `database/videira_db.sql`
- Execute no banco de dados criado

### 7. Limpar Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 8. Verificar Funcionamento

Acesse seu domínio no navegador:
- `https://seudominio.com.br`

Faça login com:
- **Email:** `admin@videira.com`
- **Senha:** `admin123`

## 🔧 Solução de Problemas

### Erro 500 - Internal Server Error

1. Verifique as permissões das pastas `storage/` e `bootstrap/cache/`
2. Verifique se o arquivo `.env` está configurado corretamente
3. Verifique os logs em `storage/logs/laravel.log`

### Erro: "No application encryption key has been specified"

Execute via SSH:
```bash
php artisan key:generate
```

### Erro: "Vite manifest not found"

Certifique-se de que executou `npm run build` e fez upload da pasta `build/`

### Página em Branco

1. Ative o modo debug temporariamente no `.env`:
   ```env
   APP_DEBUG=true
   ```
2. Verifique os logs em `storage/logs/laravel.log`
3. Verifique se todas as dependências foram instaladas (`vendor/` existe)

### Arquivos CSS/JS não carregam

1. Verifique se a pasta `build/` foi enviada
2. Verifique as permissões da pasta `build/`
3. Execute novamente `npm run build` e faça upload

## 📝 Checklist de Deploy

- [ ] Dependências instaladas (`composer install` e `npm install`)
- [ ] Assets compilados (`npm run build`)
- [ ] Arquivo `.env` configurado
- [ ] Banco de dados criado
- [ ] Migrations executadas
- [ ] Permissões configuradas
- [ ] Cache limpo
- [ ] Teste de acesso realizado
- [ ] Login funcionando

## 🔒 Segurança

Após o deploy, certifique-se de:

1. ✅ Alterar `APP_DEBUG=false` no `.env`
2. ✅ Alterar a senha do administrador
3. ✅ Verificar se o arquivo `.env` não está acessível publicamente
4. ✅ Configurar HTTPS (SSL)
5. ✅ Fazer backup regular do banco de dados

## 📞 Suporte

Se encontrar problemas, verifique:
- Logs em `storage/logs/laravel.log`
- Logs de erro do servidor (cPanel)
- Documentação do Laravel: https://laravel.com/docs
