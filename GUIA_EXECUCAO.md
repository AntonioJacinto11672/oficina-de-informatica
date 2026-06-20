# Guia de Execução — Assistência Técnica Informática

## 1. Configuração Inicial

### Copiar ficheiro de exemplo
```bash
# Linux / macOS / Git Bash
cp .env.example .env

# Windows PowerShell
Copy-Item .env.example .env
```

### Editar configurações
Abra o ficheiro `.env` e ajuste conforme o seu ambiente:

```ini
DB_HOST=localhost
DB_PORT=3306
DB_NAME=manutencao
DB_USER=root
DB_PASS=

APP_URL=http://localhost/oficina-de-equipamentos-informatico/
APP_NAME=ASSISTÊNCIA TÉCNICA INFORMÁTICA
```

---

## 2. Instalar Dependências

```bash
composer install
```

Se a extensão `gd` não estiver activa:
```bash
composer install --ignore-platform-req=ext-gd
```

---

## 3. Criar e Popular a Base de Dados

```bash
# Criar a base de dados
mysql -u root -e "CREATE DATABASE manutencao CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Aplicar o schema (Git Bash / Linux / macOS)
mysql -u root manutencao < database/schema.sql

# Windows CMD / PowerShell
"C:\xampp\mysql\bin\mysql.exe" -u root manutencao < database\schema.sql

> Em ambiente Windows, evite `Get-Content | mysql.exe` quando existir erro do plugin `caching_sha2_password.dll`. Use o redirecionamento direto para garantir que o cliente MySQL usa os ficheiros de plugin corretos.
```

---

## 4. Validar Configuração

```bash
php test-config.php
```

Resultado esperado:
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
=== FIM DO TESTE ===
```

---

## 5. Acessar a Aplicação

| URL | Descrição |
|-----|-----------|
| `http://localhost/oficina-de-equipamentos-informatico/` | Aplicação principal |
| `http://localhost/oficina-de-equipamentos-informatico/health.php` | Estado do sistema |
| `http://localhost/oficina-de-equipamentos-informatico/docs.php` | Documentação Swagger UI |

**Credenciais padrão:**

| Campo | Valor |
|-------|-------|
| Email | `antjacinto11672@gmail.com` |
| Senha | `12345` |

---

## 6. Verificar Erros

```bash
# Activar modo debug no .env:
DEBUG=true

# Ver log de erros PHP (XAMPP)
tail -f C:\xampp\php\logs\php_error_log
```

---

## 7. Problemas Comuns

| Problema | Solução |
|----------|---------|
| `Arquivo .env não encontrado` | `cp .env.example .env` |
| `Unknown database 'manutencao'` | `CREATE DATABASE manutencao` |
| `Class not found` | `composer install` |
| Página em branco | Activar `DEBUG=true` no `.env` |
| Redirecciona para login | Verificar `mod_rewrite` Apache |
| PDFs não geram | Activar extensão `gd` no `php.ini` |
| Email não enviado | Verificar `SMTP_*` no `.env` |

---

## 8. Estrutura do Projecto

```
oficina-de-equipamentos-informatico/
├── index.php                  (Entry point)
├── .env                       (Configurações locais — NÃO commitado)
├── .env.example               (Exemplo de configuração)
├── composer.json
├── health.php                 (Health check)
├── docs.php                   (Swagger UI)
├── swagger.json               (OpenAPI 3.0 spec)
├── core/
│   ├── Config.php             (Carrega .env)
│   ├── ConfigController.php   (Router + constantes)
│   ├── ConfigView.php         (Renderização de views)
│   └── Permissao.php          (Controlo de acesso)
├── app/adms/
│   ├── Controllers/           (32 controllers)
│   ├── Models/                (Lógica de negócio)
│   └── Views/                 (Templates HTML)
├── database/
│   ├── schema.sql             (Schema completo)
│   └── migrate_to_informatica.sql
└── vendor/                    (Composer)
```

---

## 9. Verificação Rápida

```bash
php -v                          # PHP 8.2+
composer --version              # Composer 2.x
php test-config.php             # Valida configuração
php validate-project.php        # Validação completa
```

---

*Versão: 3.0.1 | Atualizado: 19 de Junho de 2026*
