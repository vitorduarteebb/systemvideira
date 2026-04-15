# ⚡ Instruções Rápidas - Configuração do Banco

## ✅ Banco de Dados Criado

As credenciais já estão configuradas:

- **Banco:** `u494944867_videiradb`
- **Usuário:** `u494944867_videira`
- **Senha:** `Blade1411@20`

## 📋 Passos para Configurar

### 1. Criar arquivo .env

Na raiz do projeto (mesma pasta do `index.php`), crie um arquivo chamado `.env` e cole o conteúdo do arquivo `env_config.txt` (ou copie abaixo):

```env
APP_NAME="Sistema VIDEIRA"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://seudominio.com.br

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u494944867_videiradb
DB_USERNAME=u494944867_videira
DB_PASSWORD=Blade1411@20

SESSION_DRIVER=file
SESSION_LIFETIME=120
```

**⚠️ Lembre-se de:**
- Substituir `https://seudominio.com.br` pelo seu domínio real
- Se `127.0.0.1` não funcionar, tente `localhost`

### 2. Gerar Chave da Aplicação

Via SSH ou Terminal do cPanel:

```bash
cd public_html
php artisan key:generate
```

Isso vai preencher o `APP_KEY` automaticamente.

### 3. Executar Migrations

```bash
php artisan migrate
```

### 4. Popular Banco de Dados

```bash
php artisan db:seed
```

Isso criará:
- Usuário admin: `admin@videira.com` / `admin123`

### 5. Limpar Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### 6. Testar

Acesse seu domínio no navegador e faça login com:
- **Email:** `admin@videira.com`
- **Senha:** `admin123`

## 🔧 Verificar Host do Banco

Se der erro de conexão, verifique o host:

1. Acesse o **phpMyAdmin** no cPanel
2. Veja qual é o host exibido (pode ser `localhost` ou outro)
3. Atualize o `DB_HOST` no arquivo `.env`

## ✅ Pronto!

Seu sistema está configurado e pronto para uso!
