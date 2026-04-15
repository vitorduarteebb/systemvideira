# 📤 Como Fazer Upload para Hospedagem

## ⚠️ SITUAÇÃO ATUAL

**Apenas o `index.php` está na hospedagem!**

Faltam TODOS os outros arquivos:
- ✗ `composer.json`
- ✗ `artisan`
- ✗ Pasta `app/`
- ✗ Pasta `bootstrap/`
- ✗ Pasta `config/`
- ✗ Pasta `database/`
- ✗ Pasta `resources/`
- ✗ Pasta `routes/`
- ✗ Pasta `storage/`
- ✗ E todas as outras...

## ✅ SOLUÇÃO: Fazer Upload de TODOS os Arquivos

### Método 1: Via FTP (FileZilla, WinSCP, etc.)

1. **Conecte ao servidor FTP:**
   - Host: `ftp.yellow-spoonbill-121332.hostingersite.com` (ou o fornecido)
   - Usuário: `u494944867`
   - Senha: (sua senha FTP)
   - Porta: 21

2. **Navegue até `public_html/`**

3. **Faça upload de TODAS estas pastas e arquivos:**

   **Arquivos na raiz:**
   ```
   index.php
   .htaccess
   artisan
   composer.json
   package.json
   vite.config.js
   ```

   **Pastas completas:**
   ```
   app/
   bootstrap/
   config/
   database/
   resources/
   routes/
   storage/
   ```

4. **NÃO envie:**
   - `node_modules/`
   - `.git/`
   - `.env` (já foi criado na hospedagem)
   - Arquivos de teste

### Método 2: Via ZIP (Mais Rápido)

1. **No seu computador, compacte:**
   - Selecione todas as pastas e arquivos
   - Crie um ZIP (ex: `sistema_videira.zip`)
   - **NÃO inclua:** `node_modules/`, `.git/`, `.env`

2. **Faça upload do ZIP via cPanel File Manager:**
   - Acesse File Manager
   - Vá para `public_html/`
   - Faça upload do ZIP
   - Clique com botão direito > Extract

3. **Após extrair, via SSH:**
   ```bash
   cd ~/public_html
   ls -la composer.json artisan
   # Deve mostrar os arquivos agora
   ```

---

## 📋 Checklist de Upload

Após fazer upload, verifique via SSH:

```bash
cd ~/public_html

# Verificar arquivos essenciais
ls -la composer.json artisan index.php .htaccess

# Verificar pastas
ls -d app bootstrap config database resources routes storage

# Se algum não aparecer, o upload está incompleto!
```

---

## 🔧 Após Upload Completo

Execute estes comandos:

```bash
cd ~/public_html

# 1. Verificar se tudo chegou
ls -la composer.json artisan

# 2. Instalar dependências
php composer.phar install --no-dev --optimize-autoloader

# 3. Gerar APP_KEY
php artisan key:generate

# 4. Criar estrutura de storage (se necessário)
mkdir -p storage/logs storage/framework/{cache,sessions,views}
chmod -R 755 storage bootstrap/cache
chmod 666 storage/logs/laravel.log

# 5. Limpar cache
php artisan config:clear
php artisan cache:clear

# 6. Testar
curl -I https://yellow-spoonbill-121332.hostingersite.com
```

---

## ⚠️ IMPORTANTE

**Você PRECISA fazer upload de TODOS os arquivos do projeto!**

Apenas o `index.php` não é suficiente. O Laravel precisa de:
- `composer.json` (dependências)
- `artisan` (comandos)
- Todas as pastas (`app/`, `bootstrap/`, `config/`, etc.)

**Sem esses arquivos, o sistema NÃO funcionará!**

---

## 🎯 Resumo

1. **Faça upload de TODOS os arquivos** via FTP ou ZIP
2. **Verifique** se chegaram (via SSH: `ls -la composer.json`)
3. **Instale dependências** (`composer install`)
4. **Gere chave** (`php artisan key:generate`)
5. **Teste** o site

---

**Faça o upload completo primeiro, depois execute os comandos!**
