# Sistema VIDEIRA - Portal Administrativo

Sistema administrativo desenvolvido em Laravel para a empresa VIDEIRA (Refrigeração & Climatização).

## 🚀 Instalação

### Pré-requisitos
- PHP 8.1 ou superior
- Composer
- MySQL/MariaDB
- Node.js e NPM

### Passos para instalação

1. **Instalar dependências do PHP:**
```bash
composer install
```

2. **Instalar dependências do Node:**
```bash
npm install
```

3. **Configurar ambiente:**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurar banco de dados no arquivo `.env`:**
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=videira_db
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

5. **Criar banco de dados no phpMyAdmin:**
   - Acesse phpMyAdmin
   - Crie um novo banco de dados chamado `videira_db`
   - Ou execute: `CREATE DATABASE videira_db;`

6. **Executar migrations:**
```bash
php artisan migrate
```

7. **Popular banco de dados com dados iniciais:**
```bash
php artisan db:seed
```

8. **Compilar assets:**
```bash
npm run dev
```

9. **Iniciar servidor:**
```bash
php artisan serve
```

## 🔐 Credenciais Padrão

Após executar o seeder, você pode fazer login com:
- **Email:** admin@videira.com
- **Senha:** admin123

## 📁 Estrutura do Projeto

- `app/Http/Controllers/` - Controllers da aplicação
- `app/Models/` - Models Eloquent
- `database/migrations/` - Migrations do banco de dados
- `resources/views/` - Views Blade
- `routes/web.php` - Rotas da aplicação

## 🎨 Funcionalidades

- ✅ Sistema de autenticação
- ✅ Dashboard com métricas financeiras
- ✅ Gráficos de receitas e despesas
- ✅ Gestão de equipe técnica
- ✅ Interface moderna e responsiva

## 📊 Banco de Dados

O sistema utiliza as seguintes tabelas:
- `users` - Usuários do sistema
- `financial_transactions` - Transações financeiras
- `tecnicos` - Equipe técnica

## 🛠️ Tecnologias Utilizadas

- Laravel 10
- MySQL
- Chart.js
- CSS3 com animações
- Blade Templates

## 🌐 Hospedagem Compartilhada

Este sistema foi **configurado especialmente para hospedagem compartilhada**:
- ✅ Estrutura sem pasta `public` (compatível com `public_html`)
- ✅ Arquivos públicos na raiz do projeto
- ✅ Configuração otimizada para cPanel/hospedagens compartilhadas

**📖 Veja o guia completo:** [HOSPEDAGEM.md](HOSPEDAGEM.md)
