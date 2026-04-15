# 🔧 Configuração do Banco de Dados

## Credenciais Configuradas

As seguintes credenciais foram configuradas para o sistema:

### Banco de Dados MySQL
- **Host:** 127.0.0.1 (ou o host fornecido pela hospedagem)
- **Porta:** 3306
- **Nome do Banco:** `u494944867_videiradb`
- **Usuário:** `u494944867_videira`
- **Senha:** `Blade1411@20`

## 📝 Como Configurar o .env

Crie ou edite o arquivo `.env` na raiz do projeto com o seguinte conteúdo:

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

## ⚠️ Importante

1. **Substitua `https://seudominio.com.br`** pelo seu domínio real
2. **Gere a chave da aplicação** executando:
   ```bash
   php artisan key:generate
   ```
3. **Verifique o host do banco** - algumas hospedagens usam `localhost` ou um host específico como `mysql.hostinger.com`

## 🔍 Verificar Host do Banco

Se `127.0.0.1` não funcionar, verifique no cPanel:
- Acesse **phpMyAdmin**
- O host geralmente aparece na página inicial
- Pode ser: `localhost`, `mysql.hostinger.com`, ou outro

## ✅ Próximos Passos

Após configurar o `.env`:

1. **Testar conexão:**
   ```bash
   php artisan migrate:status
   ```

2. **Executar migrations:**
   ```bash
   php artisan migrate
   ```

3. **Popular banco de dados:**
   ```bash
   php artisan db:seed
   ```

4. **Limpar cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

## 🆘 Solução de Problemas

### Erro: "Access denied for user"
- Verifique se o usuário tem permissões no banco
- Confirme se a senha está correta (sem espaços extras)

### Erro: "Unknown database"
- Verifique se o nome do banco está correto
- Confirme que o banco foi criado no phpMyAdmin

### Erro: "Connection refused"
- Verifique o host do banco de dados
- Algumas hospedagens bloqueiam conexões externas
