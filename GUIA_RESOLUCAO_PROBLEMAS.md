# 🔧 Guia de Resolução de Problemas - Sistema VIDEIRA

## 🚨 Problema Atual: Erro HTTP 500

### Diagnóstico Rápido

Execute estes comandos na ordem:

```bash
# 1. Ativar debug para ver erro
sed -i 's/APP_DEBUG=false/APP_DEBUG=true/' .env

# 2. Limpar todos os caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 3. Verificar permissões
chmod -R 755 storage bootstrap/cache
chmod 666 storage/logs/laravel.log

# 4. Limpar log e preparar para novo
> storage/logs/laravel.log

# 5. Testar acesso e ver log em tempo real
tail -f storage/logs/laravel.log
# (Em outro terminal, acesse o site)
```

---

## 📋 Checklist de Verificação Rápida

### ✅ Verificações Básicas

```bash
# 1. PHP funciona?
php -v

# 2. Composer funciona?
composer --version

# 3. .env existe e está configurado?
cat .env | grep -E "APP_KEY|DB_"

# 4. Vendor existe?
ls -la vendor/autoload.php

# 5. Views existem?
ls -la resources/views/auth/login.blade.php
ls -la resources/views/dashboard.blade.php

# 6. Rotas existem?
ls -la routes/web.php

# 7. Permissões corretas?
ls -ld storage bootstrap/cache
```

---

## 🔍 Scripts de Diagnóstico

### Script 1: Teste Básico
**Arquivo:** `teste_simples.php`
**Acesse:** `https://seudominio.com.br/teste_simples.php`
**O que faz:** Testa se PHP e Laravel carregam

### Script 2: Teste Completo
**Arquivo:** `testar_index.php`
**Acesse:** `https://seudominio.com.br/testar_index.php`
**O que faz:** Testa todo o fluxo do index.php

### Script 3: Ver Logs
**Arquivo:** `ver_logs.php`
**Acesse:** `https://seudominio.com.br/ver_logs.php`
**O que faz:** Mostra logs do Laravel

### Script 4: Testar Rotas
**Arquivo:** `testar_rotas.php`
**Acesse:** `https://seudominio.com.br/testar_rotas.php`
**O que faz:** Testa se rotas funcionam

### Script 5: Diagnóstico Completo
**Arquivo:** `diagnosticar_erro.php`
**Acesse:** `https://seudominio.com.br/diagnosticar_erro.php`
**O que faz:** Diagnóstico completo do sistema

---

## 🛠️ Soluções por Tipo de Erro

### Erro: "Class not found"
**Causa:** Autoload não funcionando
**Solução:**
```bash
composer dump-autoload --optimize
php artisan config:clear
```

### Erro: "View not found"
**Causa:** View não existe ou caminho errado
**Solução:**
```bash
# Verificar se existe
ls -la resources/views/auth/login.blade.php

# Se não existir, verificar controller
grep "view(" app/Http/Controllers/Auth/LoginController.php
```

### Erro: "Route not defined"
**Causa:** Rota não registrada
**Solução:**
```bash
php artisan route:clear
php artisan route:list
```

### Erro: "Database connection failed"
**Causa:** Credenciais erradas ou host incorreto
**Solução:**
```bash
# Testar conexão
php testar_conexao.php

# Verificar .env
grep DB_ .env

# Tentar localhost em vez de 127.0.0.1
sed -i 's/DB_HOST=127.0.0.1/DB_HOST=localhost/' .env
```

### Erro: "Permission denied"
**Causa:** Permissões incorretas
**Solução:**
```bash
chmod -R 755 storage bootstrap/cache
chmod 666 storage/logs/laravel.log
```

### Erro: "APP_KEY is not set"
**Causa:** Chave não gerada
**Solução:**
```bash
php artisan key:generate
# Ou usar gerar_chave_manual.php
php gerar_chave_manual.php
```

---

## 📊 Análise de Performance

### Métricas Atuais
- **Tempo de resposta:** Não medido (erro 500)
- **Uso de memória:** Não medido
- **Queries por página:** Não medido

### Recomendações de Otimização

#### 1. Cache (Após Resolver Erro 500)
```bash
# Produção
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Desenvolvimento (não usar cache)
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### 2. Autoloader
```bash
composer dump-autoload --optimize --classmap-authoritative
```

#### 3. Banco de Dados
- Adicionar índices nas colunas de busca
- Otimizar queries N+1
- Usar eager loading

---

## 🔐 Segurança - Checklist

### Configurações Essenciais

#### .env
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seudominio.com.br
```

#### Permissões
```bash
# Arquivos
chmod 644 .env index.php

# Pastas
chmod 755 storage bootstrap/cache

# Logs
chmod 666 storage/logs/laravel.log
```

#### Servidor
- [ ] HTTPS configurado
- [ ] Firewall ativo
- [ ] Backup automático
- [ ] Monitoramento de erros

---

## 📝 Logs e Monitoramento

### Onde Ver Logs

1. **Laravel:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Servidor (cPanel):**
   - Acesse: Logs > Error Log

3. **PHP:**
   - Verificar `php.ini` para localização

### O que Monitorar

- Erros 500, 404, 403
- Queries lentas
- Uso de memória
- Tentativas de login falhadas
- Ações administrativas

---

## 🎯 Próximas Melhorias Sugeridas

### Curto Prazo (1-2 semanas)
1. ✅ Resolver erro 500
2. ⬜ Implementar recuperação de senha
3. ⬜ Adicionar validação de email
4. ⬜ Melhorar tratamento de erros
5. ⬜ Adicionar logs estruturados

### Médio Prazo (1 mês)
1. ⬜ Sistema de backup automático
2. ⬜ Dashboard de métricas
3. ⬜ Exportação de relatórios
4. ⬜ Filtros avançados
5. ⬜ Notificações

### Longo Prazo (3+ meses)
1. ⬜ API REST
2. ⬜ App mobile
3. ⬜ Integração com outros sistemas
4. ⬜ Machine Learning para previsões
5. ⬜ Sistema de relatórios avançado

---

## 📚 Recursos de Aprendizado

### Laravel
- [Documentação Oficial](https://laravel.com/docs/10.x)
- [Laracasts](https://laracasts.com)
- [Laravel News](https://laravel-news.com)

### PHP
- [PHP The Right Way](https://phptherightway.com)
- [PHP Manual](https://www.php.net/manual)

### Banco de Dados
- [MySQL Documentation](https://dev.mysql.com/doc)
- [Eloquent ORM](https://laravel.com/docs/10.x/eloquent)

---

## ✅ Conclusão

O sistema está **quase pronto**. O erro 500 atual é o último obstáculo. Siga os passos de diagnóstico acima para identificar e resolver o problema específico.

**Prioridade:**
1. 🔴 Resolver erro 500 (URGENTE)
2. 🟡 Otimizar para produção
3. 🟢 Implementar melhorias

**Tempo Estimado para Resolução:** 1-2 horas

---

**Documento criado em:** 13/01/2026
**Versão:** 1.0
**Status:** Ativo
