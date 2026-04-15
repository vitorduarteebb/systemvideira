# 📋 Guia de Instalação - Sistema VIDEIRA

## Passo a Passo Completo

### 1. Pré-requisitos
Certifique-se de ter instalado:
- ✅ PHP 8.1 ou superior
- ✅ Composer
- ✅ MySQL/MariaDB
- ✅ Node.js 16+ e NPM
- ✅ phpMyAdmin (ou acesso ao MySQL)

### 2. Instalar Dependências

#### Dependências PHP (Composer)
```bash
composer install
```

#### Dependências JavaScript (NPM)
```bash
npm install
```

### 3. Configurar Ambiente

#### Copiar arquivo de ambiente
```bash
cp .env.example .env
```

#### Gerar chave da aplicação
```bash
php artisan key:generate
```

### 4. Configurar Banco de Dados

#### Opção 1: Via phpMyAdmin (Recomendado)
1. Acesse o phpMyAdmin no navegador
2. Clique em "Novo" para criar um novo banco de dados
3. Nome do banco: `videira_db`
4. Collation: `utf8mb4_unicode_ci`
5. Clique em "Criar"

#### Opção 2: Via SQL
Execute o arquivo `database/videira_db.sql` no phpMyAdmin ou MySQL:
```sql
CREATE DATABASE videira_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### Configurar conexão no .env
Edite o arquivo `.env` e configure:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=videira_db
DB_USERNAME=root
DB_PASSWORD=sua_senha_aqui
```

### 5. Executar Migrations

```bash
php artisan migrate
```

Isso criará todas as tabelas necessárias:
- `users` - Usuários do sistema
- `financial_transactions` - Transações financeiras
- `tecnicos` - Equipe técnica

### 6. Popular Banco de Dados (Opcional)

Para criar dados de exemplo:
```bash
php artisan db:seed
```

Isso criará:
- Usuário administrador: `admin@videira.com` / `admin123`
- 2 técnicos de exemplo

### 7. Compilar Assets

#### Desenvolvimento (com hot reload)
```bash
npm run dev
```

#### Produção
```bash
npm run build
```

### 8. Iniciar Servidor

```bash
php artisan serve
```

O sistema estará disponível em: `http://localhost:8000`

### 9. Acessar o Sistema

1. Abra o navegador em `http://localhost:8000`
2. Faça login com:
   - **Email:** `admin@videira.com`
   - **Senha:** `admin123`

## 🔧 Solução de Problemas

### Erro: "Class 'PDO' not found"
Instale a extensão PDO do PHP:
```bash
# Ubuntu/Debian
sudo apt-get install php-mysql

# Windows (XAMPP/WAMP)
# A extensão já vem instalada
```

### Erro: "SQLSTATE[HY000] [1045] Access denied"
Verifique as credenciais do banco de dados no arquivo `.env`

### Erro: "Vite manifest not found"
Execute:
```bash
npm run build
```

### Erro: "Route [login] not defined"
Limpe o cache:
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

## 📝 Notas Importantes

- **Estrutura para Hospedagem Compartilhada:** O sistema foi configurado para hospedagem compartilhada. Os arquivos públicos estão na raiz do projeto (compatível com `public_html`)
- O sistema usa sessões, então certifique-se de que a pasta `storage/framework/sessions` tem permissão de escrita
- Para produção, altere `APP_DEBUG=false` no arquivo `.env`
- A senha padrão do administrador deve ser alterada após o primeiro acesso

## 🌐 Deploy em Hospedagem Compartilhada

### Estrutura de Pastas
O sistema está configurado para funcionar diretamente na pasta `public_html` da hospedagem:

```
public_html/          (ou htdocs/)
├── index.php         (arquivo principal)
├── .htaccess         (configuração Apache)
├── build/            (assets compilados - criado após npm run build)
├── app/
├── bootstrap/
├── config/
├── database/
├── resources/
├── routes/
├── storage/
└── vendor/
```

### Passos para Deploy

1. **Fazer upload de todos os arquivos** para `public_html/`
2. **Configurar permissões:**
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```
3. **Configurar `.env`** com as credenciais do banco de dados da hospedagem
4. **Executar migrations:**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```
5. **Compilar assets:**
   ```bash
   npm run build
   ```
6. **Limpar cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   ```

## 🎨 Personalização

Para personalizar o sistema:
- **Cores e estilos:** Edite `resources/views/auth/login.blade.php` e `resources/views/dashboard.blade.php`
- **Lógica de negócio:** Edite os controllers em `app/Http/Controllers/`
- **Banco de dados:** Adicione novas migrations em `database/migrations/`
