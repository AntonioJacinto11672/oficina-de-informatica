# 🚀 GUIA DE EXECUÇÃO - Oficina Mecânica

## 1️⃣ CONFIGURAÇÃO INICIAL

### Copiar arquivo de exemplo
```bash
# O arquivo .env já foi criado, mas se precisar resetar:
copy .env.example .env
```

### Editar configurações (se necessário)
```bash
# Abra o arquivo .env e altere conforme seu ambiente:
# DB_HOST, DB_NAME, DB_USER, DB_PASS, APP_URL, etc.
```

---

## 2️⃣ INSTALAR DEPENDÊNCIAS (Já Feito ✓)

```bash
# Já instaladas! Se precisar atualizar:
composer install --ignore-platform-req=ext-gd

# Ou para atualizar para últimas versões:
composer update --ignore-platform-req=ext-gd
```

---

## 3️⃣ VALIDAR CONFIGURAÇÃO

```bash
# Execute o script de teste:
php test-config.php
```

**Resultado esperado:**
```
=== TESTE DE CONFIGURAÇÃO DO PROJETO ===

1. CARREGANDO CONFIGURAÇÕES:
   ✓ Arquivo .env carregado com sucesso

2. VERIFICANDO DEPENDÊNCIAS:
   ✓ Composer autoloader encontrado
   - PHPMailer: ✓
   - mPDF: ✓

3. TESTANDO CONEXÃO COM BANCO DE DADOS:
   ✓ Conexão com MySQL bem-sucedida

[... resto do output ...]

=== FIM DO TESTE ===
Se todos os testes passaram, o projeto está pronto para usar!
```

---

## 4️⃣ INICIAR O SERVIDOR LOCAL

### Opção A: Usar PHP Built-in Server
```bash
# Na raiz do projeto:
php -S localhost:8000
```

Acesse: http://localhost:8000

### Opção B: Usar Apache/Nginx
Certifique-se de que o document root aponta para a raiz do projeto.

---

## 5️⃣ ACESSAR A APLICAÇÃO

1. **URL Padrão:** http://localhost/oficinamecanica.co.ao/
2. **Ou conforme .env:** O valor definido em `APP_URL`
3. **Login:** Verifique com o administrador as credenciais

---

## 6️⃣ VERIFICAR ERROS

### Ver erros em tempo real
```bash
# Abra o arquivo error_log (se existir):
tail -f error.log

# Ou veja em php.ini:
error_log=./php_errors.log
display_errors=On (apenas desenvolvimento!)
```

### Modo Debug
```env
# Para ver debug adicional, edite .env:
DEBUG=true
```

---

## 7️⃣ PROBLEMAS COMUNS E SOLUÇÕES

### Erro: "Arquivo .env não encontrado"
```bash
# O arquivo foi criado, certifique-se de estar na raiz:
ls -la .env  # ou: dir .env (Windows)
```

### Erro: "Não conseguiu conectar ao MySQL"
```bash
# Verifique as credenciais no .env:
DB_HOST=localhost
DB_PORT=3306
DB_NAME=mecanica
DB_USER=root
DB_PASS=

# Teste a conexão:
mysql -h localhost -u root -p mecanica
```

### Erro: "Classe não encontrada"
```bash
# Regenere o autoloader:
composer dump-autoload
```

### Erro: "Extensão GD não ativada"
```bash
# Em php.ini, descomente:
# extension=gd

# Reinicie o servidor Apache/PHP-FPM
```

### Email de recuperação de senha não enviado

O sistema usa **Mailtrap** (as mesmas credenciais já configuradas no projeto). Confirme que o `.env` contém:

```ini
SMTP_HOST=smtp.mailtrap.io
SMTP_PORT=587
SMTP_SECURE=tls
SMTP_USER=c85e426e1ec5a1
SMTP_PASS=7cf202962d5c0e
```

Os emails enviados aparecem na caixa de entrada do Mailtrap em https://mailtrap.io.

---

## 8️⃣ VERIFICAÇÃO RÁPIDA DE SAÚDE

```bash
# Execute esta sequência para verificar tudo:
echo "=== Verificação Rápida ==="
php -v
composer --version
php test-config.php
```

---

## 9️⃣ ESTRUTURA DO PROJETO

```
oficina-mecanica/
├── index.php              (Entrada principal)
├── .env                   (Configurações locais - NÃO ENVIAR PARA GIT)
├── .env.example           (Exemplo de configuração)
├── composer.json          (Dependências)
├── composer.lock          (Lock das dependências)
├── test-config.php        (Script de validação)
│
├── core/
│   ├── Config.php        (✨ Novo - Gerenciar .env)
│   ├── ConfigController.php
│   ├── ConfigView.php
│   └── Permissao.php
│
├── app/
│   └── adms/
│       ├── Controllers/
│       ├── Models/
│       └── Views/
│
└── vendor/                (Dependências do Composer)
```

---

## 🔟 DICAS IMPORTANTES

✅ **Sempre execute `php test-config.php` após alterações**

✅ **Mantenha `.env` fora do repositório Git**

✅ **Use `.env.example` como referência**

✅ **Em produção, configure variáveis no servidor**

✅ **Faça backup regular do banco de dados**

---

## 📞 SUPORTE

Erro persistente? Verifique:
1. ✓ Arquivo `.env` existe e tem permissões de leitura
2. ✓ MySQL está rodando (`mysql -u root`)
3. ✓ Composer está instalado (`composer --version`)
4. ✓ PHP versão está correta (`php -v`)

Execute `php test-config.php` para diagnóstico completo!

---

*Versão: 1.1 | Atualizado: 15 de Junho de 2026*
