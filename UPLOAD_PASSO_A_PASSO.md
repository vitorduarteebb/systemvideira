# 🚀 UPLOAD PASSO A PASSO - Sistema VIDEIRA

## ⚠️ PROBLEMA ATUAL

Os arquivos do Laravel **NÃO estão na hospedagem**. Apenas o `index.php` está lá.

**Por isso nada funciona!**

## ✅ SOLUÇÃO: Fazer Upload de TODOS os Arquivos

### 📦 O QUE ENVIAR

Você precisa enviar **TODOS** estes arquivos e pastas para `public_html/`:

#### Arquivos na raiz:
- ✅ `index.php` (já está)
- ❌ `.htaccess` (FALTANDO)
- ❌ `artisan` (FALTANDO)
- ❌ `composer.json` (FALTANDO)
- ❌ `package.json` (FALTANDO)
- ❌ `vite.config.js` (FALTANDO)
- ❌ `phpunit.xml` (FALTANDO)

#### Pastas completas:
- ❌ `app/` (FALTANDO - TODA A PASTA)
- ❌ `bootstrap/` (FALTANDO - TODA A PASTA)
- ❌ `config/` (FALTANDO - TODA A PASTA)
- ❌ `database/` (FALTANDO - TODA A PASTA)
- ❌ `resources/` (FALTANDO - TODA A PASTA)
- ❌ `routes/` (FALTANDO - TODA A PASTA)
- ❌ `storage/` (FALTANDO - TODA A PASTA)

### 📤 COMO FAZER UPLOAD

#### Opção 1: Via FTP (FileZilla, WinSCP, etc.)

1. **Abra seu cliente FTP** (FileZilla, WinSCP, etc.)

2. **Conecte ao servidor:**
   - Host: `ftp.seuservidor.com` (ou IP)
   - Usuário: `u494944867`
   - Senha: (sua senha FTP)
   - Porta: `21`

3. **Navegue até `public_html/`** no servidor

4. **No seu computador**, navegue até a pasta:
   ```
   C:\Users\oem\OneDrive\Área de Trabalho\sistema videira
   ```

5. **Selecione e arraste** todos estes arquivos/pastas:
   - `.htaccess`
   - `artisan`
   - `composer.json`
   - `package.json`
   - `vite.config.js`
   - `phpunit.xml`
   - Pasta `app/` (inteira)
   - Pasta `bootstrap/` (inteira)
   - Pasta `config/` (inteira)
   - Pasta `database/` (inteira)
   - Pasta `resources/` (inteira)
   - Pasta `routes/` (inteira)
   - Pasta `storage/` (inteira)

6. **Aguarde o upload terminar** (pode demorar alguns minutos)

#### Opção 2: Via ZIP (MAIS RÁPIDO) ⚡

1. **No seu computador**, na pasta do projeto:
   ```
   C:\Users\oem\OneDrive\Área de Trabalho\sistema videira
   ```

2. **Selecione** todos estes arquivos e pastas:
   - `.htaccess`
   - `artisan`
   - `composer.json`
   - `package.json`
   - `vite.config.js`
   - `phpunit.xml`
   - Pasta `app/`
   - Pasta `bootstrap/`
   - Pasta `config/`
   - Pasta `database/`
   - Pasta `resources/`
   - Pasta `routes/`
   - Pasta `storage/`

3. **Crie um ZIP** (botão direito > Enviar para > Pasta compactada)

4. **Acesse o cPanel** da sua hospedagem

5. **Abra o File Manager**

6. **Vá para `public_html/`**

7. **Faça upload do ZIP**

8. **Extraia o ZIP** (botão direito > Extract)

9. **Delete o arquivo ZIP** após extrair

### ✅ VERIFICAR SE FUNCIONOU

Após o upload, via SSH execute:

```bash
cd ~/public_html

# Verificar se arquivos chegaram
ls -la composer.json artisan

# Deve mostrar:
# -rw-r--r-- 1 u494944867 ... composer.json
# -rwxr-xr-x 1 u494944867 ... artisan
```

**Se aparecerem os arquivos, continue!**

### 🔧 APÓS O UPLOAD - Configurar Sistema

```bash
cd ~/public_html

# 1. Instalar dependências
php composer.phar install --no-dev --optimize-autoloader

# 2. Criar .env (se não existir)
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

# 3. Gerar APP_KEY
php artisan key:generate

# 4. Criar estrutura de storage
mkdir -p storage/logs storage/framework/{cache,sessions,views}
chmod -R 755 storage bootstrap/cache
chmod 666 storage/logs/laravel.log 2>/dev/null || touch storage/logs/laravel.log && chmod 666 storage/logs/laravel.log

# 5. Executar migrations
php artisan migrate --force

# 6. Popular banco
php artisan db:seed --force

# 7. Limpar cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# 8. Testar
curl -I https://yellow-spoonbill-121332.hostingersite.com
```

## ⚠️ IMPORTANTE

- **SEM os arquivos do Laravel, NADA vai funcionar!**
- Você **DEVE** fazer upload de **TODOS** os arquivos listados acima
- Não adianta tentar executar comandos sem os arquivos estarem lá

## 📞 PRÓXIMOS PASSOS

1. ✅ Fazer upload de todos os arquivos
2. ✅ Verificar se chegaram (`ls -la composer.json artisan`)
3. ✅ Executar os comandos de configuração acima
4. ✅ Testar o site no navegador

---

**Faça o upload primeiro, depois execute os comandos!**
